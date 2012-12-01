var per_search = 10;
var is_searching = false;

function show_loading(){
    jQuery('#vehicle-finder .results:eq(0)').css('opacity', .5).prepend('<img id="loading-icon" src="/wp-content/themes/wheels/img/ajax-loader.gif" style="position:absolute; left:600px; top:250px; width: 100px; z-index:9999">');
}

function hide_loading(){
    jQuery('#vehicle-finder .results:eq(0)').css('opacity', 1);
    jQuery('#loading-icon').remove();
}

function setSearchParam(){

    var make = ( jQuery('#finder-filter-make').val() == 'none' ) ? 'All' : jQuery('#finder-filter-make').val();
    var model = ( jQuery('#finder-filter-model').val() == 'none' ) ? 'All' : jQuery('#finder-filter-model').val();
    var trim = ( jQuery('#finder-filter-trim').val() == 'none' ) ? 'All' : jQuery('#finder-filter-trim').val();
    var text = '';
    text += ", Make - '<span>"+ make + "</span>'";
    text += ", Model - '<span>"+ model + "</span>'";
    text += ", Trim - '<span>"+ trim + "</span>'";
    jQuery('.results-bar .results-count .summary').html(text);
}

function doSearch() {

    if(is_searching) return false;
    is_searching = true;
    setSearchParam();
    $('#start').val('0');
    var form_data = jQuery('#vehicle-filters').serialize();
    //console.log( jQuery('#vehicle-filters').serializeArray() ); return false;
    $.ajax({
        data: form_data,
        url: '/search-vehicle-finder.php',
        type: 'post',
        dataType:'json',
        beforeSend:function(){
            show_loading();
        },
        success: function(json){
            is_searching = false;
            hide_loading();

            var resultCount = (json.total > 1) ? json.total+' results' : json.total + ' result';
            jQuery('.results-bar .results-count .count').html('<strong>Your Search:&nbsp;</strong>'+resultCount);

            jQuery('#vehicle-finder .search-result').empty();
            jQuery('#vehicle-finder .search-result').append(json.result);

            var isMore = json.total - ( parseInt(jQuery('#start').val()) + per_search );

            if( isMore > 0 ){
                jQuery('#vehicle-finder .search-result').append('<div class="load-more"><a href="#" onclick="JavaScript:loadMore(); return false;"><strong>Load more...</strong>&nbsp;' + isMore +' more results </a></div>');
                $('#start').val(per_search);
            }
            //jQuery('.trim-selector').unbindAll();
            jQuery('.trim-selector').change(function(){
                window.location.href = '/vehicles/'+jQuery(this).val();
            });
        },
        error: function(){
            is_searching = false;
        }
    });
}

function loadMore() {
    if(is_searching) return false;
    is_searching = true;
    setSearchParam();
    var form_data = jQuery('#vehicle-filters').serialize();
    //console.log( jQuery('#vehicle-filters').serializeArray() ); return false;
    $.ajax({
        data: form_data,
        url: '/search-vehicle-finder.php',
        type: 'post',
        dataType:'json',
        beforeSend:function(){
            show_loading();
        },
        success: function(json){
            is_searching = false;
            hide_loading();

            $('.load-more').remove();

            var resultCount = (json.total > 1) ? json.total+' results' : json.total + ' result';
            jQuery('.results-bar .results-count .count').html('<strong>Your Search:&nbsp;</strong>'+resultCount);

            jQuery('#vehicle-finder .search-result').append(json.result);

            var isMore = json.total - ( parseInt(jQuery('#start').val()) + per_search );

            if( isMore > 0 ){
                jQuery('#vehicle-finder .search-result').append('<div class="load-more"><a href="#" onclick="JavaScript:loadMore(); return false;"><strong>Load more...</strong>&nbsp;' + isMore +' more results </a></div>');
            }

            $('#start').val( parseInt(jQuery('#start').val()) + per_search );
        },
        error: function(){
            is_searching = false;
        }
    });
    return false;
}

function updateTrim(){

    var make = jQuery('#finder-filter-make').val();
    var model = jQuery('#finder-filter-model').val();
    if( model == 'none' ) return false;
    jQuery.ajax({
        url: '/vehicle-finder',
        type:'POST',
        data: "find-trim=true&&make="+make+"&&model="+model,
        dataType: 'json',
        beforeSend: function()
        {
            jQuery('#vehicle-filters .trim-container').prepend('<img id="trim-loader" width="22" src="/wp-content/themes/wheels/img/ajax-loader.gif" style="position: absolute; right: -21px;">');
        },
        success:function(json)
        {
            jQuery('#trim-loader').remove();
            //finder-filter-trim
            var $select = jQuery('#finder-filter-trim')
                .parent()
                .html('<select id="finder-filter-trim" data-role="none" name="trim" data-controller="ComboboxController" class="filter-selector ui-menu-trim ui-light">')
                .find("select");
            if(json.length)
            {
                $select.append( jQuery("<option />").attr("value", 'none').text('All Trims') );
                // push model which matched makeCode
                jQuery.each(json, function (i) {
                    $select.append( jQuery("<option />").attr("value", json[i]).text(json[i]) );
                });

                // Reinitialize combobox
                $select.combobox();
                jQuery('#finder-filter-trim').bind('comboboxselected', function(event, ui){
                    doSearch();
                });
            }else{
                $select.append( jQuery("<option />").attr("value", '').text('') );
                $select.combobox();
            }
            setSearchParam();
        },
        error:function()
        {
            jQuery('#trim-loader').remove();
        }
    });
}

jQuery(document).ready(function () {

    jQuery('#finder-filter-make').bind('comboboxselected', function(event, ui){

        jQuery('.filters .mrec-ad iframe, #topads iframe').each(function(){
            var url = jQuery(this).attr('src');
            url = url + '&make='+ui.item.value;
            jQuery(this).attr('src', url);
        });

        updateModel( 'finder-filter-model', ui.item.className, '', 'trim' );
        updateTrim();
    });

    jQuery('#vehicle-filters #year-slider').bind('slidestop slidecreate', function (event, ui) {
        if(event.type == 'slidecreate')
        {
            var values = jQuery(this).slider('values');
            jQuery('.yearRange:first').val(values[0]);
            jQuery('.yearRange:last').val(values[1]);
        }else{
            jQuery('.yearRange:first').val(ui.values[0]);
            jQuery('.yearRange:last').val(ui.values[1]);

            doSearch();
        }
    });

    jQuery('#vehicle-filters #efficiency-slider').bind('slidestop slidecreate', function (event, ui) {
        if(event.type == 'slidecreate')
        {
            var value = jQuery(this).slider('value', 5.0);
            jQuery('.efficiencyRange:first').val(5.0);
            jQuery('.efficiencyRange:last').val(50);
        }else{
            jQuery('.efficiencyRange:first').val(ui.value);
            jQuery('.efficiencyRange:last').val(50);

            doSearch();
        }
    });

    jQuery('#vehicle-filters #price-slider').bind('slidestop slidecreate', function (event, ui) {
        if(event.type == 'slidecreate')
        {
            var values = jQuery(this).slider('values');

            values[0] = 1000;
            values[1] = 400000;
            if(typeof(PRICE_END) != 'undefined')
            {
                values[1] = PRICE_END
            }

            jQuery('.priceRange:first').val(values[0]);
            jQuery('.priceRange:last').val(values[1]);
            jQuery(this).slider('values', [values[0], values[1]]);
        }else{
            jQuery('.priceRange:first').val(ui.values[0]);
            jQuery('.priceRange:last').val(ui.values[1]);

            doSearch();
        }
    });

    jQuery('#research-vehicles #price-slider').bind('slidestop slidecreate', function (event, ui) {
        if(event.type == 'slidecreate')
        {
            var value = 400000;
            if(typeof(PRICE_END) != 'undefined')
            {
                value = PRICE_END
            }
            jQuery(this).slider('value', value);
            jQuery('.priceRangeTop:first').val(1000);
            jQuery('.priceRangeTop:last').val(value);
        }else{
            jQuery('.priceRangeTop:first').val(1000);
            jQuery('.priceRangeTop:last').val(ui.value);
        }
    });

    jQuery('#vehicle-filters input[type="checkbox"]').live('click', function(e){
        e.stopPropagation();
        doSearch();
    });

    jQuery('#vehicle-filters .filter-selector').bind('comboboxselected', function(event, ui){
        doSearch();
    });

    jQuery('#vehicle-filters #km-slider').bind('slidestop slidecreate', function (event, ui) {
        if(event.type == 'slidecreate')
        {
            var value = jQuery(this).slider('value');
            jQuery('.kmRange:first').val(0);
            jQuery('.kmRange:last').val(value);
        }else{
            jQuery('.kmRange:first').val(0);
            jQuery('.kmRange:last').val(ui.value);

            doSearch();
        }
    });

    jQuery('#vehicle-filters #torque-slider').bind('slidestop slidecreate', function (event, ui) {
        if(event.type == 'slidecreate')
        {
            var value = jQuery(this).slider('value');
            jQuery('.torqueRange:first').val(0);
            jQuery('.torqueRange:last').val(value);
        }else{
            jQuery('.torqueRange:first').val(0);
            jQuery('.torqueRange:last').val(ui.value);

            doSearch();
        }
    });

    jQuery('#vehicle-filters #hp-slider').bind('slidestop slidecreate', function (event, ui) {
        if(event.type == 'slidecreate')
        {
            var value = jQuery(this).slider('value');
            jQuery('.horsepowerRange:first').val(0);
            jQuery('.horsepowerRange:last').val(value);
        }else{
            jQuery('.horsepowerRange:first').val(0);
            jQuery('.horsepowerRange:last').val(ui.value);

            doSearch();
        }
    });

    jQuery('#sort-filter').bind('comboboxselected', function(event, ui){
        jQuery('#orderby').val(ui.item.value);
        doSearch();
    });

    if(typeof(MAKE_SELECTED) != 'undefined' && MAKE_SELECTED != 'none')
    {

        if(MODEL_SELECTED != 'undefined'){
           var modelName = MODEL_SELECTED;
        }else{
            var modelName = 'all';
        }

        var selectedOption = jQuery('#filter-make').find(':selected');

        if(typeof(selectedOption) != 'undefined' || selectedOption2 != 'none'){

            setTimeout(function(){
                updateModel('filter-model', selectedOption.attr('class'), modelName);
            }, 500);

        }

        var selectedOption2 = jQuery('#finder-filter-make').find(':selected');

        if(typeof(selectedOption2) != 'undefined' || selectedOption2 != 'none' ){
            setTimeout(function(){

                updateModel('finder-filter-model', selectedOption2.attr('class'), modelName);

                setTimeout(function(){
                    updateTrim();
                }, 500);

            }, 500);
        }

    }

    if(typeof(VEHICLE_FINDER_LOADED) != 'undefined')
    {
        jQuery('#orderby').val('year desc');
        setTimeout('doSearch()', 1000);
    }

});