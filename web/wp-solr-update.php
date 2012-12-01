<?php

require_once __DIR__ . '/wp-content/bootstrap.php';
require_once __DIR__ . '/../lib/Emicro/Model/Autodata.php';
require_once __DIR__ . '/wp-content/plugins/solr-for-wordpress/SolrPhpClient/Apache/Solr/Service.php';

$solr = new Apache_Solr_Service(SOLR_HOST, SOLR_PORT, SOLR_PATH);
$autodata = new \Emicro\Model\Autodata($solr);

global $wpdb;

$result = $wpdb->get_results("SELECT
            wp_posts.post_type,
            wp_postmeta.meta_key,
            wp_postmeta.meta_value,
            wp_wheels_custom_data.vehicle_id_1
            FROM
            wp_posts
            LEFT JOIN wp_postmeta ON wp_posts.ID = wp_postmeta.post_id
            LEFT JOIN wp_wheels_custom_data ON wp_postmeta.post_id = wp_wheels_custom_data.post_id
            WHERE
            wp_posts.post_type = 'reviews'
            AND wp_postmeta.meta_key = 'wheels_post_popularity'
            AND vehicle_id_1 <> ''");
foreach($result as $row)
{
    $autodata->updateVehicle(array('popularity' => 6), $row->vehicle_id_1);
}