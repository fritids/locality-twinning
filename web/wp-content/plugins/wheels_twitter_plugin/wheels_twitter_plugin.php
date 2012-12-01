<?php
/*
Plugin Name: Twitter Custom Plugin
Plugin URI: http://emicrograph.com
Description: A widget that will display tweets from twitter.
Author: Ishtiak Mahmud
Version: 1.0
Author URI: http://emicrograph.com
*/

add_action('admin_menu','twitter_plugin_init_function');

function twitter_plugin_init_function() {
    add_options_page('Twitter Plugin','Twitter Plugin','manage_options','twitter-plugin','twitter_plugin_function');
}

function twitter_plugin_function() {
	add_option('user_name_1','','','no');
	add_option('user_name_2','','','no');
	add_option('user_name_3','','','no');
	add_option('sponsored_1','','','no');
	add_option('sponsored_2','','','no');
	add_option('sponsored_3','','','no');
	add_option('cache_timeline',30,'','no');
	add_option('feed_amount',4,'','no');
	
	if (isset($_POST['twitter_plugin_form_submit'])) {
		update_option('user_name_1',trim($_POST['user_name_1']));
		update_option('user_name_2',trim($_POST['user_name_2']));
		update_option('user_name_3',trim($_POST['user_name_3']));
		update_option('sponsored_1',$_POST['sponsored_1']);
		update_option('sponsored_2',$_POST['sponsored_2']);
		update_option('sponsored_3',$_POST['sponsored_3']);
		
		$cache_timeline = intval(trim($_POST['cache_timeline']));
		if (($cache_timeline != '') && is_int($cache_timeline)) {
			update_option('cache_timeline',$cache_timeline);
		}
		
		$feed_amount = intval(trim($_POST['feed_amount']));
		if (($feed_amount != '') && is_int($feed_amount)) {
			update_option('feed_amount',$feed_amount);
		}
	}
	?>
	<style>
		div.form_wrapper {
			width: auto;
		}
		
		#twitter_plugin_form {
			width: auto;
			border: 1px solid #DFDFDF;
		}
		
		#twitter_plugin_form td {
			padding: 20px;
		}
	</style>
	<div class="form_wrapper">
		<form action="" method="post">
			<h1>Twitter Plugin Options</h1>
			<table id="twitter_plugin_form">
				<tr>
					<td>User Name 1</td>
					<td><input type="text" class="regular-text" id="user_name_1" name="user_name_1" value="<?php echo get_option('user_name_1'); ?>" /></td>
					<td><input type="checkbox" id="sponsored_1" name="sponsored_1" value="1" <?php echo (get_option('sponsored_1') == 1) ? 'checked="checked"' : ''; ?> /> Sponsored</td>
				</tr>
				<tr>
					<td>User Name 2</td>
					<td><input type="text" class="regular-text" id="user_name_2" name="user_name_2" value="<?php echo get_option('user_name_2'); ?>" /></td>
					<td><input type="checkbox" id="sponsored_2" name="sponsored_2" value="1" <?php echo (get_option('sponsored_2') == 1) ? 'checked="checked"' : ''; ?> /> Sponsored</td>
				</tr>
				<tr>
					<td>User Name 3</td>
					<td><input type="text" class="regular-text" id="user_name_3" name="user_name_3" value="<?php echo get_option('user_name_3'); ?>" /></td>
					<td><input type="checkbox" id="sponsored_3" name="sponsored_3" value="1" <?php echo (get_option('sponsored_3') == 1) ? 'checked="checked"' : ''; ?> /> Sponsored</td>
				</tr>
				<tr>
					<td>Keep Cache for ( minute )</td>
					<td colspan="2"><input type="text" class="regular-text" id="cache_timeline" name="cache_timeline" value="<?php echo get_option('cache_timeline'); ?>" /></td>
				</tr>
				<tr>
					<td>Number of Feed</td>
					<td colspan="2"><input type="text" class="regular-text" id="feed_amount" name="feed_amount" value="<?php echo get_option('feed_amount'); ?>" /></td>
				</tr>
				<tr>
					<td colspan="3" style="text-align:center;"><input type="submit" class="button-primary" id="twitter_plugin_form_submit" name="twitter_plugin_form_submit" value="Save Changes" /></td>
				</tr>
			</table>
		</form>
	</div>
	<?php
}
?>