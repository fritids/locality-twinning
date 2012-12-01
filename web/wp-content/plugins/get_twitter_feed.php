<script type="text/javascript" language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
	var make = 'toyota';
	var model = 'camry';
	var widgetHtml = true;
	
	$('#make_tags').empty();
	$('#make_tags').html(make);
	$('#model_tags').empty();
	$('#model_tags').html(model);
	
	make = <?php echo urlencode('#' + make); ?>;
	model = <?php echo urlencode('#' + model); ?>;
	
	jQuery.ajax( {
		data: 'q=' + make + '&rpp=8&callback=?',
		type: 'POST',
		url: 'http://search.twitter.com/search.json',
		dataType: 'jsonp',
		success: function(data_make) {
			for (key in data_make['results']) {
				var DateSegmentArray = data_make['results'][key]['created_at'].split(' ');
				var customFormatedDate = DateSegmentArray[1] + ' ' + DateSegmentArray[2];
				widgetHtml = '<p>' + data_make['results'][key]['text'] + '</p><strong class="date">' + customFormatedDate + '</strong><a href="http://twitter.com/intent/tweet?in_reply_to=' + data_make['results'][key]['id'] + '" class="reply">Reply</a>';
				$('#make_slide_wrap_' + key).empty();
				$('#make_slide_wrap_' + key).html(widgetHtml);
				widgetHtml = '';
			}
		}
	});
	
	jQuery.ajax( {
		data: 'q=' + model + '&rpp=8&callback=?',
		type: 'POST',
		url: 'http://search.twitter.com/search.json',
		dataType: 'jsonp',
		success: function(data_model) {
			for (key in data_model['results']) {
				var DateSegmentArray = data_model['results'][key]['created_at'].split(' ');
				var customFormatedDate = DateSegmentArray[1] + ' ' + DateSegmentArray[2];
				widgetHtml = widgetHtml + '<p>' + data_model['results'][key]['text'] + '</p><strong class="date">' + customFormatedDate + '</strong><a href="http://twitter.com/intent/tweet?in_reply_to=' + data_model['results'][key]['id'] + '" class="reply">Reply</a>';
				$('#model_slide_wrap_' + key).empty();
				$('#model_slide_wrap_' + key).html(widgetHtml);
				widgetHtml = '';
			}
		}
	});
});
</script>

<div data-controller="TabsController" class="social">
    <h3>The Word</h3>
    <div class="twitter-tags tab-nav">
        <ul class="clearfix">
            <li><a id="make_tags" href="#" class="tag">#Jetta</a></li>
            <li><a id="model_tags" href="#" class="tag">#VW</a></li>
        </ul>
        <a href="#" class="follow">Follow @VWCanada</a></div>
    <div class="tabs">
        <div data-controller="SlidesController" class="twitter-feed tab">
            <div class="tweet-navigation navigation"><a class="nav left">Left</a><a class="nav right">Right</a></div>
            <div class="tweets viewport">
                <div class="tweet-container container clearfix">
                    <div class="tweet slide">
                        <div id="make_slide_wrap_0" class="wrap">
                            Loading...
						</div>
                    </div>
                    <div class="tweet slide">
                        <div id="make_slide_wrap_1" class="wrap">
                            Loading...
						</div>
                    </div>
                    <div class="tweet slide">
                        <div id="make_slide_wrap_2" class="wrap">
                            Loading...
						</div>
                    </div>
                    <div class="tweet slide">
                        <div id="make_slide_wrap_3" class="wrap">
                            Loading...
						</div>
                    </div>
                    <div class="tweet slide">
                        <div id="make_slide_wrap_4" class="wrap">
                            Loading...
						</div>
                    </div>
                    <div class="tweet slide">
                        <div id="make_slide_wrap_5" class="wrap">
                            Loading...
						</div>
                    </div>
                    <div class="tweet slide">
                        <div id="make_slide_wrap_6" class="wrap">
                            Loading...
						</div>
                    </div>
                    <div class="tweet slide">
                        <div id="make_slide_wrap_7" class="wrap">
                            Loading...
						</div>
                    </div>
                </div>
            </div>
            <a href="#" class="sponsor">Sponsored</a>
		</div>
        <div data-controller="SlidesController" class="twitter-feed tab">
            <div class="tweet-navigation navigation"><a class="nav left">Left</a><a class="nav right">Right</a></div>
            <div class="tweets viewport">
                <div class="tweet-container container clearfix">
                    <div class="tweet slide">
                        <div id="model_slide_wrap_0" class="wrap">
                            Loading...
						</div>
                    </div>
                    <div class="tweet slide">
                        <div id="model_slide_wrap_1" class="wrap">
                            Loading...
						</div>
                    </div>
                    <div class="tweet slide">
                        <div id="model_slide_wrap_2" class="wrap">
                            Loading...
						</div>
                    </div>
                    <div class="tweet slide">
                        <div id="model_slide_wrap_3" class="wrap">
                            Loading...
						</div>
                    </div>
                    <div class="tweet slide">
                        <div id="model_slide_wrap_4" class="wrap">
                            Loading...
						</div>
                    </div>
                    <div class="tweet slide">
                        <div id="model_slide_wrap_5" class="wrap">
                            Loading...
						</div>
                    </div>
                    <div class="tweet slide">
                        <div id="model_slide_wrap_6" class="wrap">
                            Loading...
						</div>
                    </div>
                    <div class="tweet slide">
                        <div id="model_slide_wrap_7" class="wrap">
                            Loading...
						</div>
                    </div>
                </div>
            </div>
            <a href="#" class="sponsor">Sponsored</a>
		</div>
    </div>
</div>
