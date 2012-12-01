<?php
error_reporting(0);

if( $_GET['action'] != 'do-this-update-right-now' )
{
    die('Access Denied');
}

require_once 'wp-content/bootstrap.php';
require_once WP_CONTENT_DIR . '/plugins/solr-for-wordpress/SolrPhpClient/Apache/Solr/Service.php';

$solr = new Apache_Solr_Service(SOLR_HOST, SOLR_PORT, SOLR_PATH);

$autodata = new \Emicro\Model\Autodata($solr);
global $wpdb;
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);

$sql = "SELECT
            wp_wheels_vehicle_review.post_id,
            wp_wheels_vehicle_review.star_rating,
            wp_wheels_vehicle_review.user_rating,
            wp_wheels_custom_data.vehicle_id_1,
            wp_postmeta.meta_key,
            wp_postmeta.meta_value as popularity
        FROM
            wp_wheels_vehicle_review
        INNER JOIN wp_wheels_custom_data ON wp_wheels_vehicle_review.post_id = wp_wheels_custom_data.post_id
        INNER JOIN wp_postmeta ON wp_wheels_vehicle_review.post_id = wp_postmeta.post_id
        WHERE wp_postmeta.meta_key = 'wheels_post_popularity' AND wp_postmeta.meta_value > CAST(wp_postmeta.meta_value AS UNSIGNED) AND wp_wheels_custom_data.vehicle_id_1 != ''
        ";
$result = $wpdb->get_results($sql);

foreach($result as $row)
{
    $data = array(
        'star_rating' => (float)$row->star_rating,
        'star_rating' => (float)$row->user_rating,
        'review_id' => (int)$row->post_id,
        'popularity' => (int)$row->popularity
    );
    $autodata->updateVehicle($data, $row->vehicle_id_1);
}