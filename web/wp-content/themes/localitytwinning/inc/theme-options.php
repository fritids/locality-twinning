<?php
use \Emicro\Plugin\Varnish;
// Clear varnish cacah if update option
if(basename($_SERVER['PHP_SELF']) == 'themes.php' && $_GET['settings-updated'] == 'true')
{
    $urls = array(
        'road-test' => get_template_directory_uri().'/esi/road-tests.php',
        'special' => get_template_directory_uri().'/esi/special.php',
        'poll' => get_template_directory_uri().'/esi/poll.php',
        'contest-poll' => get_template_directory_uri().'/esi/contest-poll.php',
        'footer-navigation' => get_template_directory_uri().'/esi/footer/footer-navigation.php',
        'header-guides-category' => get_template_directory_uri().'/esi/header/guides-category.php',
        'header-news-feature-category' => get_template_directory_uri().'/esi/header/news-feature-category.php',
        'event-landing-page' => site_url().'/events/'
    );
    Varnish::purgeAll($urls);
}

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function theme_options_init(){
    register_setting( 'wheels_options', 'wheels_theme_options', 'theme_options_validate' );
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
    add_theme_page( __( 'Wheels Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', 'theme_options', 'theme_options_do_page' );
}

/**
 * Create the options page
 */
function theme_options_do_page() {
    global $select_options, $radio_options;

    if ( ! isset( $_REQUEST['updated'] ) )
        $_REQUEST['updated'] = false;

    ?>
<div class="wrap">
    <?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options' ) . "</h2>"; ?>

    <?php if ( false !== $_REQUEST['updated'] ) : ?>
        <div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
    <?php endif; ?>

    <form method="post" action="options.php">
        <?php settings_fields( 'wheels_options' ); ?>
        <?php $options = get_option( 'wheels_theme_options' ); ?>

        <table class="form-table">

            <tr valign="top"><th scope="row"><?php _e( 'Facebook URL' ); ?></th>
                <td>
                    <input id="wheels_theme_options[facebook_url]" name="wheels_theme_options[facebook_url]" type="text" size="50" value="<?php esc_attr_e( $options['facebook_url'] ); ?>" />
                    <label class="description" for="wheels_theme_options[facebook_url]"><?php _e( 'Enter Facebook URL' ); ?></label>
                </td>
            </tr>

            <tr valign="top"><th scope="row"><?php _e( 'Twitter URL' ); ?></th>
                <td>
                    <input id="wheels_theme_options[twitter_url]" name="wheels_theme_options[twitter_url]" type="text" size="50" value="<?php esc_attr_e( $options['twitter_url'] ); ?>" />
                    <label class="description" for="wheels_theme_options[twitter_url]"><?php _e( 'Enter Twitter URL' ); ?></label>
                </td>
            </tr>

            <tr valign="top"><th scope="row"><?php _e( 'Youtube URL' ); ?></th>
                <td>
                    <input id="wheels_theme_options[youtube_url]" name="wheels_theme_options[youtube_url]" type="text" size="50" value="<?php esc_attr_e( $options['youtube_url'] ); ?>" />
                    <label class="description" for="wheels_theme_options[youtube_url]"><?php _e( 'Enter Youtube URL' ); ?></label>
                </td>
            </tr>

            <tr valign="top"><th scope="row"><?php _e( 'Road Test Category' ); ?></th>
                <td>
                    <?php $selected = $options['road_test_term'];
                    wp_dropdown_categories("selected=$selected&name=wheels_theme_options[road_test_term]&hierarchical=1&taxonomy=feature-category&show_count=1"); ?>
                    <label class="description" for="wheels_theme_options[road_test_term]"><?php _e( 'Select a category for road test' ); ?></label>
                </td>
            </tr>

            <tr valign="top"><th scope="row"><?php _e( 'Special Feature Category' ); ?></th>
                <td>
                    <?php $selected = $options['special_feature_term'];
                    wp_dropdown_categories("selected=$selected&name=wheels_theme_options[special_feature_term]&hierarchical=1&taxonomy=feature-category&show_count=1"); ?>
                    <label class="description" for="wheels_theme_options[special_feature_term]"><?php _e( 'Select a category for Special Feature' ); ?></label>
                </td>
            </tr>

            <tr valign="top"><th scope="row"><?php _e( 'PollDaddy' ); ?></th>
                <td>
                    <textarea name="wheels_theme_options[polldaddy]" id="wheels_theme_options[polldaddy]" style="width: 600px; height: 200px"><?php echo $options['polldaddy']; ?></textarea><br>
                    <label class="description" for="wheels_theme_options[polldaddy]"><?php _e( 'Insert or Update poll daddy\'s Javascirpt code' ); ?></label>
                </td>
            </tr>

        </table>

        <h3>Twitter has name for event page</h3>
        <table class="form-table">

            <tr valign="top"><th scope="row"><?php _e( 'Tag 1' ); ?></th>
                <td>
                    <input id="wheels_theme_options[event_twitter_tag_1]" name="wheels_theme_options[event_twitter_tag_1]" type="text" size="20" value="<?php esc_attr_e( $options['event_twitter_tag_1'] ); ?>" />
                    <label class="description" for="wheels_theme_options[event_twitter_tag_1]"></label>
                </td>
            </tr>

            <tr valign="top"><th scope="row"><?php _e( 'Tag 2' ); ?></th>
                <td>
                    <input id="wheels_theme_options[event_twitter_tag_2]" name="wheels_theme_options[event_twitter_tag_2]" type="text" size="20" value="<?php esc_attr_e( $options['event_twitter_tag_2'] ); ?>" />
                    <label class="description" for="wheels_theme_options[event_twitter_tag_2]"></label>
                </td>
            </tr>

        </table>

        <h3>Contest</h3>
        <table class="form-table">

            <tr valign="top"><th scope="row"><?php _e( 'Show Contest' ); ?></th>
                <td>
                    <input id="wheels_theme_options[show_contest]" name="wheels_theme_options[show_contest]" type="checkbox" value="1" <?php echo ($options['show_contest'])?'checked="checked"':''; ?> />
                    <label class="description" for="wheels_theme_options[show_contest]"><?php _e( 'Show Contest at Frontend' ); ?></label>
                </td>
            </tr>

            <tr valign="top"><th scope="row"><?php _e( 'Contest Image URL' ); ?></th>
                <td>
                    <input id="wheels_theme_options[contest_image_url]" name="wheels_theme_options[contest_image_url]" type="text" size="50" value="<?php esc_attr_e( $options['contest_image_url'] ); ?>" />
                    <label class="description" for="wheels_theme_options[contest_image_url]"><?php _e( 'Enter Contest Image URL, (Dimension: 484X274)' ); ?></label>
                </td>
            </tr>

            <tr valign="top"><th scope="row"><?php _e( 'Contest Title' ); ?></th>
                <td>
                    <input id="wheels_theme_options[contest_title]" name="wheels_theme_options[contest_title]" type="text" size="50" value="<?php esc_attr_e( $options['contest_title'] ); ?>" />
                    <label class="description" for="wheels_theme_options[contest_title]"><?php _e( 'Enter Contest Title' ); ?></label>
                </td>
            </tr>

            <tr valign="top"><th scope="row"><?php _e( 'Contest More Link' ); ?></th>
                <td>
                    <input id="wheels_theme_options[contest_more_link]" name="wheels_theme_options[contest_more_link]" type="text" size="50" value="<?php esc_attr_e( $options['contest_more_link'] ); ?>" />
                    <label class="description" for="wheels_theme_options[contest_more_link]"><?php _e( 'Enter Contest More Link' ); ?></label>
                </td>
            </tr>

        </table>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" />
        </p>
    </form>
</div>

<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function theme_options_validate( $input ) {
    global $select_options, $radio_options;

    $input['sometextarea'] = wp_filter_post_kses( $input['sometextarea'] );
    return $input;
}