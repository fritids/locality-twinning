<?php

namespace Emicro\Model;

include_once WP_CONTENT_DIR . '/bootstrap.php';

class Omniture extends Base
{
    protected $ads = array();
    protected $originals = array();

    static function getPageName()
    {

        $postType = get_query_var('post_type');
        $taxonomy  = get_query_var('taxonomy');
        $term      = get_query_var('term');

        $pageName = '';

        list($empty, $page, $vehicle_id) = explode('/', $_SERVER['REQUEST_URI']);

        if (is_home()) {

            $pageName .= 'home';

        } elseif (is_single()) {

            global $post;

            $pageName .= $postType .'|'. $post->post_name;

        } elseif (is_post_type_archive()) {

            $pageName .= $postType;

        } elseif ($page == 'vehicles') {

            $pageName .= $page .'|'. $vehicle_id;

        } elseif ( !empty($taxonomy) ) {

            $pageName .= $taxonomy .'|'. $term;

        } elseif ($page == 'vehicle-finder') {

            $pageName .= 'vehicle-finder';

        } elseif ($page == 'mywheels') {

            $pageName .= 'mywheels';

        } elseif ($page == 'search') {

            $pageName .= 'search';

        } else {

            // Decide later what should be default

        }

        return $pageName;
    }

}