function load_browse_vehicles_content(page)
{

    if (jQuery('.review-browse-container .vehicle-navigation .tab-nav a.active').length > 0)
    {
        var taxonomy = jQuery('.review-browse-container .vehicle-navigation .tab-nav a.active').text().toLowerCase();
    }else
    {
        var taxonomy = jQuery('.review-browse-container .vehicle-navigation .tab-nav a:first').text().toLowerCase();
    }

    if (jQuery('.review-browse-container .vehicle-navigation .'+taxonomy+'-list a.selected').length > 0)
    {
        var term = jQuery('.review-browse-container .vehicle-navigation .'+taxonomy+'-list a.selected').attr('rel');
    }else
    {
        var term = jQuery('.review-browse-container .vehicle-navigation .'+taxonomy+'-list a.title:first').attr('rel');
    }

    var term2 = jQuery('.review-browse-container .browse-vehicles .compare-selector').val();


    jQuery.ajax
    ({
        url: WHEELS_VEHICLE_REVIEW_AJAX_DATA,
        type: 'post',
        dataType: 'json',
        data: 'taxonomy='+taxonomy+'&term='+term+'&term2='+term2+'&page='+page,
        beforeSend:function(){
            start_vehicle_browse_loading();
        },
        success: function(json){
            jQuery('.review-browse-container .browse-vehicles .vehicle-listing .listing').html(json.data);
            jQuery('.review-browse-container .browse-vehicles .header .pagination').html(json.pagination);
            stop_vehicle_browse_loading();
        },
        error: function(){
            stop_vehicle_browse_loading();
        }
    });
}

function start_vehicle_browse_loading()
{
    jQuery('.review-browse-container .vehicle-listing').prepend('<div id="vehicle_browse_loading"></div>');
    var height = 50;
    jQuery('.review-browse-container .vehicle-listing ul li:even').each(function(){
        height += jQuery(this).height();
    });
    if(height < 100) height = 100;

    jQuery('#vehicle_browse_loading').width(708).height(height).css({
        'background-color': '#FFFFFF',
        'background-image': 'url(/wp-content/themes/wheels/img/ajax-loader.gif)',
        'background-position': 'center center',
        'background-repeat': 'no-repeat',
        'opacity': 0.5,
        'position': 'absolute',
        'right': 0,
        'z-index': 8650
    });

}

function stop_vehicle_browse_loading()
{
    jQuery('#vehicle_browse_loading').remove();
}

jQuery(document).ready(function(){
    jQuery('.review-browse-container .vehicle-navigation .tab .slide .title').click(function(){

        jQuery('.vehicle-navigation .tab .slide .title').removeClass('selected');
        jQuery(this).addClass('selected');
        load_browse_vehicles_content( 1 );
        return false;
    });

    $( ".browse-vehicles .compare-selector" ).bind( "comboboxselected", function(event, ui) {
        load_browse_vehicles_content( 1 );
    });

    jQuery('.browse-vehicles .pagination a').live('click', function(){
        var page = jQuery(this).attr('href').split('=');
        var page_num = page[1];
        if ( typeof(page_num) == 'undefined') page_num = 1;
        load_browse_vehicles_content( page_num );
        return false;
    });

    load_browse_vehicles_content( 1 );

});
