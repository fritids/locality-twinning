<?php

//session_start();

require_once WP_CONTENT_DIR . '/bootstrap.php';
require_once __DIR__ . '/helpers/string_helper.php';

add_action('after_setup_theme', 'wheels_theme_setup');
add_action('comment_post', 'wheels_comment_saved');
add_action('wp_set_comment_status', 'wheels_comment_approval', '99');
add_filter('show_admin_bar', '__return_false');

if( $_SERVER['HTTP_HOST'] == 'ec2-107-20-15-173.compute-1.amazonaws.com' ){
    add_filter('wp_handle_upload', 'change_file_url', 0);
    add_filter('plugins_url', 'change_file_url', 0);
}

function change_file_url($urls){
    if(is_array($urls))
    {
        $urls['url'] = str_replace(
            array('www.wheels.ca', 'wheels.ca', 'ec2-67-202-36-56.compute-1.amazonaws.com'),
            $_SERVER['HTTP_HOST'],
            $urls['url']
        );
        return $urls;
    }else{
        $url = str_replace(
            array('www.wheels.ca', 'wheels.ca', 'ec2-67-202-36-56.compute-1.amazonaws.com'),
            $_SERVER['HTTP_HOST'],
            $urls
        );
        return $url;
    }
}

\Emicro\Plugin\Assets::setProduction(PRODUCTION_ASSETS_ENABLED);

// Define Sponsored Make, Model,
define('SPONSORED_MAKE', DEFAULT_SPONSORED_MAKE);
define('SPONSORED_MODEL', DEFAULT_SPONSORED_MODEL);
define('SPONSORED_YEAR', DEFAULT_SPONSORED_YEAR);
define('SPONSORED_CLASS', DEFAULT_SPONSORED_CLASS);

// --------------------- GENERIC FUNCTIONS ---------------------

/**
 * Return post related custom data
 *
 * @param int $post_id
 * @return object
 */
function get_custom_values($post_id = 0)
{
    global $post;
    global $wpdb;

    if (!$post_id) {
        $post_id = (!empty($post->ID)) ? $post->ID : 0;
    }

    if (!$post_id) {
        return '';
    }

    $row = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "wheels_custom_data WHERE post_id = " . $post_id);
    return $row;
}

function wheels_get_terms_by_name($term, $taxonomy)
{
    global $wpdb;
    $term = $wpdb->get_row($wpdb->prepare("SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = %s AND t.name = %s LIMIT 1", $taxonomy, htmlentities($term)));
    return $term;
}

/**
 * Generate the ESI include URL
 *
 * @param string $url
 * @param bool   $return
 * @return string
 */
function wheels_esi_include($url, $return = true)
{
    $html = '';

    if (VARNISH_ENABLED) {

        $url = str_replace('http://' . $_SERVER['HTTP_HOST'], '', $url);

        if (wheels_is_development_server()) {
            $html .= "\n<!-- ESI Include Start: $url -->";
            $html .= "<esi:include src=\"$url\" />";
            $html .= "\n<!-- ESI Include End: $url -->\n";
        } else {
            $html .= "<esi:include src=\"$url\" />";
        }

    } else {

        if (wheels_is_development_server()) {
            $html .= "\n<!-- ESI Include Bypass: $url -->";
        }

        $html .= file_get_contents($url);

    }

    return $html;
}

function wheels_is_development_server()
{
    $hosts = array('wheels.local', 'www.wheels.local');
    return in_array($_SERVER['HTTP_HOST'], $hosts);
}

// --------------------- THEME RELATED FUNCTIONS ---------------------

function wheels_theme_setup()
{
    // Load up our theme options page and related code.
    require(get_template_directory() . '/inc/theme-options.php');

    // This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
    add_theme_support('post-thumbnails');

    // First Image for sliding
    add_image_size('slide-big', 432, 240, true); // Used for large feature (header) images

    // Second, Third Image for sliding
    add_image_size('slide-medium', 208, 240, true); // Used for featured posts if a large-feature doesn't exist

    // Small Thumbnail on News category page
    add_image_size('tiny-thumbnail', 60, 34, true); // Used for featured posts if a large-feature doesn't exist

    // Used for guides posts
    add_image_size('driving-guide', 132, 74, true); // Used for guides posts
    add_image_size('driving-guide-medium', 492, 310, true);
    add_image_size('feature-thumbnail', 132, 74, true);

    // Use in vehicle and review landing page
    add_image_size('216x310', 216, 310, true);
    add_image_size('155x154', 155, 154, true);

    // Use in home page
    add_image_size('276x151', 276, 151, true);
    add_image_size('183x202', 183, 202, true);
    add_image_size('183x100', 183, 100, true);
    add_image_size('204x115', 204, 115, true);

    // Special Feature
    add_image_size('120x68', 120, 78, true);

    // Gallery Image
    add_image_size('556x371', 556, 371, true);
    add_image_size('featured-events', 156, 89, true);

    register_nav_menus(
        array(
            'footer-navigation' => __('Footer Navigation')
        )
    );
}

function wheels_pagination($totalPage, $currentPage)
{
    $template_url = get_template_directory_uri();

    $pagination   = paginate_links(array(
        'base'         => '%_%',
        'format'       => '?page=%#%',
        'total'        => $totalPage,
        'current'      => $currentPage,
        'show_all'     => false,
        'end_size'     => 1,
        'mid_size'     => 5,
        'prev_next'    => True,
        'prev_text'    => '<img alt="Paginate Left" src="' . $template_url . '/img/paginate-left.png">',
        'next_text'    => '<img alt="Paginate Right" src="' . $template_url . '/img/paginate-right.png">',
        'type'         => 'array',
        'add_args'     => false,
        'add_fragment' => ''
    ));

    // Define string to replace
    $generated_markup = array(
        'current',
        'next',
        'prev'
    );

    $replaced_maekup  = array(
        'selected',
        'paginate',
        'paginate'
    );

    // Re-generate pagination link
    // Replace default css class to match wheels template pagination style
    $paging = '';
    $paging .= '<ul>' . "\n";

    if (is_array($pagination)) {

        if ($currentPage == 1) {
            $paging .= '<li><a class="paginate page-numbers" href="?page=1"><img alt="Paginate Left" src="' . get_template_directory_uri() . '/img/paginate-left.png"></a></li>';
        }

        foreach ($pagination as $link) {

            $paging .= '<li>';

            if (strpos($link, 'prev') != 0) {
                $paging .= str_replace($generated_markup, $replaced_maekup, $link);
            } elseif (strpos($link, 'next') != 0) {
                $paging .= str_replace($generated_markup, $replaced_maekup, $link);
            } elseif (strpos($link, 'current') != 0) {
                $page_number = strip_tags($link);
                $paging .= '<a class="selected page-numbers" href="?page=' . $page_number . '">';
                $paging .= $page_number;
                $paging .= '</a>';
            } else {
                $paging .= $link;
            }

            $paging .= '</li>' . "\n";
        }

        if ($currentPage == $totalPage) {
            $paging .= '<li><a class="paginate page-numbers" href="?page=' . $totalPage . '"><img alt="Paginate Right" src="' . get_template_directory_uri() . '/img/paginate-right.png"></a></li>';
        }
    }

    $paging .= '</ul>';
    return $paging;
}

/**
 * Replace Avatar class to match with wheels template
 *
 * @param string $str
 * @return mixed
 */
function wheels_add_avatar_class($str = '')
{
    return str_replace("class='", "class='profile-picture ", $str);
}

/**
 * Return full breadcrumb by generating ourselves or by calling third party plugin function
 *
 * @param bool $echo
 * @return string
 */
function wheels_breadcrumb($echo = true)
{
    global $post;

    $taxonomy  = get_query_var('taxonomy');
    $term      = get_query_var('term');
    $structure = array();

    if ((is_post_type_archive() && get_post_type() == 'reviews') || $post->post_type == 'reviews') {
        $structure[] = array(
            'title' => 'Vehicles & Reviews',
            'link'  => '/vehicles-reviews'
        );
    }

    if ((is_post_type_archive() && get_post_type() == 'events') || $post->post_type == 'events') {
        $structure[] = array(
            'title' => 'News & Features',
            'link'  => '/news/'
        );
    }

    if (basename($_SERVER['REQUEST_URI']) == 'vehicles') {
        $structure[] = array(
            'title' => 'Vehicles & Reviews',
            'link'  => '/vehicles-reviews/'
        );
    }

    if (!empty($taxonomy) && !empty($term)) {

        if ($taxonomy == 'news-category') {
            $post_type  = 'news';
            $post_title = 'News & Features';
        } elseif ($taxonomy == 'feature-category') {
            $post_type  = 'news';
            $post_title = 'News & Features';
        } elseif ($taxonomy == 'guides-category' || $taxonomy == 'Guides-category') {
            $post_type  = 'guides';
            $post_title = 'Guides';
        }

        $structure[] = array(
            'title' => $post_title,
            'link'  => get_post_type_archive_link($post_type)
        );

        $term = get_term_by('slug', $term, $taxonomy);

        if ($term != false) {
            if (!is_wp_error($term)) {
                //$structure[] = array('title' => $term->name, 'link' => get_term_link($term, $term->taxonomy));
            }
        }
    }

    if (is_single()) {

        if ($post->post_type == 'news') {
            $post_type  = 'news';
            $post_title = 'News & Features';
            $taxonomy   = 'news-category';
        } elseif ($post->post_type == 'feature') {
            $post_type  = 'news';
            $post_title = 'News & Features';
            $taxonomy   = 'feature-category';
        } elseif ($post->post_type == 'reviews') {
            $post_type  = 'reviews';
            $post_title = 'Review';
            $taxonomy   = 'none';
        } elseif ($post->post_type == 'second-opinion') {
            $post_type  = 'reviews';
            $post_title = 'Review';
            $taxonomy   = 'none';
        } elseif ($post->post_type == 'events') {
            $structure[] = array(
                'title' => 'Events',
                'link'  => '/events/'
            );
        } elseif ($post->post_type == 'guides') {
            $post_type  = 'guides';
            $post_title = 'Guides';
            $taxonomy   = 'Guides-category';
        } else {
            $post_type  = 'news';
            $post_title = 'News & Features';
            $taxonomy   = 'news-category';
        }

        $structure[] = array(
            'title' => $post_title,
            'link'  => get_post_type_archive_link($post_type)
        );

        if($post->post_type == 'events') unset( $structure[count($structure) - 1] );

        $term = get_the_terms($post->ID, $taxonomy);

        if ($term != false) {
            if (!is_wp_error($term)) {
                $term        = current($term);
                $structure[] = array(
                    'title' => $term->name,
                    'link'  => get_term_link($term, $term->taxonomy)
                );
            }
        }
    }

    $breadcrumb = '<div class="row breadcrumb">';

    foreach ($structure as $key => $item) {
        $last = ($key != 0 && ($key + 1) == count($structure)) ? ' class="last"' : false;
        $breadcrumb .= '<a ' . $last . ' href="' . $item['link'] . '">' . $item['title'] . '</a> ';
        if (($key + 1) < count($structure) || $key == 0)
            $breadcrumb .= '&nbsp/&nbsp;';
    }
    $breadcrumb .= ' </div>';

    if ($echo) {
        echo $breadcrumb;
    } else {
        return $breadcrumb;
    }
}

// --------------------- COMMENT RELATED FUNCTIONS ---------------------

function wheels_comment($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    $is_depth = ($depth > 1) ? true : false;

    if ($comment->comment_type != 'pingback' || $comment->comment_type != 'trackback') {
        $class = ($is_depth) ? 'reply' : 'answer';
        require __DIR__ . '/partials/comment_view.php';
    }
}

/*
 * Update post popularity value when user posts a comment
 */
function wheels_comment_saved($comment_id)
{
    global $wpdb;

    $post_id     = esc_attr($_POST['comment_post_ID']);
    $user_rating = esc_attr($_POST['user-rating']);
    $owned       = esc_attr($_POST['owned']);
    $post_type   = get_post_type($post_id);

    if (in_array($user_rating, array(0, 1, 2, 3, 4, 5))) {
        $user_rating = $user_rating . '.0';
    }

    update_comment_meta($comment_id, 'comment_rating', $user_rating);

    $popularityPlugin = new \Emicro\Plugin\Popularity($wpdb);
    $popularityPlugin->update($post_id);
    $popularityPlugin->updateUserRating($post_id);
    $popularityPlugin->comment($comment_id);

    $urls = array(
        'comment-count'                => get_template_directory_uri() . "/esi/comment-count.php?post_id={$post_id}",
        'readers-thought-taxonomy-page' => get_template_directory_uri() . '/esi/readers-thoughts.php?post_type=' . $post_type
    );

    if ($post_type == 'reviews') {
        $urls['vehicle-profile-top-comment']         = get_template_directory_uri() . "/esi/vehicles/vehicle-profile-top-comment.php?post_id=" . $post_id;
        $urls['landing-vehicles-readers-thought']    = get_template_directory_uri() . "/esi/vehicles/landing-vehicles-readers-thought.php";
        $urls['user-rating']                         = get_template_directory_uri() . '/esi/reviews/user_rating.php?post_id=' . $post_id;
        $urls['review-landing-page-popular-caresol'] = get_template_directory_uri() . '/esi/reviews/archive-reviews-carousel.php';
        $urls['landing-vehicles-reviews-popular-ve'] = get_template_directory_uri() . '/esi/vehicles/landing-vehicles-reviews-popular-vehicles.php';
        $urls['landing-reviews-readers-though']      = get_template_directory_uri() . '/esi/reviews/landing-readers-though.php';
    }

    \Emicro\Plugin\Varnish::purgeAll($urls);
}

function wheels_comment_approval($commentID)
{
    global $wpdb;

    $post = $wpdb->get_row("SELECT wp_posts.ID, wp_posts.post_type
                            FROM wp_comments
                            INNER JOIN wp_posts ON wp_comments.comment_post_ID = wp_posts.ID
                            WHERE wp_comments.comment_ID = '{$commentID}'");

    $post_id     = $post->ID;
    $post_type   = get_post_type($post_id);

    $popularityPlugin = new \Emicro\Plugin\Popularity($wpdb);
    $popularityPlugin->update($post_id);
    $popularityPlugin->updateUserRating($post_id);
    $popularityPlugin->comment($commentID);

    $urls = array(
        'comment-count'                => get_template_directory_uri() . "/esi/comment-count.php?post_id={$post_id}",
        'readers-thought-taxonomy-page' => get_template_directory_uri() . '/esi/readers-thoughts.php?post_type=' . $post_type
    );

    if ($post_type == 'reviews') {
        $urls['vehicle-profile-top-comment']         = get_template_directory_uri() . "/esi/vehicles/vehicle-profile-top-comment.php?post_id=" . $post_id;
        $urls['landing-vehicles-readers-thought']    = get_template_directory_uri() . "/esi/vehicles/landing-vehicles-readers-thought.php";
        $urls['user-rating']                         = get_template_directory_uri() . '/esi/reviews/user_rating.php?post_id=' . $post_id;
        $urls['review-landing-page-popular-caresol'] = get_template_directory_uri() . '/esi/reviews/archive-reviews-carousel.php';
        $urls['landing-vehicles-reviews-popular-ve'] = get_template_directory_uri().'/esi/vehicles/landing-vehicles-reviews-popular-vehicles.php';
        $urls['landing-reviews-readers-though']      = get_template_directory_uri() . '/esi/reviews/landing-readers-though.php';
    }
    
    \Emicro\Plugin\Varnish::purgeAll($urls);
}

/**
 * Outputs a complete commenting form for use within a template.
 *
 * @param array $args    Options for strings, fields etc in the form
 * @param mixed $post_id Post ID to generate the form for, uses the current post if null
 *
 * @return void
 */
function wheel_comment_form($args = array(), $post_id = null)
{
    global $id, $rating, $redirectURL;

    if (null === $post_id) {
        $post_id = $id;
    } else {
        $id = $post_id;
    }

    $commenter     = wp_get_current_commenter();
    $user          = wp_get_current_user();
    $user_identity = !empty($user->ID) ? $user->display_name : '';

    $req      = get_option('require_name_email');
    $aria_req = ($req ? " aria-required='true'" : '');

    $fields   = array(
        'author' => '<p class="comment-form-author">' . '<label for="author">' . __('Name') . '</label> ' . ($req ? '<span class="required">*</span>' : '') .
                    '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>',
        'email'  => '<p class="comment-form-email"><label for="email">' . __('Email') . '</label> ' . ($req ? '<span class="required">*</span>' : '') .
                    '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></p>',
        'url'    => '<p class="comment-form-url"><label for="url">' . __('Website') . '</label>' .
                    '<input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>',
    );

    $required_text = sprintf(' ' . __('Required fields are marked %s'), '<span class="required">*</span>');

    $defaults      = array(
        'fields'               => apply_filters('comment_form_default_fields', $fields),
        'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . _x('Comment', 'noun') . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
        'must_log_in'          => '<p class="must-log-in">' . sprintf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url(apply_filters('the_permalink', get_permalink($post_id)))) . '</p>',
        'logged_in_as'         => '<p class="logged-in-as">' . sprintf(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink($post_id)))) . '</p>',
        'comment_notes_before' => '<p class="comment-notes">' . __('Your email address will not be published.') . ($req ? $required_text : '') . '</p>',
        'comment_notes_after'  => '<p class="form-allowed-tags">' . sprintf(__('You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s'), ' <code>' . allowed_tags() . '</code>') . '</p>',
        'id_form'              => 'commentform',
        'id_submit'            => 'submit',
        'title_reply'          => __('Leave a Reply'),
        'title_reply_to'       => __('Leave a Reply to %s'),
        'cancel_reply_link'    => __('Cancel reply'),
        'label_submit'         => __('Post Comment'),
    );

    $args = wp_parse_args($args, apply_filters('comment_form_defaults', $defaults));

    if (comments_open($post_id)) {
        do_action('comment_form_before');
        require __DIR__ . '/partials/comment_form.php';
        do_action('comment_form_after');
    } else {
        do_action('comment_form_comments_closed');
    }
}

/**
 * Source: http://dimox.net/wordpress-comments-pagination-without-a-plugin/
 *
 * @param bool $echo
 * @param int  $mid_size
 * @param int  $end_size
 */
function wheels_comments_paging($echo = true, $mid_size = 3, $end_size = 1)
{
    $pages = '';
    $max   = get_comment_pages_count();
    $page  = get_query_var('cpage');

    if (gettype($page) != 'string') {
        $page = 1;
    }

    if (!$page) {
        $page = 1;
    }

    $a['current'] = $page;
    $a['echo']    = false;

    $total          = 0;

    $a['mid_size']  = $mid_size;
    $a['end_size']  = $end_size;
    $a['prev_text'] = '<img src="' . get_template_directory_uri() . '/img/paginate-left.png" alt="Paginate Left"/>';
    $a['next_text'] = '<img src="' . get_template_directory_uri() . '/img/paginate-right.png" alt="Paginate Left"/>';
    $a['type']      = 'array';

    //if ($total == 1 && $max > 1) $pages = '<li><a href=""> ' . $page . ' of ' . $max . '</a></li>'."\r\n";
    //echo '<li>' . paginate_comments_links($a) . '</li>';
    //if ($max > 1) echo '</ul>';

    $output = '';

    if ($max > 1) {
        $output .= '<ul>';
    }

    if (is_array(paginate_comments_links($a))) {
        foreach (paginate_comments_links($a) as $paging) {
            $replaced = str_replace(
                array(
                    '<span class=\'page-numbers current\'>',
                    '</span>',
                    '<a class="prev page-numbers"',
                    '<a class="next page-numbers"'
                ),
                array(
                    '<a href="" class="selected">',
                    '</a>',
                    '<a href="" class="paginate"',
                    '<a href="" class="paginate"'
                ),
                $paging
            );
            $output .= '<li>' . $replaced . '</li>';
        }
    }

    if ($max > 1) {
        $output .= '</ul>';
    }

    echo $output;
}

function isMobilePage()
{
    if (is_home()){
        return true;
    }

    if ( is_single() & in_array(get_post_type(), array('news', 'reviews', 'feature')) )
    {
        return true;
    }

    list ($empty, $page_name, $vehicle_id) = explode('/', $_SERVER['REQUEST_URI']);
    if ( $page_name == 'vehicles' && !empty($vehicle_id) )
    {
        return true;
    }

    return false;
}

// --------------------- MASS CONTENT CREATION FUNCTIONS ---------------------

function mass_post($int = 0)
{
    $post_data = array(
        'post_title'     => "Test News Post - " . $int,
        'post_content'   => str_repeat(" Test news Content - " . $int, 5),
        'post_status'    => 'publish',
        'post_author'    => 1,
        'post_type'      => 'news',
        'comment_status' => 'closed',
        'post_status'    => 'publish', // 'draft' | 'publish' | 'pending'| 'future',
        'tax_input'      => array('news-category' => array(3)) // support for custom
    );

    $post_id = wp_insert_post($post_data);
    update_post_meta($post_id, '_thumbnail_id', 28);
}

function mass_comment($int = 0, $post_id, $child = 0)
{
    $data      = array(
        'comment_post_ID'      => $post_id,
        'comment_author'       => 'admin',
        'comment_author_email' => 'parser@pul-email.com',
        'comment_content'      => 'Test comment - ' . $int,
        'comment_type'         => '',
        'comment_parent'       => 0,
        'user_id'              => 1,
        'comment_author_IP'    => '127.0.0.1',
        'comment_agent'        => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
        'comment_approved'     => 1,
    );

    $commentID = wp_insert_comment($data);
    update_comment_meta($commentID, 'comment_popularity', rand(10, 30));

    $r = array('1.0', '1.5', '2.0', '2.5', '3.0', '3.5', '4.0', '4.5', '5.0');
    update_comment_meta($commentID, 'comment_rating', $r[rand(0, 8)]);

    if ($child) {
        for ($j = 0; $j < $child; $j++) {
            $data = array(
                'comment_post_ID'      => $post_id,
                'comment_author'       => 'admin',
                'comment_author_email' => 'parser@pul-email.com',
                'comment_content'      => str_repeat(' Test Sub comment - ' . $j, 5),
                'comment_type'         => '',
                'comment_parent'       => $commentID,
                'user_id'              => 1,
                'comment_author_IP'    => '127.0.0.1',
                'comment_agent'        => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
                'comment_approved'     => 1,
            );
            wp_insert_comment($data);
        }
    }
}