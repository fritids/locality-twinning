<?php
/*
Plugin Name: Review
Plugin URI: http://emicrograph.com
Description: Custom feature plugin for Wheels.ca. This plugin dependable on Wheels - Global Taxonomies plugin.
Author: Md. Sirajus Salayhin
Version: 1.0
Author URI: http://emicrograph.com
*/

include_once WP_CONTENT_DIR . '/bootstrap.php';
use \Emicro\Plugin\Varnish;

define('WHEELS_VEHICLE_REVIEW_REF_TABLE', 'wheels_vehicle_review');
define('WHEELS_QUOTE_REF_TABLE', 'wheels_custom_data');
register_activation_hook(__FILE__, 'wheels_vehicle_review_install');
register_deactivation_hook(__FILE__, 'wheels_vehicle_review_uninstall');


add_action('init', 'wheels_register_post_type_vehicle_review');
add_action('add_meta_boxes', 'wheels_vehicle_review_meta_box');
add_action('save_post', 'wheels_vehicle_review_post_submit');
add_action('edit_post', 'wheels_vehicle_review_varnish_invalidate', 99);
add_action('delete_post', 'wheels_vehicle_review_varnish_invalidate', 99);

function wheels_vehicle_review_varnish_invalidate($postId)
{
    $acode = $_POST['vehicle_id'];

    if( get_post_type($postId) == 'reviews' ){
    $urls = array(
        'reviews' => get_permalink($postId),
        'archive-reviews-carousel' => get_template_directory_uri() . '/esi/reviews/archive-reviews-carousel.php',
        'archive_reviews_second_opinion' => get_template_directory_uri() . '/esi/reviews/archive_reviews_second_opinion.php',
        'archive_reviews_vehicle_review' => get_template_directory_uri() . '/esi/reviews/archive_reviews_vehicle_review.php',
        'archive_reviews_review_video' => get_template_directory_uri() . '/esi/reviews/archive_reviews_review_video.php',
        'vehicles_and_reviews_feature' => get_template_directory_uri() . '/esi/header/vehicles_and_reviews_feature.php',
        'user_rating' => get_template_directory_uri() . '/esi/reviews/user_rating.php',
        'vehicles_acode' => site_url().'/vehicles/'.$acode,
    );

    Varnish::purgeAll($urls);
   }
}

function wheels_vehicle_review_install()
{

    wheels_register_post_type_vehicle_review();

    wheels_vehicle_review_create_table();

    flush_rewrite_rules();
}

function wheels_vehicle_review_uninstall()
{
    wheels_vehicle_review_drop_table();
}

function wheels_vehicle_review_create_table()
{
    global $wpdb;

    $vehicleReviewRefTable = $wpdb->prefix . WHEELS_VEHICLE_REVIEW_REF_TABLE;

    $sql = "CREATE TABLE `{$vehicleReviewRefTable}` (
                  `wheels_vehicle_review_id` int(11) NOT NULL AUTO_INCREMENT,
                  `post_id` int(11) NOT NULL,
                  `star_rating` decimal(3,1) NOT NULL,
                  `user_rating` decimal(3,1) NOT NULL,
                  `introduction` text NOT NULL,
                  `whats_new` text NOT NULL,
                  `performance` text NOT NULL,
                  `the_verdict` text NOT NULL,
                  `vehicle_review_video_id` VARCHAR (120) DEFAULT NULL,
                  PRIMARY KEY (`wheels_vehicle_review_id`),
                  KEY `post_id` (`post_id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";


    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}


function wheels_vehicle_review_drop_table()
{

    global $wpdb;

    $vehicleReviewRefTable = $wpdb->prefix . WHEELS_VEHICLE_REVIEW_REF_TABLE;
    if ($wpdb->get_var("show tables like '$vehicleReviewRefTable'") == $vehicleReviewRefTable) {
        $sqlReview = "DROP TABLE $vehicleReviewRefTable";
        $wpdb->query($sqlReview);
    }
}


function wheels_register_post_type_vehicle_review()
{
    $labels = array(
        'name' => _x('Reviews', 'post type general name'),
        'singular_name' => _x('New', 'post type singular name'),
        'add_new' => _x('Add New', 'Reviews'),
        'add_new_item' => __('Reviews'),
        'edit_item' => __('Edit Reviews'),
        'new_item' => __('New reviews'),
        'all_items' => __('All reviews'),
        'view_item' => __('View reviews'),
        'search_items' => __('Search reviews'),
        'not_found' => __('No reviews found'),
        'not_found_in_trash' => __('No reviews found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Reviews'

    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title','editor' ,'author', 'thumbnail', 'excerpt', 'comments')
    );
    register_post_type('reviews', $args);
}

function wheels_vehicle_review_meta_box()
{
    //$post_types = get_option(WHEELS_VEHICLE_REVIEW_SETTINGS_FIELD);
    $post_type = 'reviews';
    //if(!is_array($post_types)) $post_types = array();

    //foreach($post_types as $post_type){
    add_meta_box(
        'wheels_vehicle_review_meta_box_id',
        __('Vehicle Review'),
        'wheels_vehicle_review_meta_box_content',
        $post_type
    );
    //}
}

function wheels_vehicle_review_meta_box_content($post)
{
    global $wpdb;
    $post_id = $post->ID;

    $vehicleReviewRefTable = $wpdb->prefix . WHEELS_VEHICLE_REVIEW_REF_TABLE;

    $vehicleData = $wpdb->get_row($wpdb->prepare("SELECT * FROM $vehicleReviewRefTable WHERE `post_id` = '$post_id' "));

    $introduction = unserialize($vehicleData->introduction);

    $whatsNew = unserialize($vehicleData->whats_new);
    $performance = unserialize($vehicleData->performance);
    $theVerdict = unserialize($vehicleData->the_verdict);

    wp_nonce_field(plugin_basename(__FILE__), 'wheels_vehicle_review_metabox_noncename');

    echo '<table border="0" width="100%">';
    echo '<tr>';
    echo '<td>Star Rating: </td>';
    echo '<td width="80%">';
    echo "<select name='star_rating'>
          <option value='0.0' " . (($vehicleData->star_rating == 0.0) ? "selected='selected'" : '') . " >n/a</option>
          <option value='1.0' " . (($vehicleData->star_rating == 1.0) ? "selected='selected'" : '') . " >1.0</option>
          <option value='1.5' " . (($vehicleData->star_rating == 1.5) ? "selected='selected'" : '') . " >1.5</option>
          <option value='2.0' " . (($vehicleData->star_rating == 2.0) ? "selected='selected'" : '') . " >2.0</option>
          <option value='2.5' " . (($vehicleData->star_rating == 2.5) ? "selected='selected'" : '') . " >2.5</option>
          <option value='3.0' " . (($vehicleData->star_rating == 3.0) ? "selected='selected'" : '') . " >3.0</option>
          <option value='3.5' " . (($vehicleData->star_rating == 3.5) ? "selected='selected'" : '') . " >3.5</option>
          <option value='4.0' " . (($vehicleData->star_rating == 4.0) ? "selected='selected'" : '') . " >4.0</option>
          <option value='4.5' " . (($vehicleData->star_rating == 4.5) ? "selected='selected'" : '') . " >4.5</option>
          <option value='5.0' " . (($vehicleData->star_rating == 5.0) ? "selected='selected'" : '') . " >5.0</option>
          </select>";
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td colspan="2">';
    echo '<hr width="100%"/>';
    echo '</td>';
    echo '</tr>';


    echo '<tr>';
    echo '<td>What\'s Best Title </td>';
    echo '<td>';
    echo '<input type="text" name="whats_best_title" value="' . stripslashes($introduction['whats_best_title']) . '" style="width:80%">';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>What\'s Best Content </td>';
    echo '<td>';
    echo '<textarea rows="3" name="whats_best" style="width:100%">' . stripslashes($introduction['whats_best']) . '</textarea>';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td colspan="2">';
    echo '<hr width="100%"/>';
    echo '</td>';
    echo '</tr>';


    echo '<tr>';
    echo '<td>What\'s Worst Title: </td>';
    echo '<td>';
    echo '<input type="text" name="whats_worst_title" value="' . stripslashes($introduction['whats_worst_title']) . '" style="width:80%">';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>What\'s Worst Content: </td>';
    echo '<td>';
    echo '<textarea rows="3" name="whats_worst" style="width:100%">' . stripslashes($introduction['whats_worst']) . '</textarea>';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td colspan="2">';
    echo '<hr width="100%"/>';
    echo '</td>';
    echo '</tr>';


    echo '<tr>';
    echo '<td>What\'s Interesting Title</td>';
    echo '<td>';
    echo '<input type="text" name="whats_interesting_title" value="' . stripslashes($introduction['whats_interesting_title']) . '" style="width:80%">';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>What\'s Interesting Content: </td>';
    echo '<td>';
    echo '<textarea rows="3" name="whats_interesting" style="width:100%">' . stripslashes($introduction['whats_interesting']) . '</textarea>';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td colspan="2">';
    echo '<hr width="100%"/>';
    echo '</td>';
    echo '</tr>';


    echo '<tr>';
    echo '<td>Introduction Title</td>';
    echo '<td>';
    echo '<input type="text" name="introduction_title" value="' . stripslashes($introduction['introduction_title']) . '" style="width:80%">';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>Introduction: </td>';
    echo '<td>';
    echo '<textarea rows="3" name="introduction" style="width:100%">' . stripslashes($introduction['introduction']) . '</textarea>';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td colspan="2">';
    echo '<hr width="100%"/>';
    echo '</td>';
    echo '</tr>';


    echo '<tr>';
    echo '<td>What\'s New Title</td>';
    echo '<td>';
    echo '<input type="text" name="whats_new_title" value="' . stripslashes($whatsNew['whats_new_title']) . '" style="width:80%">';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>What\'s New: </td>';
    echo '<td>';
    echo '<textarea rows="3" name="whats_new" style="width:100%">' . stripslashes($whatsNew['whats_new']) . '</textarea>';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td colspan="2">';
    echo '<hr width="100%"/>';
    echo '</td>';
    echo '</tr>';


    echo '<tr>';
    echo '<td>Performance Title</td>';
    echo '<td>';
    echo '<input type="text" name="performance_title" value="' . stripslashes($performance['performance_title']) . '" style="width:80%">';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>Performance: </td>';
    echo '<td>';
    echo '<textarea rows="3" name="performance" style="width:100%">' . stripslashes($performance['performance']) . '</textarea>';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td colspan="2">';
    echo '<hr width="100%"/>';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>Verdict Title</td>';
    echo '<td>';
    echo '<input type="text" name="the_verdict_title" value="' . stripslashes($theVerdict['the_verdict_title']) . '" style="width:80%">';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>Verdict: </td>';
    echo '<td>';
    echo '<textarea rows="3" name="the_verdict" style="width:100%">' . stripslashes($theVerdict['the_verdict']) . '</textarea>';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td colspan="2">';
    echo '<hr width="100%"/>';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>Vehicle Review Video ID: </td>';
    echo '<td>';
    echo '<input type="text" name="vehicle_review_video_id" value="' . stripslashes($vehicleData->vehicle_review_video_id) . '" style="width:80%">';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td colspan="2">';
    echo '<hr width="100%"/>';
    echo '</td>';
    echo '</tr>';

    echo '</table>';

}

define('WHEELS_VEHICLE_REVIEW_SETTINGS_FIELD', 'wheels-vehicle-review-postypes');

function wheels_vehicle_review_post_submit($post_id)
{
    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times

    if (!wp_verify_nonce($_POST['wheels_vehicle_review_metabox_noncename'], plugin_basename(__FILE__)))
        return;


    // Check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return;
    }
    else
    {
        if (!current_user_can('edit_post', $post_id))
            return;
    }

    $serializeIntroduction['whats_best_title'] = $_POST['whats_best_title'];
    $serializeIntroduction['whats_best'] = $_POST['whats_best'];
    $serializeIntroduction['whats_worst_title'] = $_POST['whats_worst_title'];
    $serializeIntroduction['whats_worst'] = $_POST['whats_worst'];
    $serializeIntroduction['whats_interesting_title'] = $_POST['whats_interesting_title'];
    $serializeIntroduction['whats_interesting'] = $_POST['whats_interesting'];
    $serializeIntroduction['introduction_title'] = $_POST['introduction_title'];
    $serializeIntroduction['introduction'] = $_POST['introduction'];

    $serializeWhatsNew['whats_new_title'] = $_POST['whats_new_title'];
    $serializeWhatsNew['whats_new'] = $_POST['whats_new'];

    $serializePerformance['performance_title'] = $_POST['performance_title'];
    $serializePerformance['performance'] = $_POST['performance'];

    $serializeTheVerdict['the_verdict_title'] = $_POST['the_verdict_title'];
    $serializeTheVerdict['the_verdict'] = $_POST['the_verdict'];

    $data['introduction'] = serialize($serializeIntroduction);

    $data['star_rating'] = $_POST['star_rating'];
    $data['whats_new'] = serialize($serializeWhatsNew);
    $data['performance'] = serialize($serializePerformance);
    $data['the_verdict'] = serialize($serializeTheVerdict);
    $data['post_id'] = $post_id;
    $data['vehicle_review_video_id'] = $_POST['vehicle_review_video_id'];

    if (!empty($data)) {

        global $wpdb;

        $vehicleReviewRefTable = $wpdb->prefix . WHEELS_VEHICLE_REVIEW_REF_TABLE;

        $ref_id = $wpdb->get_var($wpdb->prepare("SELECT wheels_vehicle_review_id FROM $vehicleReviewRefTable WHERE post_id = %d", $post_id));


        if ($ref_id) {
            $update = $wpdb->update($vehicleReviewRefTable, $data, array('post_id' => $post_id));

            if ($update !== false) {
                wheels_review_update_solr_data($post_id, $data['star_rating']);
            }

        } else {
            $insert = $wpdb->insert($vehicleReviewRefTable, $data);

            if ($insert !== false) {
                wheels_review_update_solr_data($post_id, $data['star_rating']);
            }

        }

    }

}


function wheels_review_update_solr_data($post_id, $star_rating)
{
    global $wpdb;
    $popularity = $wpdb->get_var("SELECT CAST(meta_value as UNSIGNED) FROM wp_postmeta WHERE meta_key = 'wheels_post_popularity' AND post_id = '$post_id'");
    $popularity++;

    $acode = trim($_POST['vehicle_id']);
    $vehicleModel = new \Emicro\Model\Vehicle($wpdb);

    if (!empty($acode)) {
        $vehicleModel->updateVehicle(
            array(
                'star_rating' => (float)$star_rating,
                'review_id' => $post_id,
                'popularity' => $popularity
            ),
            $acode
        );
    }

}


add_action('wp_footer',
    function()
    {
        global $template;
        if (basename($template) == 'archive-reviews.php') {
            echo '<script type="text/javascript">var WHEELS_VEHICLE_REVIEW_AJAX_DATA = "' . plugins_url('vehicle-review-ajax-data.php', __FILE__) . '"</script>';
            echo '<script type="text/javascript" src="' . plugins_url('vehicle-review-js.js', __FILE__) . '"></script>';
        }
    }
);
