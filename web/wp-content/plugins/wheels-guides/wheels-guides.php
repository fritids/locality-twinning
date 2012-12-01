<?php
/*
Plugin Name: Guides
Plugin URI: 
Description: Custom news plugin for Wheels.ca. This plugin has dependency on Wheels - Global Taxonomies plugin.
Author: Rakibul Islam
Version: 1.0
Author URI: 
*/

use \Emicro\Plugin\Varnish;

register_activation_hook( __FILE__, 'wheels_guides_install' );

add_action( 'init', 'register_wheels_guides_post_type' );
//add_action( 'add_meta_boxes', 'wheels_gallery_meta_box' );
add_action('edit_post', 'wheels_guides_invalidate', 99);
add_action('delete_post', 'wheels_guides_invalidate', 99);

function wheels_guides_invalidate($postId)
{
    $urls = array();
    if(get_post_type($postId) == 'guides'){
        $urls = array(
            'guides' => get_permalink($postId),
            'archive-guides-browse-guides' => get_template_directory_uri() . '/esi/news/archive-guides-browse-guides.php',
            'archive-guides-carousel' => get_template_directory_uri() . '/esi/news/archive-guides-carousel.php',
            'footer-guides-widget' => get_template_directory_uri() . '/esi/wheels-guides.php',//to do: wild card needed here
            'homepage-latest-guides' => get_template_directory_uri() . '/esi/guides/homepage-latest-guides.php',
            'guides_latest' => get_template_directory_uri() . '/esi/header/guides_latest.php',//to do: wild card needed here
            'guides_our_experts' => get_template_directory_uri() . '/esi/header/guides_our_experts.php',//to do: wild card needed here
            'footer-widget' => get_template_directory_uri().'/esi/wheels-guides.php'
        );

        $newsCategory = wp_get_post_terms($postId, 'guides-category');
        if(isset($newsCategory) && !is_wp_error($newsCategory))
        {
            foreach($newsCategory as $key => $term)
            {
                $urls['guides-category-'.$key] = get_term_link($term);
            }
        }

        Varnish::purgeAll($urls);

    }else{
        $urls = array(
            'guides_our_experts' => get_template_directory_uri() . '/esi/header/guides_our_experts.php',//to do: wild card needed here
        );

        Varnish::purgeAll($urls);

    }




}

function wheels_guides_install()
{
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry,
    // when you add a post of this CPT.
    register_wheels_guides_post_type();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}

function register_wheels_guides_post_type()
{
    $labels = array(
        'name' => _x('Guides', 'post type general name'),
        'singular_name' => _x('Guide', 'post type singular name'),
        'add_new' => _x('Add New', 'news'),
        'add_new_item' => __('Add New Guide'),
        'edit_item' => __('Edit Guide'),
        'new_item' => __('New Guide'),
        'all_items' => __('All Guides'),
        'view_item' => __('View Guides'),
        'search_items' => __('Search Guides'),
        'not_found' =>  __('No guide found'),
        'not_found_in_trash' => __('No guide found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Guides'

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
        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    );
    register_post_type('guides',$args);
	
	//register texonomy
	$post_types = array('guides');
    $labels = array(
        'name' => _x( 'Guides-categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Guides-category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Guides-category' ),
        'all_items' => __( 'All Guides-category' ),
        'parent_item' => __( 'Parent Guides-category' ),
        'parent_item_colon' => __( 'Parent Guides-category:' ),
        'edit_item' => __( 'Edit Guides-category' ),
        'update_item' => __( 'Update Guides-category' ),
        'add_new_item' => __( 'Add New Guides-category' ),
        'new_item_name' => __( 'New Guides-category Name' ),
        'menu_name' => __( 'Guides-category' ),
    );

    register_taxonomy('guides-category', $post_types, array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'guides-category' ),
    ));
	
}

/*
 * Add guides JS file in theme: Unfortunately this function didn't working
 * TODO: Check Wordpress function reference to make this working
 */
//add_action( 'wp_enqueue_scripts', 'wheels_guides_enqueue' );
function wheels_guides_enqueue() {

    //wp_register_script('wheels-guides-js', plugins_url('guides-js.js', __FILE__), array('jquery'));
    //wp_enqueue_script('wheels-guides-js');

}

add_action('wp_footer',
    function()
    {
        global $template;
        if(basename($template) == 'archive-guides.php')
        {
            echo '<script type="text/javascript">var WHEELS_GUIDE_AJAX_DATA = "' .plugins_url('wheels-guides-ajax-data.php', __FILE__). '"</script>';
            echo '<script type="text/javascript" src="' .plugins_url('guides-js.js', __FILE__). '"></script>';
        }
    }
);
