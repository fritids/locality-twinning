<?php

/**
 * Plugin Name: News
 * Plugin URI: http://www.emicrograph.com
 * Description: Custom news plugin for Wheels.ca. This plugin dependable on Wheels - Global Taxonomies plugin.
 * Author: Samiul Amin
 * Version: 1.0
 * Author URI: http://www.emicrograph.com
 */

use \Emicro\Plugin\Varnish;

register_activation_hook(__FILE__, 'wheels_news_install');

add_action('init', 'wheels_news_register', 0);
add_action('edit_post', 'wheels_news_invalidate', 99);
add_action('delete_post', 'wheels_news_invalidate', 99);

function wheels_news_install()
{
    wheels_news_register();
    flush_rewrite_rules();
}

function wheels_news_register()
{
    wheels_news_register_post_type();
    wheels_news_register_taxonomy();
}

function wheels_news_invalidate($postId)
{

    Varnish::purgeAll(
        array(
            'wheels-header' => site_url('wheels-header.php'),
            // Purge our expert widget on homepage
            // TODO: check if author is expert, then send purge request
            'our-experts' => get_template_directory_uri(). '/esi/our-experts.php',
            'our-experts-header' => get_template_directory_uri(). '/esi/header/guides_our_experts.php'
        )
    );

    if( get_post_type($postId) == 'news' ){
        $urls = array(
            'news' => get_permalink($postId),
            'home-news-carousel' => get_template_directory_uri() . '/esi/news/home-news-carousel.php',
            'comment-count' => get_template_directory_uri() . '/esi/comment-count.php?post_id='.$postId,
            'landing-news-latest-carousel' => get_template_directory_uri() . '/esi/landing-news-latest-carousel.php',
            'landing-news-latest-list' => get_template_directory_uri() . '/esi/landing-news-latest-list.php',
            'news_and_feature_latest_feature' => get_template_directory_uri() . '/esi/header/news_and_feature_latest_feature.php',
            'news_and_feature_latest_news' => get_template_directory_uri() . '/esi/header/news_and_feature_latest_news.php',
            'news-breaking' => get_template_directory_uri() . '/esi/news/breaking.php',
            'footer-widget' => get_template_directory_uri().'/esi/more-news.php'
        );

        $newsCategory = wp_get_post_terms($postId, 'news-category');
        if(isset($newsCategory) && !is_wp_error($newsCategory))
        {
             foreach($newsCategory as $key => $term)
             {
                 $urls['news-category-'.$key] = get_term_link($term);
             }
        }

        Varnish::purgeAll($urls);
    }

}

function wheels_news_register_post_type()
{
    $labels = array(
        'name'               => _x('News', 'post type general name'),
        'singular_name'      => _x('News', 'post type singular name'),
        'add_new'            => _x('Add News', 'news'),
        'add_new_item'       => __('Add New News'),
        'edit_item'          => __('Edit News'),
        'new_item'           => __('New News'),
        'all_items'          => __('All News'),
        'view_item'          => __('View News'),
        'search_items'       => __('Search News'),
        'not_found'          => __('No news found'),
        'not_found_in_trash' => __('No news found in Trash'),
        'parent_item_colon'  => '',
        'menu_name'          => 'News'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'taxonomies'         => array('post_tag')
    );

    register_post_type('news', $args);
}

function wheels_news_register_taxonomy()
{
    $labels = array(
        'name'              => _x('News Category', 'taxonomy general name'),
        'singular_name'     => _x('News Category', 'taxonomy singular name'),
        'search_items'      => __('Search News Category'),
        'all_items'         => __('All News Category'),
        'parent_item'       => __('Parent News Category'),
        'parent_item_colon' => __('Parent News Category:'),
        'edit_item'         => __('Edit News Category'),
        'update_item'       => __('Update News Category'),
        'add_new_item'      => __('Add New News Category'),
        'new_item_name'     => __('New News Category Name'),
        'menu_name'         => __('News Category'),
    );

    $args = array(
        'hierarchical' => true,
        'labels'       => $labels,
        'show_ui'      => true,
        'query_var'    => true,
        'rewrite'      => array('slug' => 'news-category'),
    );

    register_taxonomy('news-category', array('news'), $args);
}

add_action( 'add_meta_boxes', 'wheels_news_meta_box' );
add_action( 'save_post', 'wheels_news_meta_box_submit' );

function wheels_news_meta_box()
{
    $post_type = 'news';
    add_meta_box(
        'wheels_news_meta_box_id',
        __('Breaking News'),
        'wheels_news_meta_box_content',
        $post_type
    );
}

function wheels_news_meta_box_content($post)
{
    global $wpdb;
    $post_id = $post->ID;

    $customDataTable = $wpdb->prefix . 'wheels_custom_data';
    $row = $wpdb->get_row($wpdb->prepare("SELECT news_breaking FROM $customDataTable WHERE `post_id` = '$post_id' "));
    wp_nonce_field(plugin_basename(__FILE__), 'wheels_news_metabox_noncename');

    echo '<table border="0" width="100%">';
    echo '<tr>';
    echo '<td width="80%">';
    echo "<input type='checkbox' name='news_breaking' id='news_breaking' value='1' ".(($row->news_breaking)?"checked='checked'":'').">";
    echo '</td>';
    echo '</tr>';
    echo '</table>';
}

/* When the post is saved, saves our custom data */
function wheels_news_meta_box_submit( $post_id  ){

    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times

    if ( !wp_verify_nonce( $_POST['wheels_news_metabox_noncename'], plugin_basename( __FILE__ ) ) )
        return;


    // Check permissions
    if ( 'page' == $_POST['post_type'] )
    {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return;
    }
    else
    {
        if ( !current_user_can( 'edit_post', $post_id ) )
            return;
    }
    $news_breaking = ($_POST['news_breaking'])?'1':'0';

    global $wpdb;
    $customDataTable = $wpdb->prefix . 'wheels_custom_data';

    $ref_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id,news_breaking FROM $customDataTable WHERE post_id = %d", $post_id) );

    if($ref_id){
        $wpdb->update($customDataTable, array('news_breaking' => $news_breaking), array('post_id' => $post_id), array('%s'), array('%d') );
    }else{
        $wpdb->insert($customDataTable, array('post_id' => $post_id, 'news_breaking' => $news_breaking), array('%d', '%s'));
    }
}

/**
 * Proxy function to Post->getAll()
 *
 * @param array $args
 * @return mixed
 */
function wheels_news_get($args = array())
{
    global $wpdb;

    $postModel = new \Emicro\Model\Post($wpdb);
    return $postModel->getAll($args);
}

function wheels_news_get_quote_by_term($args)
{
    global $wpdb;

    $defaults = array(
        'type'             => 'latest',
        'post_type'        => 'news',
        'start'            => 0,
        'limit'            => 1,
        'term'             => '',
        'taxonomy'         => 'news-category',
        'popularity_field' => 'wheels_post_popularity',
        'fields'           => 'quote'
    );

    $args = wp_parse_args($args, $defaults);
    $quote_table = $wpdb->prefix . 'wheels_custom_data';

    $query  = "SELECT $wpdb->posts.*, $quote_table.quote FROM $wpdb->posts";
    $query .= " LEFT JOIN $quote_table ON($wpdb->posts.ID = $quote_table.post_id)";

    if (!empty($args['term'])) {
        $query .= " LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)";
        $query .= " LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)";
        $query .= " LEFT JOIN $wpdb->terms ON($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)";
    }

    $query .= " WHERE 1=1";

    if (!empty($args['term'])) {
        $query .= " AND $wpdb->terms.slug = '" . $args['term'] . "'";
        $query .= " AND $wpdb->term_taxonomy.taxonomy = '" . $args['taxonomy'] . "'";
    }

    $query .= " AND $wpdb->posts.post_status = 'publish'";
    $query .= " AND $wpdb->posts.post_type = '" . $args['post_type'] . "'";
    $query .= " AND $quote_table.quote != ''";
    $query .= " ORDER BY $wpdb->posts.post_date DESC";
    $query .= " LIMIT " . $args['start'] . ", " . $args['limit'] . "";

    $results = $wpdb->get_results($wpdb->prepare($query));

    if ($wpdb->num_rows == 0) {
        $results = array();
    }

    return $results;
}
