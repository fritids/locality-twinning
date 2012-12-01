<?php
/**
Plugin Name: The Grid
Plugin URI: http://emicrograph.com
Description: The Grid would like to give their users the ability to navigate through content from a map interface. <a href="options-general.php?page=grid-settings-page">Plugin Settings</a>
Author: Mohammad Rakibul islam & Md. Asraful Islam
Version: 1.0
Author URI: http://emicrograph.com
License: GPLv2 or later
*/
?>
<?php

add_action('wp_head', 'add_the_grid_script');
add_action('admin_enqueue_scripts', 'add_the_grid_script');
function add_the_grid_script(){
?>
    <script type="text/javascript">var BASE_URL = '<?php bloginfo('home'); ?>'</script>
    <script type="text/javascript">var SITE_TITLE = '<?php echo get_bloginfo( 'name' ); ?>'</script>
    <script type="text/javascript">var SITE_URL = '<?php echo get_bloginfo( 'home' ); ?>'</script>
    <script type="text/javascript">var GRID_MAP_TWEET_POST_ID = '<?php echo $_GET['gid'] ?>'</script>
    <script type="text/javascript">var GRID_MAP_CURRENT_URL = '<?php the_permalink(); echo ( isset( $_SERVER['QUERY_STRING'] ) ? '&' : '?' ); ?>'</script>
<?php
}
define('THE_GRID_PLUGIN_URL', plugin_dir_url( __FILE__ ));

$current_url = $_SERVER['PHP_SELF'];
$arr_current_url = explode("/", $current_url);

if(in_array("post.php", $arr_current_url) || in_array("post-new.php", $arr_current_url)){
    //backend
    wp_register_style('jquery.ui.all.css', THE_GRID_PLUGIN_URL . 'assets/css/jquery.ui.all.css', array(), '');
    wp_enqueue_style('jquery.ui.all.css');
    wp_register_style('jquery.ui.datepicker.css', THE_GRID_PLUGIN_URL . 'assets/css/jquery.ui.datepicker.css', array(), '');
    wp_enqueue_style('jquery.ui.datepicker.css');
    wp_register_style('timepicker.css', THE_GRID_PLUGIN_URL . 'assets/css/timepicker.css', array(), '');
    wp_enqueue_style('timepicker.css');

    wp_enqueue_script('google-map-api-v3', 'http://maps.googleapis.com/maps/api/js?key=AIzaSyBQdDcftXeBfg8aBdscVTruA9fDH97KMBE&sensor=false&libraries=geometry' );
    wp_register_script('jquery.js', THE_GRID_PLUGIN_URL . 'assets/js/jquery.js', array('jquery'), '');
    wp_enqueue_script('jquery.js');
    wp_register_script('jquery-ui-1.8.16.custom.min.js', THE_GRID_PLUGIN_URL . 'assets/js/jquery-ui-1.8.16.custom.min.js', array('jquery'), '');
    wp_enqueue_script('jquery-ui-1.8.16.custom.min.js');
    wp_register_script('jquery-ui-timepicker-addon.js', THE_GRID_PLUGIN_URL . 'assets/js/jquery-ui-timepicker-addon.js', array('jquery'), '');
    wp_enqueue_script('jquery-ui-timepicker-addon.js');
    wp_register_script('sliderAccess.js', THE_GRID_PLUGIN_URL . 'assets/js/sliderAccess.js', array('jquery'), '');
    wp_enqueue_script('sliderAccess.js');

    wp_register_script('the-grid.js', THE_GRID_PLUGIN_URL . 'the-grid.js', array('jquery'), '');
    wp_enqueue_script('the-grid.js');
}
if(in_array("edit-tags.php", $arr_current_url) && $_GET['action'] == 'edit' && $_GET['taxonomy'] == 'category'){
    wp_register_style('grid-color-cat.css', THE_GRID_PLUGIN_URL . 'grid-color-cat.css', array(), '');
    wp_enqueue_style('grid-color-cat.css');

    wp_register_script('jquery.js', THE_GRID_PLUGIN_URL . 'assets/js/jquery.js', array('jquery'), '');
    wp_enqueue_script('jquery.js');
    wp_register_script('the-grid-category.js', THE_GRID_PLUGIN_URL . 'the-grid-category.js', array('jquery'), '');
    wp_enqueue_script('the-grid-category.js');
}
//add_meta_boxes
add_action( 'add_meta_boxes', 'the_grid_add_custom_box' );
add_action( 'save_post', 'the_grid_post' );

function the_grid_add_custom_box() {
    add_meta_box(
        'the_grid_sectionid',
        __( 'The Grid Map', 'the_grid_textdomain' ),
        'the_grid_inner_custom_box',
        'reviews'
    );
    add_meta_box(
        'the_grid_sectionid',
        __( 'The Grid Map', 'the_grid_textdomain' ),
        'the_grid_inner_custom_box',
        'grids'
    );
}
function the_grid_inner_custom_box( $post ) {
    global $post;
    global $wpdb;
    $post_id = $post->ID;
    $grid_data_table = $wpdb->prefix . 'grid_data';
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $grid_data_table WHERE `post_id` = '$post_id' "));

    wp_nonce_field( plugin_basename( __FILE__ ), 'the_grid_noncename' );
    echo '<label for="the_grid_new_field">';
    _e(" ", 'the_grid_textdomain' );
    echo '</label><br> ';
?>
<p>
    <label class="control-label"><?php _e("External article link :", 'the_grid_textdomain' );?></label>
    <input type="text" name="external_link" id="external_link" size="50" value="<?php echo $row->external_link ?>" />
    <i>(Leave blank to ignore this)</i>
</p>
<div id="map-canvas" style="width: 100%; height: 300px;"></div>
<div class="the_grid_canvas">
    <i>(You can drag and drop the marker to set the exact position)</i>
</div>
<p>
    <label class="the_grid_left"><?php _e("Address :", 'the_grid_textdomain' );?></label>
    <input type="text" class="form_input_contact" name="grid_address" id="grid-address" size="50" value="<?php echo $row->grid_address ?> "/>
    <label class="the_grid_view"><a href="javascript:void(0)" id="view-in-map">View in map</a></label>
</p>
<p>
    <label><?php _e("Latitude :", 'the_grid_textdomain' );?></label>
    <input type="text" id="grid-lat" size="35" name="grid_lat" value="<?php echo $row->grid_lat ?>" />
</p>
<p>
    <label><?php _e("Longitude :", 'the_grid_textdomain' );?></label>
    <input type="text" id="grid-lng" size="35" name="grid_lng" value="<?php echo $row->grid_lng ?>" />
</p>
<p>
    <label class="control-label"><?php _e("Expire Date :", 'the_grid_textdomain' );?></label>
    <?php $exp_date = current_time('mysql'); ?>
    <input type="text" value="<?php echo (empty($row->grid_expire_date))?date("Y-m-d H:i:d", strtotime($exp_date." +24hours")):$row->grid_expire_date; ?>" id="grid_expire_date" name="grid_expire_date" />
    <input type="hidden" id="hdn-grid-zoom"  name="hdn_grid_zoom" value="<?php echo (!empty($row->grid_zoom))?$row->grid_zoom:'15'; ?>" />
</p>

<?php } ?>
<?php
function the_grid_post( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;
    if ( !wp_verify_nonce( $_POST['the_grid_noncename'], plugin_basename( __FILE__ ) ) )
        return;
    // Check permissions
    if ( 'grids' == $_POST['post_type'] )
    {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return;
    }
    else
    {
        if ( !current_user_can( 'edit_post', $post_id ) )
            return;
    }
    $external_link = (isset($_POST['external_link']) && $_POST['external_link']!='')?addhttp($_POST['external_link']):'';
    $grid_address = (isset($_POST['grid_address']))?$_POST['grid_address']:'';
    $grid_lat = (isset($_POST['grid_lat']))?$_POST['grid_lat']:'';
    $grid_lng = (isset($_POST['grid_lng']))?$_POST['grid_lng']:'';
    $hdn_grid_zoom = (isset($_POST['hdn_grid_zoom']))?$_POST['hdn_grid_zoom']:'';
    $grid_expire_date = (isset($_POST['grid_expire_date']))?$_POST['grid_expire_date']:'';
    $grid_expire_fromate_date = date('Y-m-d H:i:s',strtotime($grid_expire_date));

    global $wpdb;
    $grid_data_table = $wpdb->prefix . 'grid_data';

    $ref_id = $wpdb->get_var( $wpdb->prepare( "SELECT * FROM $grid_data_table WHERE post_id = %d", $post_id) );
    if($ref_id){
        $wpdb->update($grid_data_table, array('external_link' => $external_link, 'grid_address' => $grid_address, 'grid_lat' => $grid_lat, 'grid_lng' => $grid_lng, 'grid_zoom' => $hdn_grid_zoom, 'grid_expire_date' => $grid_expire_fromate_date), array('post_id' => $post_id) );
    }else{
        $wpdb->insert($grid_data_table, array('post_id' => $post_id, 'grid_lat' => $grid_lat, 'grid_lng' => $grid_lng, 'external_link' => $external_link, 'grid_address' => $grid_address, 'grid_zoom' => $hdn_grid_zoom, 'grid_expire_date' => $grid_expire_fromate_date));
    }
}

function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

add_action( 'delete_post', 'the_grid_delete_post' );
function the_grid_delete_post( $post_id ) {
    global $wpdb;
    $grid_data_table = $wpdb->prefix . 'grid_data';
    $wpdb->query("DELETE FROM $grid_data_table WHERE post_id = $post_id ");
}

register_activation_hook(__FILE__, 'plugin_install');
function plugin_install() {
    require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    global $wpdb;
    $table = $wpdb->prefix . 'grid_data';
    if( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) != $table ) {
        if ( ! empty( $wpdb->charset ) )
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if ( ! empty( $wpdb->collate ) )
            $charset_collate .= " COLLATE $wpdb->collate";

        $sql = "CREATE TABLE " . $table . " (
			`post_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
			`external_link` varchar(255) NOT NULL DEFAULT '',
			`grid_address` varchar(100) NOT NULL DEFAULT '',
			`grid_lat` double NOT NULL DEFAULT '0',
			`grid_lng` double NOT NULL DEFAULT '0',
			`grid_zoom` tinyint NOT NULL DEFAULT '2',
			`grid_expire_date` DATETIME,
			UNIQUE KEY (post_id)
		) $charset_collate;";
        dbDelta( $sql );
    }
}
//////// The Grid Short Code ///////////////
function the_grid_short_function($atts){

/* @license
 * MyFonts Webfont Build ID 2360090, 2012-09-10T13:09:53-0400
 *
 * The fonts listed in this notice are subject to the End User License
 * Agreement(s) entered into by the website owner. All other parties are
 * explicitly restricted from using the Licensed Webfonts(s).
 *
 * You may obtain a valid license at the URLs below.
 *
 * Webfont: Pragmatica Medium Oblique by ParaType
 * URL: http://www.myfonts.com/fonts/paratype/pragmatica/medium-oblique/
 * Licensed pageviews: 10,000
 *
 * Webfont: Pragmatica Medium by ParaType
 * URL: http://www.myfonts.com/fonts/paratype/pragmatica/medium/
 * Licensed pageviews: unspecified
 *
 * Webfont: Pragmatica Bold by ParaType
 * URL: http://www.myfonts.com/fonts/paratype/pragmatica/pt-pragmatica-bold/
 * Licensed pageviews: unspecified
 *
 * Webfont: Pragmatica Book by ParaType
 * URL: http://www.myfonts.com/fonts/paratype/pragmatica/book/
 * Licensed pageviews: unspecified
 *
 * Webfont: Pragmatica Bold Oblique by ParaType
 * URL: http://www.myfonts.com/fonts/paratype/pragmatica/pt-pragmatica-bold-oblique/
 * Licensed pageviews: unspecified
 *
 * Webfont: Pragmatica Book Oblique by ParaType
 * URL: http://www.myfonts.com/fonts/paratype/pragmatica/book-oblique/
 * Licensed pageviews: unspecified
 *
 *
 * License: http://www.myfonts.com/viewlicense?type=web&buildid=2360090
 * Webfonts copyright: Copyright (c) ParaType, Inc., 2007. All rights reserved.
 *
 * © 2012 Bitstream Inc
*/

    //frontend
    wp_register_style('MyFontsWebfontsKit.css', THE_GRID_PLUGIN_URL . 'TheGridPragmatica/MyFontsWebfontsKit.css', array(), '');
    wp_enqueue_style('MyFontsWebfontsKit.css');
    wp_register_style('the-grid.css', THE_GRID_PLUGIN_URL . 'the-grid.css', array(), '');
    wp_enqueue_style('the-grid.css');

    wp_enqueue_script('google-map-api-v3', 'http://maps.googleapis.com/maps/api/js?key=AIzaSyBQdDcftXeBfg8aBdscVTruA9fDH97KMBE&sensor=false&libraries=geometry' );
    wp_register_script('jquery-1.8.1.min.js', THE_GRID_PLUGIN_URL . 'jquery-1.8.1.min.js', array('jquery'), '');
    wp_enqueue_script('jquery-1.8.1.min.js');
    wp_register_script('googlemap.js', THE_GRID_PLUGIN_URL . 'googlemap.js', array('jquery'), '');
    wp_enqueue_script('googlemap.js');
    wp_register_script('markerclusterer.js', THE_GRID_PLUGIN_URL . 'markerclusterer.js', array('jquery'), '');
    wp_enqueue_script('markerclusterer.js');
    wp_register_script('infobox.js', THE_GRID_PLUGIN_URL . 'infobox.js', array('jquery'), '');
    wp_enqueue_script('infobox.js');
    wp_register_script('jquery.base64.min.js', THE_GRID_PLUGIN_URL . 'jquery.base64.min.js', array('jquery'), '');
    wp_enqueue_script('jquery.base64.min.js');

    $return_string ='<form id="map-search-form" action="" method="post">
                        <input type="hidden" value="toronto" name="search-location" id="search-location" />
                        <input type="hidden" value="0" name="hdn_lat" id="hdn_lat" />
                        <input type="hidden" value="0" name="hdn_lng" id="hdn_lng" />
                        <input type="hidden" value="0" name="hdn_distance" id="hdn_distance" />
                   </form>
                <div id="map_canvas" style="width: 100%;height: 713px"></div>';
    return $return_string;
}

///////////  add taxonomy Table   /////////////////////
register_activation_hook(__FILE__, 'the_grid_taxonomy_extra_table');
function the_grid_taxonomy_extra_table() {
    require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    global $wpdb;
    $grid_taxonomy_table = $wpdb->prefix . 'grid_markers';
    if( $wpdb->get_var( "SHOW TABLES LIKE '$grid_taxonomy_table'" ) != $grid_taxonomy_table ) {
        if ( ! empty( $wpdb->charset ) )
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if ( ! empty( $wpdb->collate ) )
            $charset_collate .= " COLLATE $wpdb->collate";

        $grid_taxonomy_sql = "CREATE TABLE " . $grid_taxonomy_table . " (
			`term_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
			`color` varchar(100) NOT NULL DEFAULT '',
			`icon` varchar(200) NOT NULL DEFAULT '',
			`h_icon` varchar(200) NOT NULL DEFAULT '',
			`active` tinyint NOT NULL DEFAULT '1',
	    	 UNIQUE KEY (term_id)
		) $charset_collate;";
        dbDelta( $grid_taxonomy_sql );
    }
}

///////////  add taxonomy extra field  /////////////////////
add_action('admin_head', 'the_grid_inti');
function the_grid_inti() {
    $the_grid_taxonomies = get_taxonomies();
    if (is_array($the_grid_taxonomies)) {
        foreach ($the_grid_taxonomies as $the_grid_taxonomy ) {
            add_action($the_grid_taxonomy.'_add_form_fields', 'the_grid_add_texonomy_field');
            add_action($the_grid_taxonomy.'_edit_form_fields', 'the_grid_edit_texonomy_field');
        }
    }
}

// add image field in add form
function the_grid_add_texonomy_field() {
    wp_enqueue_style('thickbox');
    wp_enqueue_script('thickbox');
}

// Add Texonomy Field in Edit Form
function the_grid_edit_texonomy_field($term_id) {
    wp_enqueue_style('thickbox');
    wp_enqueue_script('thickbox');

    $grid_map_allowed_cats = get_option( 'grid_map_allowed_cats' );
    $arr_grid_map_allowed_cats = explode("|", $grid_map_allowed_cats);
    if(!empty($arr_grid_map_allowed_cats)){
        if(in_array($_REQUEST['tag_ID'], $arr_grid_map_allowed_cats)){

            global $wpdb;
            $term_id =$term_id->term_id;
            $grid_taxonomy_table = $wpdb->prefix . 'grid_markers';

            $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $grid_taxonomy_table WHERE `term_id` = '$term_id'"));
            $isColor = $row->color;
            $isIcon = $row->icon;
            $isH_icon = $row->h_icon;
            echo '<tr class="form-field">
                <th scope="row" valign="top"><label for="taxonomy_color">Color</label></th>
                <td>
                  <select name="color" class="the-grid-map-marker-prop">
                      <option value="red" '.(($isColor == "red") ? 'selected="selected"' : '').'>Red</option>
                      <option value="green" '.(($isColor == "green") ? 'selected="selected"' : '').'>Green</option>
                      <option value="blue" '.(($isColor == "blue") ? 'selected="selected"' : '').'>Blue</option>
                      <option value="pink" '.(($isColor == "pink") ? 'selected="selected"' : '').'>Pink</option>
                      <option value="violet" '.(($isColor == "violet") ? 'selected="selected"' : '').'>Violet</option>
                      <option value="yellow" '.(($isColor == "yellow") ? 'selected="selected"' : '').'>Yellow</option>
                  </select>
                  <label class="marker-prop-status" id="marker-prop-status-color"></label>
                </td>
            </tr>';
            echo '<tr class="form-field">
                <th scope="row" valign="top"><label for="taxonomy_icon">Icon</label></th>
                <td>
                <select name="icon" class="the-grid-map-marker-prop">
                      <option value="ico_red" '.(($isIcon == "ico_red") ? 'selected="selected"' : '').'>Red</option>
                      <option value="ico_green" '.(($isIcon == "ico_green") ? 'selected="selected"' : '').'>Green</option>
                      <option value="ico_blue" '.(($isIcon == "ico_blue") ? 'selected="selected"' : '').'>Blue</option>
                      <option value="ico_pink" '.(($isIcon == "ico_pink") ? 'selected="selected"' : '').'>Pink</option>
                      <option value="ico_violet" '.(($isIcon == "ico_violet") ? 'selected="selected"' : '').'>Violet</option>
                      <option value="ico_yellow" '.(($isIcon == "ico_yellow") ? 'selected="selected"' : '').'>Yellow</option>
                </select>
                <label class="marker-prop-status" id="marker-prop-status-icon"></label>
                </td>
            </tr>';
            echo '<tr class="form-field">
                <th scope="row" valign="top"><label for="taxonomy_hicon">Rollover Icon</label></th>
                <td>
                <select name="h_icon" class="the-grid-map-marker-prop">
                      <option value="hico_red" '.(($isH_icon == "hico_red") ? 'selected="selected"' : '').'>Red</option>
                      <option value="hico_green" '.(($isH_icon == "hico_green") ? 'selected="selected"' : '').'>Green</option>
                      <option value="hico_blue" '.(($isH_icon == "hico_blue") ? 'selected="selected"' : '').'>Blue</option>
                      <option value="hico_pink" '.(($isH_icon == "hico_pink") ? 'selected="selected"' : '').'>Pink</option>
                      <option value="hico_violet" '.(($isH_icon == "hico_violet") ? 'selected="selected"' : '').'>Violet</option>
                      <option value="hico_yellow" '.(($isH_icon == "hico_yellow") ? 'selected="selected"' : '').'>Yellow</option>
                </select>
                <label class="marker-prop-status" id="marker-prop-status-hicon"></label>
                </td>
            </tr>';
            echo '<input type="hidden" id="hdn-the-grid-edit-category" name="hdn-the-grid-edit-category" value="1" />';
            echo '<input type="hidden" id="hdn-the-grid-in-use" value="0" />';
        }
    }
}
///// Save Taxonomy Data
add_action('edit_term','the_grid_save_taxonomy');
add_action('create_term','the_grid_save_taxonomy');
add_action('delete_term','the_grid_delete_taxonomy');
function the_grid_save_taxonomy($term_id) {
    if(empty($_POST['color'])){
        return false;
    }
    $color = (isset($_POST['color']))?$_POST['color']:'';
    $icon = (isset($_POST['icon']))?$_POST['icon']:'';
    $h_icon = (isset($_POST['h_icon']))?$_POST['h_icon']:'';

    global $wpdb;
    $grid_taxonomy_table = $wpdb->prefix . 'grid_markers';

    $grid_taxonomy_ref_id = $wpdb->get_var( $wpdb->prepare( "SELECT * FROM $grid_taxonomy_table WHERE term_id = %d", $term_id) );
    if($grid_taxonomy_ref_id){
        $wpdb->update($grid_taxonomy_table, array('color' => $color,'icon' => $icon,'h_icon' => $h_icon), array('term_id' => $term_id) );
    }else{
        $wpdb->insert($grid_taxonomy_table, array('term_id' => $term_id,'color' => $color,'icon' => $icon,'h_icon' => $h_icon));
    }
}

function the_grid_delete_taxonomy($term_id) {
    global $wpdb;
    $grid_taxonomy_table = $wpdb->prefix . 'grid_markers';
    $wpdb->query( $wpdb->prepare("DELETE FROM ".$grid_taxonomy_table." WHERE term_id = '$term_id'") );
}

function register_shortcodes(){
    add_shortcode('the_grid', 'the_grid_short_function');
}
add_action( 'init', 'register_shortcodes');

//Settings Option Page Grid
/*
* Add a setting page under wordpress Settings menu
*/
add_action('admin_menu', function(){
    add_submenu_page('options-general.php', 'Grid Map Settings', 'Grid Map Settings', 'manage_options', 'grid-settings-page', 'grid_settings_page');
});

/*
* Setting page callback function
*/
function grid_settings_page(){
    global $wpdb;
    $grid_taxonomy_table = $wpdb->prefix . 'grid_markers';

    //submit setting
    if($_POST['Submit']){
        $grid_home_page_url = (isset($_POST['grid_home_page_url']))?addhttp($_POST['grid_home_page_url']):'';
        $grid_sign_up_url = (isset($_POST['grid_sign_up_url']))?addhttp($_POST['grid_sign_up_url']):'';
        $grid_default_map_location = (isset($_POST['grid_default_map_location']))?$_POST['grid_default_map_location']:'';
        $grid_map_allowed_cats = (isset($_POST['grid_map_allowed_cats[]']))?$_POST['grid_map_allowed_cats[]']:'';

        $grid_map_allowed_cats = ($_POST['grid_map_allowed_cats']);

        $grid_map_allowed_cats_id = '';
        $allowed_cats = 0;

        if(!empty($grid_map_allowed_cats)){
            foreach($grid_map_allowed_cats as $key=>$val){
                $grid_map_allowed_cats_id .= $key.'|';
                $allowed_cats .= $key.',';
            }
            $grid_map_allowed_cats_id = substr($grid_map_allowed_cats_id, 0, -1);
            $allowed_cats = substr($allowed_cats, 0, -1);
        }

        $option_name_home_page_url = 'grid_home_page_url';
        $new_value_home_page_url   = $grid_home_page_url;
        if ( get_option( $option_name_home_page_url ) != $new_value_home_page_url ) {
            update_option( $option_name_home_page_url, $new_value_home_page_url );
        } else {
            $deprecated = ' ';
            $autoload = 'yes';
            add_option( $option_name_home_page_url, $new_value_home_page_url, $deprecated, $autoload );
        }

        $option_name_sign_up_url = 'grid_sign_up_url';
        $new_value_sign_up_url   = $grid_sign_up_url;
        if ( get_option( $option_name_sign_up_url ) != $new_value_sign_up_url ) {
            update_option( $option_name_sign_up_url, $new_value_sign_up_url );
        } else {
            $deprecated = ' ';
            $autoload = 'yes';
            add_option( $option_name_sign_up_url, $new_value_sign_up_url, $deprecated, $autoload );
        }

        $option_name_default_map_location = 'grid_default_map_location';
        $new_value_default_map_location   = $grid_default_map_location;
        if ( get_option( $option_name_default_map_location ) != $new_value_default_map_location ) {
            update_option( $option_name_default_map_location, $new_value_default_map_location );
        } else {
            $deprecated = ' ';
            $autoload = 'yes';
            add_option( $option_name_default_map_location, $new_value_default_map_location, $deprecated, $autoload );
        }
        $option_grid_map_allowed_cats = 'grid_map_allowed_cats';
        $new_grid_map_allowed_cats = $grid_map_allowed_cats_id;
        if ( get_option( $option_grid_map_allowed_cats ) != $new_grid_map_allowed_cats ) {
            update_option( $option_grid_map_allowed_cats, $new_grid_map_allowed_cats );
        } else {
            $deprecated = ' ';
            $autoload = 'yes';
            add_option( $option_grid_map_allowed_cats, $new_grid_map_allowed_cats, $deprecated, $autoload );
        }
        $wpdb->query( $wpdb->prepare("UPDATE ".$grid_taxonomy_table." SET active = '0'") );
        $wpdb->query( $wpdb->prepare("UPDATE ".$grid_taxonomy_table." SET active = '1' WHERE term_id IN ($allowed_cats)") );
        $update = true;
    }
    wp_nonce_field('update-options');
    ?>
<div class="wrap">
    <div class="icon32" id="icon-options-general"></div>
    <h2>Grids Setting Page:</h2>
    <?php
    // If $update retun true, show successfull message
    if($update){
        echo '<div class="updated below-h2" id="message"><p>Settings saved.</a></div>';
    }
    ?>
    <form action="" method="post">
        <div id="grid-map-options">
        <table>
        <tr>
            <td><label class=""><?php _e("The Grid Home Page ( Url ) :", 'the-grid' );?></label></td>
            <td><input type="text" class="grid-home-page-url" name="grid_home_page_url" id="grid-home-page-url" size="50" value="<?php echo (get_option( 'grid_home_page_url' )=='')?'http://www.thegridto.com/':get_option( 'grid_home_page_url' ); ?>"/></td>
        </tr>
        <tr>
            <td><label class=""><?php _e("The Grid Sign Up ( url) :", 'the-grid' );?></label></td>
            <td><input type="text" class="grid-sign-up-url" name="grid_sign_up_url" id="grid-sign-up-url" size="50" value="<?php echo get_option( 'grid_sign_up_url' ); ?> "/>
        </tr>
        <tr>
            <td><label class=""><?php _e("The Grid Default Map Location :", 'the-grid' );?></label></td>
            <td><input type="text" class="grid-default-map-location" name="grid_default_map_location" id="grid-default-map-location" size="50" value="<?php echo (get_option( 'grid_default_map_location' )=='')?'Toronto':get_option( 'grid_default_map_location' ); ?> "/>
        </tr>
        </table>
        <br/>
        <?php $grid_map_allowed_cats = get_option( 'grid_map_allowed_cats' );
            $arr_grid_map_allowed_cats = explode("|", $grid_map_allowed_cats);
        ?>
        <div class="grid-default-map">
        <label class=""><?php _e("The Grid Map Allowed Categories :", 'the-grid' );?></label></br>
            (<i>When you will edit the following checked categories, you will find extra category options for the grid map</i>)</br>
        <?php
            $args = array(
            'hide_empty' => 0
            );
        ?>
        <?php
            $categories = get_categories($args );
            foreach ($categories as $category) { ?>
            <?php $grid_map_allowed_cats_name = $category->cat_name;?>
            <?php $grid_map_allowed_cats_id = $category->term_id; ?>
            </br><input type="checkbox" <?php echo (in_array($grid_map_allowed_cats_id,$arr_grid_map_allowed_cats))?'checked="checked"':'' ?> class="grid-map-allowed-cats" name="grid_map_allowed_cats[<?php echo $grid_map_allowed_cats_id; ?>]" id="<?php echo $grid_map_allowed_cats_id; ?>" value="<?php echo $grid_map_allowed_cats_name;?>"/> <?php echo $grid_map_allowed_cats_name; ?></br>
        <?php }?>
        </div>
        </div>
        <p class="submit">
            <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>"/>
        </p>
        <div style="border: 1px solid #808080;padding: 10px">
                <b>Important Note to show grid map at front end:</b>
            <ul>
                <li>Show grid map in a page:
                <blockquote><i>Go to Pages->Add New; Write the short code <?php echo highlight_string("[the_grid]", true); ?> into page content editor; Then publish it.</i></blockquote>
                </li>
                <li>Show grid map in a post:
                <blockquote><i>Go to Posts->Add New; Write the short code <?php echo highlight_string("[the_grid]", true); ?> into post content editor; Then publish it.</i></blockquote>
                </li>
                <li>Show grid map in a custom template:
                <blockquote><i>Open the page.php file within your active theme folder; Write the code <?php echo highlight_string("<?php echo do_shortcode('[the_grid]'); ?>", true); ?> in appropriate place.</i> </blockquote>
                </li>
            </ul>
        </div>
    </form>
</div><!-- wrap -->
<?php
}
//end