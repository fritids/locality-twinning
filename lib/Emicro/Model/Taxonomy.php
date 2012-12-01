<?php

namespace Emicro\Model;
include_once WP_CONTENT_DIR . '/bootstrap.php';
class Taxonomy extends Post
{
    function getTerms($options = array())
    {
        $defaults = array(
            'taxonomy' => ''
        );

        $args = array_merge($defaults, $options);
        $prefix = $this->prefix;

        global $wpdb;
        $query = "SELECT $wpdb->terms.name, $wpdb->terms.slug FROM $wpdb->term_taxonomy";
        $query .= " INNER JOIN {$prefix}terms ON($wpdb->term_taxonomy.term_id = {$prefix}terms.term_id)";
        $query .= " INNER JOIN {$prefix}term_relationships ON({$prefix}term_taxonomy.term_taxonomy_id = {$prefix}term_relationships.term_taxonomy_id)";

        if(!empty($args['taxonomy'])){
        $query .= " WHERE {$prefix}term_taxonomy.taxonomy = '" . $args['taxonomy'] . "'";
        }

        $query .= "GROUP BY {$prefix}term_relationships.term_taxonomy_id";
        $results = $wpdb->get_results( $wpdb->prepare($query) );
        if($wpdb->num_rows == 0) $results = array();
        return $results;
    }
}
