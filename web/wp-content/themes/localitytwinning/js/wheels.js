var sponsoredAcode;

function initLocalStorage(c_name)
{
    if(!localStorage.getItem(c_name)){
        localStorage.setItem('compare',JSON.stringify({vehicles: new Array()}));
    }
}

function setLocalstorageItem(c_name,value,exdays)
{
    initLocalStorage('compare');
    localStorage.removeItem(c_name);
    localStorage.setItem(c_name,value);
}

function getLocalstorageItem(c_name)
{
    initLocalStorage('compare');
    return localStorage.getItem(c_name);
}

function acodeExists(acode)
{
    var exists = false;

    var compareStr = getLocalstorageItem('compare');

    if(typeof(compareStr) == 'undefined'){
        compareStr = JSON.stringify({vehicles: new Array()});
    }

    var compare = JSON.parse(compareStr);

    if(compare.vehicles.length > 0)
    {
        for(i=0; compare.vehicles.length > i; i++)
        {
            if(compare.vehicles[i] == acode)
            {
                exists = true;
                break;
            }
        }
    }

    if(exists){
        $("#galert").trigger($.Events.OPEN);
        $("#galert #galert-title").html("Vehicle already exists.");
        return true;
    }else{
        return false;
    }

}

function addToCompare(vehicle, max)
{
    var exists = false;

    var compareStr = getLocalstorageItem('compare');

    if(typeof(compareStr) == 'undefined'){
        compareStr = JSON.stringify({vehicles: new Array()});
    }

    var compare = JSON.parse(compareStr);

    if(compare.vehicles.length == max) return false;

    if(compare.vehicles.length == 0)
    {
        compare.vehicles.push(vehicle);
        exists = true;
    }else{
        for(i=0; compare.vehicles.length > i; i++)
        {
            if(compare.vehicles[i] == vehicle)
            {
                exists = true;
                break;
            }
        }
    }

    if(!exists)
    {
        compare.vehicles[compare.vehicles.length] = vehicle;
    }
    compareStr = JSON.stringify(compare);
    setLocalstorageItem('compare', compareStr);
    return compare.vehicles.length;
}

function getCompareCount()
{
    var compareStr = getLocalstorageItem('compare');
    var count = 0;
    if(typeof(compareStr) != 'undefined'){
        var compare = JSON.parse(compareStr);
        count = compare.vehicles.length + 1;
    } else {
        count = 1;
    }

    jQuery('#compare-menu-item a').text('');
    jQuery('#compare-menu-item a').text('Compare ('+count+')');
}

function fillCompareCart()
{
    //clearing compare cart
    clearCompareCart();
    //
    var compareStr = getLocalstorageItem('compare');
    if(typeof(compareStr) != 'undefined'){

        var compare = JSON.parse(compareStr);
        if (compare.vehicles.length > 0) {
            var strAcode = compare.vehicles.join("|") + "|" + sponsoredAcode;
        } else {
            var strAcode = sponsoredAcode;
        }

        if(strAcode.length){
            //retriving vehicle info

            $.ajax({
                type   : "POST",
                cache  : false,
                url     : "/wp-content/plugins/wheels-compare/ajax-call.php",
                data   : "acode="+strAcode,
                dataType: 'json',
                beforeSend: function(){
                    $("#bg-compare-menu .compare-conatiner .compare-item").html('<img src="/wp-content/themes/wheels/img/ajax-loader.gif" class="ajax-loader" alt="Loading">');

                },
                success: function(data) {
                    $("#bg-compare-menu .compare-conatiner .compare-item").html('');
                    if(data.length){
                        var start = 4 - data.length;
                        for(i=0;i<=(data.length-1);i++){

                            var cross = '<a href="javascript:void(0)" onclick="discardItem(\''+data[i].acode+'\')" class="close small">X</a>';
                            if(data[i].acode == sponsoredAcode){
                                cross = '<span class="sponsor">Sponsored</span>';
                            }
                            var x = '<img src="'+data[i].image_link+'" alt="'+data[i].profile_title+'"><div class="copy"><div class="pos"><p>'+data[i].profile_title+'</p></div></div>'+cross;
                            $("#bg-compare-menu .compare-conatiner #comp-item"+(i+start+1)).html(x);


                        }
                    }

                }
            });
        }

    }
}

function clearCompareCart(){
    $("#bg-compare-menu .compare-conatiner .compare-item").html('&nbsp;');
}

function discardItem(acode){
    var compareStr = getLocalstorageItem('compare');

    var compare = JSON.parse(compareStr);

    for(i=0; compare.vehicles.length > i; i++)
    {
        if(compare.vehicles[i] == acode)
        {
            compare.vehicles.splice(i,1);
        }
    }
    compareStr = JSON.stringify(compare);
    setLocalstorageItem('compare', compareStr);
    getCompareCount();

    if(typeof window.fillComparePage == 'function') {
        // function exists, so we can now call it
        fillComparePage();
    }
    fillCompareCart();
    //-----------------------
    if(typeof window.onLoadSuggestion == 'function') {
        // function exists, so we can now call it
        onLoadSuggestion();
    }
    //-----------------------
}

function number_format (number, decimals, dec_point, thousands_sep) {
    //Source:http://phpjs.org/functions/number_format:481
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

// Update model, change model depend makeCode
var updateModel = function (modeSelectId, makeCode, selectedValue, extraParam)
{
    // Re-build select box
    if(typeof(selectedValue) == 'undefined') selectedValue = '';
    if(typeof(extraParam) == 'undefined') extraParam = '';

    var $select = jQuery('#'+modeSelectId)
        .parent()
        .html('<select id="'+modeSelectId+'" data-role="none" name="model" data-controller="ComboboxController" class="filter-selector ui-menu-model ui-light">')
        .find("select");

    // push model which matched makeCode
    if(makeCode == 'none')
    {
        $select.append( jQuery("<option />").attr("value", 'none').text('All Models') );
    }
    else
    {
        $select.append( jQuery("<option />").attr("value", 'none').text('All Models') );
        $.each(modelList, function () {
            if(this.makeCode ==  makeCode)
            {
                if(selectedValue == this.modelName){
                    $select.append( jQuery('<option selected="selected" />').attr("value", this.modelName).text(this.modelName) );
                }else{
                    $select.append( jQuery("<option />").attr("value", this.modelName).text(this.modelName) );
                }
            }
        });
    }

    // Reinitialize combobox
    $select.combobox();

    // Bind event for change trip value
    if(modeSelectId == 'finder-filter-model'){
        jQuery('#'+modeSelectId).bind('comboboxselected', function(event, ui){
            doSearch();
            updateTrim();
        });
    }
}

function loadUsedCar(make,model,year,class_name,style,showLimit)
{
    var wheels_restful_service_url = 'http://vehicles.wheels.ca/wheelsservice/WheelsService.svc/GetVehicles';
    var wheels_restful_service_params = '';

    if(make != '') wheels_restful_service_params += '&make1=' + make;
    if(model != '') wheels_restful_service_params += '&model1=' + model;
    //if(year != '') wheels_restful_service_params += '&yearfrom=' + year;
    if(class_name != '') wheels_restful_service_params += '&class=' + class_name;
    if(showLimit != '') wheels_restful_service_params += '&number=' +  showLimit;

    jQuery.ajax({
        url: wheels_restful_service_url,
        type: 'GET',
        dataType: "jsonp",
        data: wheels_restful_service_params,
        success: function(json){
            jQuery('.used-listings .listing').empty();
            jQuery('.used-vehicles .listing').empty();
            for(i = 0; i < json.length; i++)
            {
                var obj = json[i];
                {
                    if(style == 'style_1'){
                        var data = '<li class="vehicle clearfix"><div class="wrap"><a target="_blank" href="'+obj.LinkUrl+'"><img alt="'+ obj.Year +' '+ obj.Make +' '+ obj.Model+'" src="'+obj.ThumbUrl+'" width="120" height="68"><div class="copy"><span class="title">'+ obj.Year +' '+ obj.Make +' '+ obj.Model +'</span><strong class="price">$'+ number_format(obj.Price) +'</strong></div></a></div></li>';
                        jQuery('.used-listings .listing').append(data);
                    }else if(style == 'style_2'){
                        var data = '';
                        data += '<li>';
                        data += '<div class="wrap"><a target="_blank" href="'+obj.LinkUrl+'"><img alt="'+ obj.Year +' '+ obj.Make +' '+ obj.Model+'" src="'+obj.ThumbUrl+'" width="120" height="68" />';
                        data += '<p>'+ obj.Year +' '+ obj.Make +' '+ obj.Model+'<strong class="price">$'+ number_format(obj.Price) +'</strong></p>';
                        data += '</a></div>';
                        //data += '<a data-id="" href="#" class="compare callout">Compare <img alt="Compare this vehicle" src="/img/compare-callout.png"/></a>';
                        data += '</li>';//number_format

                        jQuery('.used-vehicles .listing').append(data);
                        jQuery('.used-vehicles .listing li:odd').addClass('even last');
                        jQuery('.used-vehicles .listing li:even').addClass('odd');
                    }else{
                        var data = '<li class=""><div class="wrap"><a target="_blank" href="'+obj.LinkUrl+'"><img alt="'+ obj.Year +' '+ obj.Make +' '+ obj.Model +'" src="'+obj.ThumbUrl+'" width="120" height="68" /><p>'+ obj.Year +' '+ obj.Make +' '+ obj.Model +'<strong class="price">$'+ number_format(obj.Price) +'</strong></p></a></div></li>';
                        jQuery('.used-vehicles .listing').append(data);
                        jQuery(".used-vehicles .listing").find('li:nth-child(3),li:nth-child(6)').addClass('last');
                    }
                }
            }

        }

    });
}

function loadUsedCar2(limit){
    var wheels_restful_service_url = 'http://vehicles.wheels.ca/wheelsservice/WheelsService.svc/GetVehicles';
    var wheels_restful_service_params = '';
    wheels_restful_service_params += '&number=' +  limit;
    jQuery.ajax({
        url: wheels_restful_service_url,
        type: 'GET',
        dataType: "jsonp",
        data: wheels_restful_service_params,
        success: function(json){
            jQuery('#used-car-instead-answer-center .listing').empty();
            for(i = 0; i < json.length; i++)
            {
                var obj = json[i];
                var data = '<li class="vehicle clearfix"><div class="wrap"><a target="_blank" href="'+obj.LinkUrl+'"><img alt="'+ obj.Year +' '+ obj.Make +' '+ obj.Model+'" src="'+obj.ThumbUrl+'" width="120" height="68"><div class="copy"><span class="title">'+ obj.Year +' '+ obj.Make +' '+ obj.Model +'</span><strong class="price">$'+ number_format(obj.Price) +'</strong></div></a></div></li>';
                jQuery('#used-car-instead-answer-center .listing').append(data);

            }
        }
    });
}

function loadComment(postId, page, sort, scroll)
{
    jQuery.ajax({
        async: false,
        'type': 'post',
        'data': 'post_id='+postId+'&page='+page+'&sort='+sort,
        url: '/wp-content/themes/wheels/comment-ajax.php',
        success: function(html)
        {
            jQuery('#comment-loader').html(html);
            jQuery('#comment-loader .sort-comments').combobox();
            jQuery('#comment-loader .sort-comments').bind('comboboxselected', function(event, ui){
                var post_id = jQuery('#commentPostId').val();
                var orderby = jQuery('#comment-loader .sort-comments').val();
                var page = jQuery('#currentCommentPage').val();
                loadComment( post_id, page, orderby, false );
            });
            if(scroll){
                if(window.location.hash)
                {
                    var name = window.location.hash.replace('#', '');
                    jQuery('html, body').animate({ scrollTop: jQuery('a[name="comment-container"]').offset().top - 180 }, 500);
                }
            }

        },
        beforeSend: function()
        {
            // loading icon here
        },
        error: function()
        {
            // alert('Could not load comment')
        }
    });
    return false;
}

function commentPopularity(commentId, action, updateElm, containerElm)
{
    jQuery.ajax({
        async: false,
        'type': 'post',
        'data': 'comment_id='+commentId+'&action='+action,
        url: '/wp-content/themes/wheels/comment-popularity-ajax.php',
        success: function(html)
        {
            containerElm.css('opacity', 1);
            updateElm.text(html);
        },
        beforeSend: function()
        {
            containerElm.css('opacity', .5);
        },
        error: function()
        {
            // alert('Could not execute ajax request')
        }
    });
    return false;
}

function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

if(typeof(LOAD_USED_CAR) != 'undefined')
{
    loadUsedCar(VEHICLE_MAKE, VEHICLE_MODEL, '', VEHICLE_CLASS, 'style_1', 4);
}

$("#register-form").live("submit",function(){

    $("div.content div.registration-form .status").empty();
    $("div.content div.registration-form #agree-terms").next(".agree-terms").css("color","#222222");
    //client side validation
    var cbool_error = false;
    var email = '';
    var username = '';
    var pass = '';
    var pass2 = '';

    if($.trim($("div.content div.registration-form #reg-email").val())==''){
        email = 'Email address required.';
        cbool_error = true;
    }
    if($.trim($("div.content div.registration-form #username").val())==''){
        username = 'Username required.';
        cbool_error = true;
    }
    if($.trim($("div.content div.registration-form #password").val())==''){
        pass = 'Password required.';
        cbool_error = true;
    }
    if($.trim($("div.content div.registration-form #password2").val())==''){
        pass2 = 'Confirm password required.';
        cbool_error = true;
    }
    if(!$("div.content div.registration-form #agree-terms").next(".agree-terms").hasClass("checked")){
        $("div.content div.registration-form #agree-terms").next(".agree-terms").css("color","red");
        cbool_error = true;
    }
    if(cbool_error)
    {
        var cdata = {"error":"true","email":email,"username":username,"pass":pass,"pass2":pass2};
        show_status(cdata);
    }
    else
    {
        //server side validation
        var form_data = $("#register-form").serialize();
        $.ajax({
            type	: "POST",
            cache	: false,
            url     : "/wp-content/plugins/wheels-my-wheels/ajax-call.php",
            data	: form_data,
            dataType: 'json',
            beforeSend: function(){
                $("#modal-screens #registration .registration-form  #reg-submit-loading").html('<img src="/wp-content/themes/wheels/img/ajax-loader.gif" alt="" />');
            },
            success: function(data) {
                $("#modal-screens #registration .registration-form  #reg-submit-loading").html('');
                show_status(data);
            }
        });
    }
    return false;
});

$("#terms_link").live("click",function(){
    $("#termsview").trigger($.Events.OPEN);
});

$("#modal-screens #close-term").live("click",function(){
    $("#registration").trigger($.Events.OPEN);
});

$("#modal-screens #close-confirmation").live("click",function(){
    window.location = document.URL;
})

function show_status(data)
{
    if(data.error=='true')
    {
        if(data.email!=''){
            $("div.content div.registration-form .email-status").removeClass("available");
            $("div.content div.registration-form .email-status").html(data.email);
            $("div.content div.registration-form .email-status").addClass("error");
        }
        if(data.username!=''){
            $("div.content div.registration-form .username-status").removeClass("available");
            $("div.content div.registration-form .username-status").html(data.username);
            $("div.content div.registration-form .username-status").addClass("error");
        }
        if(data.pass!=''){
            $("div.content div.registration-form .pass-status").removeClass("available");
            $("div.content div.registration-form .pass-status").html(data.pass);
            $("div.content div.registration-form .pass-status").addClass("error");
        }
        if(data.pass2!=''){
            $("div.content div.registration-form .pass2-status").removeClass("available");
            $("div.content div.registration-form .pass2-status").html(data.pass2);
            $("div.content div.registration-form .pass2-status").addClass("error");
        }
        return false;
    }
    else
    {
        _gaq.push(['_trackEvent', 'User Accounts', 'Sign ups']);

        //confirmation popup
        $("#confirmation").trigger($.Events.OPEN);
    }
}

$("#signin-form").live("submit",function(){

    if ($('#wp-pwd').val() != '') {
        return true;
    }

    $("div.content div.email-signin .status").empty();

    //client side validation
    var cbool_error = false;
    var email = '';
    var pass = '';

    if($.trim($("div.content div.email-signin #signin-form #email").val())==''){
        email = 'Email address required.';
        cbool_error = true;
    }
    if($.trim($("div.content div.email-signin #signin-form #password").val())==''){
        pass = 'Password required.';
        cbool_error = true;
    }
    if(cbool_error)
    {
        var cdata = {"error":"true","email":email,"pass":pass,"login":''};
        show_status1(cdata);
    }
    else
    {
        //server side validation
        var form_data = $("#signin-form").serialize();
        $.ajax({
            type	: "POST",
            cache	: false,
            url     : "/wp-content/plugins/wheels-my-wheels/ajax-call.php",
            data	: form_data,
            dataType: 'json',
            success: function(data) {
                show_status1(data);
            }
        });
    }
    return false;
});

function show_status1(data)
{
    if(data.error=='true')
    {
        if(data.email!=''){
            $("div.content div.email-signin .email-status").html(data.email);
            $("div.content div.email-signin .email-status").addClass("error");
        }
        if(data.pass!=''){
            $("div.content div.email-signin .pass-status").html(data.pass);
            $("div.content div.email-signin .pass-status").addClass("error");
        }
        if(data.login!=''){
            $("div.content div.email-signin .login-status").html(data.login);
            $("div.content div.email-signin .login-status").addClass("error");
        }
        return false;
    }
    else
    {

        $('#wp-log').val(data.username);
        $('#wp-pwd').val($('#password').val());

        var url = data.redir_url;
        var url_parts = url.split("?");
        var main_url = url_parts[0];
        var site_url = data.site_url;
        if(main_url == site_url+'/lostpassword'){
            $('#wp-redir').val(site_url);
        }
        else{
            $('#wp-redir').val(data.redir_url);
        }
        $('#signin-form').attr("action", '/wp-login.php');
        $('#signin-form').submit();
    }
}

function getSpecialOffer(make)
{
    jQuery.ajax({
        url:'/get-special-offer.php',
        type:'POST',
        dataType: 'json',
        data:'make='+make,
        beforeSend: function(){
            jQuery('#more-special-offer').hide();
            jQuery( '.special-offers .offers ul' ).html('<img src="/wp-content/themes/wheels/img/ajax-loader.gif" class="ajax-loader" alt="Loading">');
        },
        success:function(json){
            jQuery('.special-offers .offers').find('img.ajax-loader').remove();
            jQuery( '.special-offers .offers ul' ).html(json.data);
            if( parseInt(json.total) > 4 )
            {
                jQuery('#more-special-offer').show();
            }else{
                jQuery('#more-special-offer').hide();
            }
        }
    });
}

function openSpecialOfferLightbox()
{
    $("#special-offer-lightbox").trigger($.Events.OPEN);
    jQuery.ajax({
        url:'/get-special-offer.php',
        type:'POST',
        dataType: 'json',
        data:'limit=3000&make='+jQuery('#special-offer-make').val(),
        beforeSend: function(){
            //jQuery('#more-special-offer').hide();
            jQuery("#special-offer-lightbox-body").html('<img src="/wp-content/themes/wheels/img/ajax-loader.gif" class="ajax-loader" alt="Loading">');
        },
        success:function(json){
            var html = '<div class="offers"><ul>'+ json.data +'</ul></div>';
            jQuery("#special-offer-lightbox-body").html(html);
        }
    });
}

jQuery(document).ready(function ($) {
    $("#finish").css("float", "left");
    $("#finish").css("clear", "both");

    var x = getUrlVars();
    if (x['act'] == 'opensignup') {
        setTimeout(function () {
            $("#registration").trigger($.Events.OPEN);
        }, 100);
    } else {

    }

    $(".avt-lnk").live("click", function () {
        var src = $(this).children('img').attr('src');
        $(".avatar-selection img.current-avatar").attr('src', src);
        $("#hdn_avatar").val($(this).attr('rel'));
        return false;
    });

    //file upload
    rajax_obj1 = new rajax('form_upload',
        {
            finputs:{
                file:{
                    button:'upload_link',
                    multipleFile:false,
                    allowedExt:'jpg|jpeg|gif|png',
                    selectedFileClass:'selectedFileClass',
                    selectedFileLabel:'selected_basic_file',
                    onChange:function (file, ext) {
                        setTimeout(function () {
                            rajax_obj1.post()
                        }, 100);
                        return true;
                    }
                }
            },
            responseType:'json',
            onSubmit: function(){
                $("#form_upload .current-avatar").attr('src', '/wp-content/themes/wheels/img/ajax-loader.gif');
            },
            action:'/wp-content/plugins/wheels-my-wheels/rajax.php',
            onComplete:function (response) {
                //console.log(response);
                //$("#form_upload .current-avatar").attr('src', '');
                if (response.error == 'false') {
                    $("#form_upload .current-avatar").attr('src', response.filepath);
                    $("#hdn_avatar").val(response.filename);
                    $("#hdn_avatar_path").val(response.filepath);
                }
                $("#form_upload")[0].reset();
            }
        });

    getCompareCount();

    jQuery('.callout, a.compare-vehicle').live('click', function(){

        if(jQuery(this).attr('rel')!='')
        {
            var selectedAcode = jQuery(this).attr('rel');
            var isSponsored = false;

            $.each(sponsoredlList, function(index, value) {
                if (selectedAcode == value) {
                    isSponsored = true;
                    return false;
                }
            });

            if (isSponsored) {
                $("#galert").trigger($.Events.OPEN);
                $("#galert #galert-title").html("Vehicle already exists as default sponsored item.");
                return false;
            }

            if(acodeExists(jQuery(this).attr('rel'))){
                return false;
            }

            var added = addToCompare( jQuery(this).attr('rel'), 3 );
            if(added)
            {
                jQuery('#compare-menu-item a').text('compare ('+added+')');
                getCompareCount();
                $("#galert").trigger($.Events.OPEN);
                $("#galert #galert-title").html("Vehicle added to compare.");
                if(typeof window.fillComparePage == 'function') {
                    // function exists, so we can now call it
                    fillComparePage();
                }
                fillCompareCart();
            }else
            {
                $("#galert").trigger($.Events.OPEN);
                $("#galert #galert-title").html("You have reached max limit");
            }

            return false;
        }
    });

    jQuery('#filter-make').bind('comboboxselected', function(event, ui){
        updateModel( 'filter-model', ui.item.className );
    });

    jQuery('#comment-loader  a.page-numbers').live('click', function(){
        var post_id = jQuery('#commentPostId').val();
        var orderby = jQuery('#comment-loader .sort-comments').val();
        var page = jQuery(this).attr('href').split('=');

        var page_num = page[1];
        if ( typeof(page_num) == 'undefined') page_num = 1;
        loadComment( post_id, page_num, orderby, false );
        return false;
    });

    jQuery('.comments .like').live('click', function()
    {
        if(typeof(LOGGED_IN) == 'undefined' || LOGGED_IN == 'false')
        {
            alert('Login required');
            return false;
        }
        var commentID = jQuery(this).attr('rel');
        return commentPopularity(commentID, 'add', jQuery(this).next(), jQuery(this).parent());
    });

    jQuery('.comments .dislike').live('click', function()
    {
        if(typeof(LOGGED_IN) == 'undefined'  || LOGGED_IN == 'false')
        {
            alert('Login required');
            return false;
        }

        var commentID = jQuery(this).attr('rel');
        return commentPopularity(commentID, 'remove', jQuery(this).prev(), jQuery(this).parent());
    });

    // Validate comment form
    jQuery('form.commentform').submit(function()
    {
        var formValid = new Array();

        if( jQuery('#author', this).length && jQuery.trim( jQuery('#author', this).val() ) == '' )
        {
            jQuery('#author', this).css('border', '1px solid #f00');
            formValid[0] = false;
        }else{
            jQuery('#author', this).css('border', 'none');
        }

        var emailValidRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,6}$/;
        if( jQuery('#email', this).length && jQuery.trim( jQuery('#email', this).val() ) == '' )
        {
            jQuery('#email', this).css('border', '1px solid #f00');
            formValid[0] = false;
        }else if(jQuery('#email', this).length && emailValidRegex.test( jQuery('#email', this).val() ) ==  false )
        {
            jQuery('#email', this).css('border', '1px solid #f00');
            formValid[0] = false;
        }else
        {
            jQuery('#email', this).css('border', 'none');
        }

        if( jQuery.trim( jQuery('#comment', this).val() ) == '' )
        {
            jQuery('#comment', this).css('border', '1px solid #f00');
            formValid[0] = false;
        }else{
            jQuery('#comment', this).css('border', 'none');
        }

        if( formValid.length > 0 ) return false;

    });

    jQuery('#browse-categories ul li a, .find-next-vehicle ul li a').click(function(){
        jQuery('#vehicle-finder-category').val( jQuery.trim( jQuery(this).text() ) );
        jQuery('#header-vehicle-finder-category-form').submit();
        return false;
    });
    jQuery('.review-and-profile .links a').click(function(){
        jQuery('#vehicle-finder-category').attr('name', 'class').val( jQuery.trim( jQuery(this).attr('rel') ) );
        jQuery('#header-vehicle-finder-category-form').submit();
        return false;
    });


    for(i=1; i<=4; i++){
        var elm = $('li.footer-navigation-'+ i);
        var className = elm.attr('class');
        elm.wrap('<div class=" '+ className +' col" />');
        elm.find('a:first').wrap('<h5/>');
        var el = elm.html();
        var parent= elm.parent();
        elm.remove();
        parent.prepend(el);
    }

    if(typeof(LOAD_COMMENT) != 'undefined' && typeof(POST_ID) != 'undefined'){
        loadComment(POST_ID, 1, 'recent', true);
    }

    $("#term-service-link").live("click",function(){
        $("#termsview").trigger($.Events.OPEN);
        return false;
    });

    $("#via-continue").click(function(){
        $("#register-form #reg-email").val($("#via-email").val());
        return false;
    });

    $("#via-email-form").submit(function(){
        $("#register-form #reg-email").val($("#via-email").val());
        $("#registration").trigger($.Events.OPEN);
        return false;
    });

    // Execute on News and Feature landing page
    // Flip crasoup latest/popular
    jQuery('.carousel-bullets:eq(1)').hide();
    jQuery('.order-by-list li, .pill-menu li:not(.ac)').click(function () {

        if(jQuery('.pill-menu li').length){
            var index = jQuery('.pill-menu:eq(0) li').index(jQuery(this));
        }else{
            var index = jQuery('.order-by-list li').index(jQuery(this));
        }

        jQuery('.reviewCarouselContainer').hide();
        jQuery('.carousel-bullets').hide();

        jQuery('.reviewCarouselContainer:eq(' + index + ')').show();
        jQuery('.carousel-bullets:eq(' + index + ')').show();

        jQuery('.pill-menu:eq(0) li').removeClass('on');
        jQuery('.order-by-list li').removeClass('on');
        jQuery(this).addClass('on');
        return false;
    });

    jQuery('.review-count').click(function(){
        jQuery('html, body').animate({ scrollTop: jQuery('a[name="comment-container"]').offset().top - 180 }, 500);
    });

    if( jQuery('#used-car-instead-answer-center').length )
    {
        loadUsedCar2(4);
    };

    setTimeout(function(){
        if( jQuery('#used-car-instead-answer-center').length )
        {
            loadUsedCar2(4);
        };
    }, 2000);

    if(sponsoredAcode == undefined)
    {
        var compareStr = getLocalstorageItem('compare');
        var compare = JSON.parse(compareStr);

        if(compare.vehicles.length > 0){
            getClassFor(compare.vehicles[0], false);
        } else {
            sponsoredAcode = sponsoredlList['Sedan'];
            if ($('body.compare h1').length > 0) {
                fillComparePage();
            }
            fillCompareCart();
        }
    }

    // Add class open to show arrow down
    jQuery('#vehicle-filters li.class, #vehicle-filters li.make:eq(1), #vehicle-filters li.price').addClass('open');
    // Expend filter container
    jQuery('#vehicle-filters li.class .collapsible, #vehicle-filters li.make:eq(1) .collapsible, #vehicle-filters li.price .collapsible').slideDown();

    jQuery('#logout').click(function(){
        var url = jQuery(this).attr('rel');
        if(typeof FB.logout == 'function'){
            if (FB.getAuthResponse())
            {
                FB.logout(function(response)
                {
                    window.location.href = url;
                });
                return;
            }
        }
        window.location.href = url;
        return;
    });

    jQuery('#home .answer-centre .pill-menu li-0').click(function () {

        var container = jQuery( jQuery(this).find('a').attr('href') );

        var index = jQuery('.pill-menu li', container).index(jQuery(this));

        jQuery('#home .answer-centre').hide();

        container.show();

        jQuery('.pill-menu li', container).removeClass('on');

        jQuery('.pill-menu li:eq('+index+')', container).addClass('on');
        return false;
    });

    // Bind special offer combobox
    jQuery('#special-offer-make').bind('comboboxselected', function(event, ui){
        getSpecialOffer(ui.item.value);
    });
    if( jQuery('#special-offer-make').length ) getSpecialOffer( jQuery('#special-offer-make').val() );

    jQuery('#more-special-offer').click(function(){
        openSpecialOfferLightbox();
        return false;
    });

    jQuery('.special-offer-more-link').live('click', function(){
        jQuery( '.special-offer-details' ).hide();
        jQuery( jQuery(this).attr('href') ).show();
        jQuery( '.special-offer-more-link' ).not( jQuery( this ) ).show();
        jQuery( this ).hide();
        return false;
    });

    jQuery('.special-offer-hide-details').live('click', function(){
        jQuery( '.special-offer-details' ).hide();
        jQuery( '.special-offer-more-link' ).show();
        return false;
    });

    $.Body = $('body');
    $.isMobile = ($.Body.hasClass('webkit-mobile') || (navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)));
    if(!$.isMobile)
    {
        jQuery('#home .home-reviews-container-popular .carousel-bullets').show();
    }

    if( getCookie('isMobile') == 'no' )
    {
        jQuery('div.vehicle-gallery div.img').css('height', 315);
    }

    if(!isMobilePage){
        jQuery('#main, #footer').css('width', 'auto');
    }

    if(jQuery.browser.msie)
    {
        setTimeout(function(){
            jQuery('.carousel[data-controller]').each(function(){
                jQuery(this).find('img').each(function(){
                    var imgSrc = jQuery(this).attr('src');
                    jQuery(this).attr('src', '');
                    jQuery(this).attr('src', imgSrc);
                });
            });
        }, 2000);
    }

    if( getCookie('comment_author_status_'+commentCookieHash) )
    {
        jQuery('#commentModerationConfirmation').trigger($.Events.OPEN);
        removeCookie('comment_author_status_'+commentCookieHash);
        removeCookie('comment_author_'+commentCookieHash);
        removeCookie('comment_author_email_'+commentCookieHash);
        removeCookie('comment_author_url_'+commentCookieHash);
    }

});


function getClassFor(acode)
{
    $.ajax({
        type:"POST",
        async:false,
        url:"/wp-content/plugins/wheels-compare/ajax-call.php",
        data:"request_acode=" + acode,
        dataType:'html',
        beforeSend:function () {
        },
        success:function (data) {

            if (sponsoredlList[data] == undefined) {
                sponsoredAcode = sponsoredlList['Sedan'];
            } else {
                sponsoredAcode = sponsoredlList[data];
            }

            if ($('body.compare h1').length > 0) {
                fillComparePage();
            }

            fillCompareCart();
        }
    });
}