function fillComparePage() {

   var compareStr = getLocalstorageItem('compare');

        var count = 0;
        var compare = JSON.parse(compareStr);
        var strAcode = compare.vehicles.join("|") + "|" + sponsoredAcode;
        count = compare.vehicles.length + 1;

        //retriving vehicle info
        $.ajax({
            type:"POST",
            async:false,
            url:"/wp-content/themes/wheels/archive-compare-sub.php",
            data:"compare_acode=" + strAcode,
            dataType:'html',
            beforeSend:function () {
                $("#loading-div").html('<img src="/wp-content/themes/wheels/img/ajax-loader.gif" class="ajax-loader" alt="Loading">');
            },
            success:function (data) {

                var cls = $(".compare-container").attr("class").split(" ");
                $(".compare-container").removeClass(cls[1] + " clearfix");
                if (count < 3) {
                    $(".compare-filters").attr("display", "block");
                } else {
                    $(".compare-filters").attr("display", "none");
                }
                var clear_line = '';
                if (count == 0) {
                    count = 1;
                    clear_line = 'clear-compare-line';
                }
                $(".compare-container").addClass("compare-container compare" + count + " clearfix");
                $(".compare-container").attr("id",clear_line);
                $("#loading-div").html('');
                $("#archive-compare-sub").empty();
                $("#archive-compare-sub").html(data);
                jQuery(".compare-trim").combobox();
                jQuery(".compare-container").CompareController();
                return false;
            }
        });
    //}
}

// Update model, change model depend makeCode
var compareUpdateModel = function (modeSelectId, makeCode, selectedValue) {
    // Re-build select box
    if (typeof(selectedValue) == 'undefined') selectedValue = '';
    var $select = $('.model-container select.compare-selector')
        .parent()
        .html('<select id="' + modeSelectId + '" data-role="none" name="model" data-controller="ComboboxController" class="compare-selector filter-selector ui-menu-model ui-light">')
        .find("select");

    // push model which matched makeCode
    if (makeCode == 'All') {
        $select.append($("<option />"));
    } else {
        $select.append($("<option class='Model' value='Model'>Model</option>"));
        $.each(modelList, function () {
            if (this.makeCode == makeCode) {
                if (selectedValue == this.modelName) {
                    $select.append($('<option selected="selected" />').attr("value", this.modelName).text(this.modelName));
                } else {
                    $select.append($("<option />").attr("value", this.modelName).text(this.modelName));
                }
            }
        });
    }

// Reinitialize combobox
    $select.combobox();

}

function discardCompareItem(acode) {

    var compareStr = getLocalstorageItem('compare');
    var compare = JSON.parse(compareStr);


    for (i = 0; compare.vehicles.length > i; i++) {
        if (compare.vehicles[i] == acode) {
            compare.vehicles.splice(i, 1);
        }
    }
    compareStr = JSON.stringify(compare);
    setLocalstorageItem('compare', compareStr);
    fillComparePage();
    getCompareCount();
    fillCompareCart();
    jQuery(".compare-container").CompareController();
    //-----------------------
    onLoadSuggestion();
    //-----------------------
}

function replace_acode(old_acode, new_acode) {
    var compareStr = getLocalstorageItem('compare');

    if (typeof(compareStr) != 'undefined') {
        var compare = JSON.parse(compareStr);
        for (var i = 0; i < compare.vehicles.length; i++) {
            if (compare.vehicles[i] == old_acode)
                compare.vehicles.splice(i, 1, new_acode);
        }
        var compareStr = JSON.stringify(compare);

        setLocalstorageItem('compare', compareStr);
        fillComparePage();
        getCompareCount();
        jQuery(".compare-container").CompareController();
    }
}


jQuery(document).ready(function () {

    if(sponsoredAcode == undefined)
    {
        var compareStr = getLocalstorageItem('compare');
        var compare = JSON.parse(compareStr);

        if(compare.vehicles.length > 0){
            getClassFor(compare.vehicles[0], true);
        } else {
            sponsoredAcode = sponsoredlList['Sedan'];
            fillComparePage();
            fillCompareCart();
        }
    }

    //-----------------------
    onLoadSuggestion();
    //-----------------------

    if ($('body.compare') == undefined) {
        return;
    }

    jQuery('#compare-filter-make').bind('comboboxselected', function (event, ui) {
        compareUpdateModel('compare-filter-model', ui.item.className);
    });

    //this is add button action in the compare page
    $("#add_to_compare").click(function () {
        var make = $("#compare-filter-make").val();
        var model = $("#compare-filter-model").val();
        var year = $("#compare-year").val();

        if ((make == 'Make' || make == '') || (model == 'undefined' || model == '') || (year == 'Year' || year == '')) {
            $("#galert").trigger($.Events.OPEN);
            $("#galert #galert-title").html("You must select a Make, Model and Year for the vehicle.");
            return false;
        }

        //retriving vehicle info
        $.ajax({
            type:"POST",
            async:true,
            cache:false,
            url:"/wp-content/plugins/wheels-compare/ajax-call.php",
            data:"make=" + make + "&model=" + model + "&year=" + year,
            dataType:'json',
            beforeSend:function () {
                $("#loading-div").html('<img src="/wp-content/themes/wheels/img/ajax-loader.gif" class="ajax-loader" alt="Loading">');
            },
            success:function (data) {


                $("#loading-div").empty();
                $("#archive-compare-sub").html('');
                $(".compare-container .suggestions-container #title-suggestion").html('');
                $(".compare-container .suggestions-container #suggestion-list-panel").html('');

                if (data.length) {

                    if (data[0].acode != null) {

                        var isSponsored = false;
                        $.each(sponsoredlList, function(index, value) {
                            if (data[0].acode == value) {
                                isSponsored = true;
                                return false;
                            }
                        });

                        if (isSponsored) {
                            $("#galert").trigger($.Events.OPEN);
                            $("#galert #galert-title").html("Vehicle already exists as default sponsored item.");
                            return false;
                        }

                        //adding first acode to the compare
                        if (!acodeExists(data[1].acode)) {
                            addToCompare(data[0].acode, 3);
                            if (sponsoredlList[data[0].class] == undefined) {
                                sponsoredAcode = sponsoredlList['Sedan'];
                            } else {
                                sponsoredAcode = sponsoredlList[data[0].class];
                            }
                            $("#galert").trigger($.Events.OPEN);
                            $("#galert #galert-title").html("Vehicle added to compare.");
                        }
                    }
                    else {
                        $("#galert").trigger($.Events.OPEN);
                        $("#galert #galert-title").html("No vehicle found to compare.");
                    }

                    //filling the suggestion pannel
                    //keeping rest acodes for suggestion
                    $("#suggestion-list-panel").empty();
                    if (data.length > 2) {
                        var x = '<ul id="suggestion-list">';
                        for (var i = 2; i < data.length; i++) {

                            if (data[i].acode != null) {
                                var liclass = (i % 2) ? 'even' : 'odd';
                                x += '<li class="' + liclass + '"><div class="pos"><a href="/vehicles/'+data[i].acode+'" class="suggestion-image"><img src="' + data[i].image_link + '" alt="' + data[i].profile_title + '"/><p class="suggestion-title">' + data[i].profile_title + '</p><a data-id="" href="javascript:void(0)" rel="' + data[i].acode + '" class="compare callout">Compare<img alt="Compare this vehicle" src="/wp-content/themes/wheels/img/compare-callout.png"/></a></a></div></li>';
                            }
                        }
                        x += '</ul>';
                        $(".compare-container .suggestions-container #title-suggestion").html('Suggestions');
                        $(".compare-container .suggestions-container #suggestion-list-panel").append(x);

                    }

                }

                fillComparePage();
                getCompareCount();
                fillCompareCart();

            }
        });

        return false;
    });

    jQuery(".compare-trim").live('comboboxselected', function (event, ui) {
        var acodes = ui.item.value;
        var arrAcode = acodes.split("|");
        replace_acode(arrAcode[0], arrAcode[1]);
    });

    jQuery(".compare-container").CompareController();
});

function onLoadSuggestion(){
    var compareStr = getLocalstorageItem('compare');
    var compare = JSON.parse(compareStr);
    if(compare.vehicles.length > 0){
        var acode = compare.vehicles[compare.vehicles.length-1];
        //--------------
        $.ajax({
            type:"POST",
            async:false,
            url:"/wp-content/plugins/wheels-compare/ajax-call.php",
            data:"suggestions_for_acode=" + acode,
            dataType:'json',
            beforeSend:function () {
                $("#loading-div").html('<img src="/wp-content/themes/wheels/img/ajax-loader.gif" class="ajax-loader" alt="Loading">');
            },
            success:function (data) {
                onLoadSuggestionPanelWork(data.make,data.model,data.year);
            }
        });
        //--------------
    }
}

function onLoadSuggestionPanelWork(make,model,year)
{
    $.ajax({
        type:"POST",
        async:true,
        cache:false,
        url:"/wp-content/plugins/wheels-compare/ajax-call.php",
        data:"make=" + make + "&model=" + model + "&year=" + year,
        dataType:'json',
        beforeSend:function () {
            $("#loading-div").html('<img src="/wp-content/themes/wheels/img/ajax-loader.gif" class="ajax-loader" alt="Loading">');
        },
        success:function (data) {

            $("#loading-div").empty();
            $(".compare-container .suggestions-container #title-suggestion").html('');
            $(".compare-container .suggestions-container #suggestion-list-panel").html('');

            if (data.length) {

                //filling the suggestion pannel
                //keeping rest acodes for suggestion
                $("#suggestion-list-panel").empty();
                if (data.length > 2) {
                    var x = '<ul id="suggestion-list">';
                    for (var i = 2; i < data.length; i++) {

                        if (data[i].acode != null) {
                            var liclass = (i % 2) ? 'even' : 'odd';
                            x += '<li class="' + liclass + '"><div class="pos"><a href="/vehicles/'+data[i].acode+'" class="suggestion-image"><img src="' + data[i].image_link + '" alt="' + data[i].profile_title + '"/><p class="suggestion-title">' + data[i].profile_title + '</p><a data-id="" href="javascript:void(0)" rel="' + data[i].acode + '" class="compare callout">Compare<img alt="Compare this vehicle" src="/wp-content/themes/wheels/img/compare-callout.png"/></a></a></div></li>';
                        }
                    }
                    x += '</ul>';
                    $(".compare-container .suggestions-container #title-suggestion").html('Suggestions');
                    $(".compare-container .suggestions-container #suggestion-list-panel").append(x);

                }

            }
        }
    });
}