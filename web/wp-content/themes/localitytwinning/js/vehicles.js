function MatchClass(text) {
    this.text = text;
    this.regExp = /http:\/\/[^ ]+/g;
    this.links = new Array;
}

MatchClass.prototype.getTweet = function() {
    if (this.regExp.test(this.text)) {
        this.links = this.text.match(this.regExp);
        this.tweet = this.addLink(this.text,this.links);
    } else {
        this.tweet = this.text;
    }
    return this.tweet;
}

MatchClass.prototype.addLink = function(text,links) {
    for(x in links) {
        text = text.replace(links[x],'<a target="_blank" href="' + links[x] + '">' + links[x] + '</a>');
    }
    return text;
}

function loadTwitterWidget(MAKE,MODEL,SHOW_MAKE_MODEL)
{
    var widgetHtml = true;
    var matchClassObject = true;
    var tweet = true;
    var vehicle = new Array();

    if(SHOW_MAKE_MODEL == true)
    {
        vehicle[0] = MODEL;
    }else{
        vehicle[0] = MAKE +' '+MODEL;
    }
    vehicle[1] = MAKE;

    $('#make_tags').empty();
    $('#make_tags').html(vehicle[0]);
    $('#model_tags').empty();
    $('#model_tags').html(vehicle[1]);

    $.each(vehicle,function(index,value) {
        $.ajax( {
            data: 'q=' + value + '&rpp=8&callback=?',
            type: 'POST',
            url: 'http://search.twitter.com/search.json',
            dataType: 'jsonp',
            success: function(data_vehicle) {
                for (key in data_vehicle['results']) {
                    var dateSegmentArray = data_vehicle['results'][key]['created_at'].split(' ');
                    var customFormatedDate = dateSegmentArray[1] + ' ' + dateSegmentArray[2];
                    matchClassObject = new MatchClass(data_vehicle['results'][key]['text']);
                    tweet = matchClassObject.getTweet();
                    widgetHtml = '<p>' + tweet + '</p><strong class="date">' + customFormatedDate + '</strong><a href="http://twitter.com/intent/tweet?in_reply_to=' + data_vehicle['results'][key]['id'] + '" class="reply">Reply</a>';
                    if (index == 0) {
                        $('#make_slide_wrap_' + key).empty();
                        $('#make_slide_wrap_' + key).html(widgetHtml);
                    }
                    if (index == 1) {
                        $('#model_slide_wrap_' + key).empty();
                        $('#model_slide_wrap_' + key).html(widgetHtml);
                    }
                    widgetHtml = '';
                }
            }
        });
    });
}

jQuery(document).ready(function(){

    jQuery( ".vehicle-profile-container .vehicle-profile" ).bind( "comboboxselected", function(event, ui) {
        var URL = '/vehicles/'+ui.item.value;
        document.location.href = ui.item.value;
    });

    if(typeof(LOAD_VEHICLE_PAGE_USED_CAR) != 'undefined')
    {
        loadUsedCar(VEHICLE_MAKE3, VEHICLE_MODEL3, VEHICLE_YEAR3, VEHICLE_CLASS3, 'style_1', 4);
    }

    // Execute only vehicle landing page
    // Flip latest/popular vehicle carousel
    jQuery('#vehicles .order-by-list li').click(function(){
        var index = jQuery('#vehicles .order-by-list li').index( jQuery(this) );

        jQuery('#vehicles .carousel').hide();
        jQuery('#vehicles .carousel:eq('+index+')').show();

        jQuery('#vehicles .order-by-list li').removeClass('on');
        jQuery(this).addClass('on');
        return false;
    });


});