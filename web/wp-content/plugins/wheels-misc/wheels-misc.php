<?php

/**
 * Plugin Name: Wheels - Misc
 * Plugin URI: http://emicrograph.com
 * Description: Custom news plugin for Wheels.ca. This plugin dependable on Wheels - Global Taxonomies plugin.
 * Author: Samiul Amin
 * Version: 1.0
 * Author URI: http://emicrograph.com
 */

register_activation_hook(__FILE__, 'wheels_misc_install');
add_action('init', 'wheels_misc_register_post_type');

function wheels_misc_install()
{
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry,
    // when you add a post of this CPT.
    wheels_misc_register_post_type();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}


function wheels_misc_register_post_type()
{
    $args = array(
        'labels' => '',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => false,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array( 'title' )
    );

    register_post_type('activateuser', $args);
    register_post_type('compare', $args);
    register_post_type('lostpassword', $args);
    register_post_type('myprofile', $args);
    register_post_type('mywheels', $args);
}