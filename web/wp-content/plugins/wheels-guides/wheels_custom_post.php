<?php
/**
 * Created by JetBrains PhpStorm.
 * User: General
 * Date: 3/6/12
 * Time: 10:54 AM
 * To change this template use File | Settings | File Templates.
 */
class wheels_custom_post
{
    function wheels_guides_query()
    {
        return $this->wheels_guides_query('latest','guides',0,10,'','guides-category','wheels_post_popularity');
    }

    function wheels_custom_post_query($args = array()){
        $defaults = array(
            'type' => 'latest',
            'post_type' => 'news',
            'start' => 0,
            'limit' => 10,
            'term' => '',
            'taxonomy' => 'news-category',
            'popularity_field' => 'wheels_post_popularity'
        );
        $args = wp_parse_args($args, $defaults);

        global $wpdb;
        $query = "SELECT * FROM $wpdb->posts";

        if($args['type'] == 'popular'){
            $query .= " LEFT JOIN $wpdb->postmeta ON($wpdb->posts.ID = $wpdb->postmeta.post_id)";
        }

        if(!empty($args['term'])){
            $query .= " LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)";
            $query .= " LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)";
            $query .= " LEFT JOIN $wpdb->terms ON($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)";
        }

        $query .= " WHERE 1=1";
        if(!empty($args['term'])){
            $query .= " AND $wpdb->terms.slug = '".$args['term']."'";
            $query .= " AND $wpdb->term_taxonomy.taxonomy = '".$args['taxonomy']."'";
        }

        $query .= " AND $wpdb->posts.post_status = 'publish'";
        $query .= " AND $wpdb->posts.post_type = '".$args['post_type']."'";
        if($args['type'] == 'popular'){
            $query .= " AND $wpdb->postmeta.meta_key = '".$args['popularity_field']."'";
            $query .= " ORDER BY CAST($wpdb->postmeta.meta_value AS UNSIGNED) DESC, $wpdb->posts.post_date DESC";
        }else{
            $query .= " ORDER BY $wpdb->posts.post_date DESC";
        }

        $results = $wpdb->get_results( $wpdb->prepare($query) );
        if($wpdb->num_rows == 0) $results = array();
        return $results;
    }
}
