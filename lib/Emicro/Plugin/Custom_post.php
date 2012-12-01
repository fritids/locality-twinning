<?php

namespace Emicro\Plugin;

class Custom_post
{
    function wheels_guides_query()
    {
        return $this->wheels_custom_post_query(array(
            'type' => 'latest',
            'post_type' => 'guides',
            'start' => 0,
            'limit' => 10,

            'term' => '',
            'taxonomy' => 'guides-category',
            'popularity_field' => 'wheels_post_popularity'
        ));

    }

    function guides_taxonomy()
    {
        return $this->custom_taxonomy('Guides-category');
    }

    function wheels_custom_post_query($args = array()){
        $defaults = array(
            'type' => 'latest',
            'post_type' => 'news',
            'start' => 0,
            'limit' => 10,
            'term' => '',
            'taxonomy' => 'news-category',
            'popularity_field' => 'wheels_post_popularity',
            'except' => '',
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

        if(!empty($args['except'])){
            $query .= " AND $wpdb->posts.ID NOT IN ('".$args['except']."')";
        }

        $query .= " AND $wpdb->posts.post_status = 'publish'";
        $query .= " AND $wpdb->posts.post_type = '".$args['post_type']."'";
        if($args['type'] == 'popular'){
            $query .= " AND $wpdb->postmeta.meta_key = '".$args['popularity_field']."'";
            $query .= " ORDER BY CAST($wpdb->postmeta.meta_value AS UNSIGNED) DESC, $wpdb->posts.post_date DESC";
        }else{
            $query .= " ORDER BY $wpdb->posts.post_date DESC";
        }

        $query .= " LIMIT ".$args['limit'];
        $results = $wpdb->get_results( $wpdb->prepare($query) );
        if($wpdb->num_rows == 0) $results = array();
        return $results;
    }

    function taxonomy_posts($id)
    {
        $terms = get_the_terms($id, 'Guides-category');
        if ( $terms && ! is_wp_error( $terms ) )
        {
            $keys = array_keys($terms);
            $term = $terms[$keys[0]];
            $data = $this->wheels_custom_post_query(array('post_type' => 'guides','term'=>$term->slug, 'taxonomy'=>$term->taxonomy,'except' => $id));
            return array('name'=>$term->name,'data'=>$data);
        }
    }
}
