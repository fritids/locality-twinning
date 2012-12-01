function selectUsedCarTab()
{
    jQuery('div.vehicle-finder div.tab-nav ul li').removeClass('on');
    jQuery('div.vehicle-finder div.tab-nav ul li a').removeClass('active');

    if(window.location.hash && window.location.hash == '#newcar') {
        jQuery('div.vehicle-finder div.tab-nav ul li:eq(0)').addClass('on');
        jQuery('div.vehicle-finder div.tab-nav ul li a:eq(0)').addClass('active');
        jQuery('#used').hide();
        jQuery('#new').show();
        jQuery('#home .vehicle-finder .sponsor').show();
    } else{
        jQuery('div.vehicle-finder div.tab-nav ul li:eq(1)').addClass('on');
        jQuery('div.vehicle-finder div.tab-nav ul li a:eq(1)').addClass('active');
        jQuery('#new').hide();
        jQuery('#used').show();
        jQuery('#home .vehicle-finder .sponsor').hide();
    }

    jQuery('.vehicle-finder .tab-nav ul li').click(function(){
        var index = jQuery('.vehicle-finder .tab-nav ul li').index( jQuery(this) );
        if( index ){
            jQuery('#home .vehicle-finder .sponsor').hide();
        }else{
            jQuery('#home .vehicle-finder .sponsor').show();
        }
    });

    jQuery('.home-carousel .pill-menu li').click(function(){
        var index = jQuery('.home-carousel .pill-menu li').index( jQuery(this) );

        jQuery('.home-carousel .carousel').hide();
        jQuery('.home-carousel .carousel:eq('+index+')').show();

        jQuery('.home-carousel .pill-menu li').removeClass('on');
        jQuery(this).addClass('on');
        return false;
    });

    jQuery('#home-filter-make').bind('comboboxselected', function(event, ui){
        updateModel( 'home-filter-model', ui.item.className );
    });

    setTimeout(function(){
        jQuery('#used .used-vehicles').html('<iframe height="225" scrolling="no" src="http://vehicles.wheels.ca" name="used-vehicle-iframe" frameborder="0">You need a Frames Capable browser to view this content.</iframe>');
    }, 4000);
}