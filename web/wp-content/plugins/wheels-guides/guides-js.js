function load_browse_guide_content(page)
{

    if (jQuery('.guides-taxonomy-list .selected').length > 0)
    {
        var term = jQuery('.guides-taxonomy-list a.selected').attr('rel');
    }else{
        var term = jQuery('.guides-taxonomy-list a.title:first').attr('rel');
    }
    jQuery('.guides-taxonomy-list .selected')
    jQuery.ajax
    ({
        url: WHEELS_GUIDE_AJAX_DATA,
        type: 'POST',
        dataType: 'json',
        data: 'term='+term+'&page='+page,
        success: function(json){
            jQuery('#guides-sub').html(json.data);
            jQuery('.browse-guides .pagination').html(json.pagination);
        },
        error: function(){
            //alert('Ajax error');
        }
    });
}

jQuery(document).ready(function(){

    jQuery('.guides-taxonomy-list .title').click(function(){
        jQuery('.guides-taxonomy-list .title').removeClass('selected');
        jQuery(this).addClass('selected');
        load_browse_guide_content( 1 );
        return false;
    });

    jQuery('.browse-guides .pagination a').live('click', function(){
        var page = jQuery(this).attr('href').split('=');
        var page_num = page[1];
        if ( typeof(page_num) == 'undefined') page_num = 1;
        load_browse_guide_content( page_num );
        return false;
    });

    jQuery('#guides .pill-menu:eq(0) li').click(function(){
        var index = jQuery('#guides .pill-menu li').index( jQuery(this) );

        jQuery('#guides .carousel').hide();
        jQuery('#guides .carousel:eq('+index+')').show();

        jQuery('#guides .pill-menu:eq(0) li').removeClass('on');
        jQuery(this).addClass('on');
        return false;
    });

    load_browse_guide_content( 1 );
});