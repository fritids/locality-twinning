<?php
require_once('../../../wp-load.php');

function get_twitter_feed($username,$cache_timeline,$feed_amount) {
	$cache_file_name = 'twitter_cache_'.$username.'.xml';
	$cache_timeline = $cache_timeline * 60;
	
	if (file_exists($cache_file_name)) {
		$cache_file_last_modified = filemtime($cache_file_name);
		if ((time() - $cache_file_last_modified) > $cache_timeline) {
			$feed_xml = @file_get_contents('http://twitter.com/statuses/user_timeline/'.$username.'.rss');
			$cache_file = fopen($cache_file_name,'wb');
			fwrite($cache_file,serialize($feed_xml));
			fclose($cache_file);
		}
	} else {
		$feed_xml = @file_get_contents('http://twitter.com/statuses/user_timeline/'.$username.'.rss');
        $cache_file = fopen($cache_file_name,'wb');
        fwrite($cache_file,serialize($feed_xml));
        fclose($cache_file);
	}
	$xml = simplexml_load_string(@unserialize(file_get_contents($cache_file_name)));
	$feed = '';
	for ($i = 0; $i < $feed_amount; $i++) {
		if (strlen($xml->channel->item[$i]->description) > 0) {
			$feed = $feed.'<div class="tweet slide">
				<div class="wrap">
					<p>'.$xml->channel->item[$i]->description.'</p>
					<strong class="date">'.date('d M',strtotime($xml->channel->item[$i]->pubDate)).'</strong>
					<a href="http://twitter.com/intent/tweet?in_reply_to='.$username.'" class="reply">Reply</a>
				</div>
			</div>';
		}
	}
	return $feed;
}
?>

<div data-controller="TabsController" class="social">
    <h3>The Word</h3>
    <div class="twitter-tags tab-nav">
        <ul class="clearfix">
            <?php
			if (get_option('user_name_1') != '') {
				echo '<li><a href="#" class="tag selected">'.get_option('user_name_1').'</a></li>';
			}
			if (get_option('user_name_2') != '') {
				echo '<li><a href="#" class="tag selected">'.get_option('user_name_2').'</a></li>';
			}
			if (get_option('user_name_3') != '') {
				echo '<li><a href="#" class="tag selected">'.get_option('user_name_3').'</a></li>';
			}
			?>
        </ul>
        <!--<a href="#" class="follow">Follow @VWCanada</a>-->
	</div>
    <div class="tabs">
        <?php if (get_option('user_name_1') != '') { ?>
		<div data-controller="SlidesController" class="twitter-feed tab">
            <div class="tweet-navigation navigation"><a class="nav left">Left</a><a class="nav right">Right</a></div>
            <div class="tweets viewport">
                <div class="tweet-container container clearfix">
                    <?php echo get_twitter_feed(get_option('user_name_1'),get_option('cache_timeline'),get_option('feed_amount')); ?>
                </div>
            </div>
            <?php if (get_option('sponsored_1') == 1) { ?>
			<a href="#" class="sponsor">Sponsored</a>
			<?php } ?>
		</div>
		<?php } ?>
		<?php if (get_option('user_name_2') != '') { ?>
        <div data-controller="SlidesController" class="twitter-feed tab">
            <div class="tweet-navigation navigation"><a class="nav left">Left</a><a class="nav right">Right</a></div>
            <div class="tweets viewport">
                <div class="tweet-container container clearfix">
                    <?php echo get_twitter_feed(get_option('user_name_2'),get_option('cache_timeline'),get_option('feed_amount')); ?>
                </div>
            </div>
            <?php if (get_option('sponsored_1') == 1) { ?>
			<a href="#" class="sponsor">Sponsored</a>
			<?php } ?>
		</div>
		<?php } ?>
		<?php if (get_option('user_name_3') != '') { ?>
        <div data-controller="SlidesController" class="twitter-feed tab">
            <div class="tweet-navigation navigation"><a class="nav left">Left</a><a class="nav right">Right</a></div>
            <div class="tweets viewport">
                <div class="tweet-container container clearfix">
                    <?php echo get_twitter_feed(get_option('user_name_3'),get_option('cache_timeline'),get_option('feed_amount')); ?>
                </div>
            </div>
            <?php if (get_option('sponsored_1') == 1) { ?>
			<a href="#" class="sponsor">Sponsored</a>
			<?php } ?>
		</div>
		<?php } ?>
    </div>
</div>