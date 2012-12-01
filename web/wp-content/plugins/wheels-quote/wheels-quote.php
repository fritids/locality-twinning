<?php
/*
Plugin Name: Quote
Plugin URI: http://emicrograph.com
Description: Custom Quote plugin for Wheels.ca.
Author: Samiul Amin
Version: 1.0
Author URI: http://emicrograph.com
*/

define('WHEELS_QUOTE_REF_TABLE', 'wheels_custom_data');

register_activation_hook( __FILE__, 'wheels_quote_install' );
register_deactivation_hook( __FILE__, 'wheels_quote_uninstall' );

add_action( 'add_meta_boxes', 'wheels_quote_meta_box' );
add_action( 'save_post', 'wheels_quote_post_submit' );

function wheels_quote_install(){
    include('wheels-quote-installation.php');
    wheels_quote_create_table();
}

function wheels_quote_uninstall(){
    include('wheels-quote-installation.php');
    wheels_quote_drop_table();
}

function wheels_quote_meta_box(){
    $post_types = get_option(WHEELS_QUOTE_SETTINGS_FIELD);
    if(!is_array($post_types)) $post_types = array();
    foreach($post_types as $post_type){
        add_meta_box(
            'wheels_quote_meta_box_id',
            __( 'Quotation' ),
            'wheels_quote_meta_box_content',
            $post_type
        );
    }
}

function wheels_quote_meta_box_content( $post ) {
    global $wpdb;
    $post_id = $post->ID;
    $quote_ref_table = $wpdb->prefix . WHEELS_QUOTE_REF_TABLE;
    $quote = $wpdb->get_var( $wpdb->prepare("SELECT quote FROM $quote_ref_table WHERE post_id = '$post_id'") );

    wp_nonce_field( plugin_basename( __FILE__ ), 'wheels_quote_metabox_noncename' );
    echo '<textarea rows="3" name="post_quote" style="width:100%">'.$quote.'</textarea>';
}

function wheels_quote_post_submit( $post_id  ){
    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times

    if ( !wp_verify_nonce( $_POST['wheels_quote_metabox_noncename'], plugin_basename( __FILE__ ) ) )
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

    $post_quote = $_POST['post_quote'];

    //if(!empty($post_quote)){
        global $wpdb;
        $quote_ref_table = $wpdb->prefix . WHEELS_QUOTE_REF_TABLE;

        $ref_id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $quote_ref_table WHERE post_id = %d", $post_id) );

        if($ref_id){
            //$wpdb->query( $wpdb->prepare( "INSERT INTO FROM $vehicle_ref_table WHERE post_id = %d", $post_id ) );
            $wpdb->update($quote_ref_table, array('quote' => $post_quote), array('post_id' => $post_id), array('%s'), array('%d') );
        }else{
            $wpdb->insert($quote_ref_table, array('post_id' => $post_id, 'quote' => $post_quote), array('%d', '%s'));
        }
        //exit( $wpdb->last_query );
    //}
}

// Define setting field name
define('WHEELS_QUOTE_SETTINGS_FIELD', 'wheels-quote-postypes');

/*
 * Add a setting page under wordpress Settings menu
 */
add_action('admin_menu', function(){
    //edit.php?post_type=news
    add_submenu_page('options-general.php', 'Wheels Quote Settings', 'Wheels Quote Settings', 'manage_options', 'wheels-quote-settings-page', 'wheels_quote_settings_page');
});

/*
 * Setting page callback function
 */
function wheels_quote_settings_page(){
    // If user submit setting form
    if($_POST['Submit']){
        // Update setting value
        update_option(WHEELS_QUOTE_SETTINGS_FIELD, $_POST[WHEELS_QUOTE_SETTINGS_FIELD]);
        // Set $update = true to show Notification message
        $update = true;//var_dump(get_option(WHEELS_QUOTE_SETTINGS_FIELD));
    }
    wp_nonce_field('update-options');
    ?>
<div class="wrap">
    <div class="icon32" id="icon-options-general"></div>
    <h2>Show in Post Types:</h2>
    <?php
    // If $update retun true, show successfull message
    if($update){
        echo '<div class="updated below-h2" id="message"><p>Settings saved.</a></div>';
    }
    ?>
    <form action="" method="post">
        <?php
        // Get saved settings value
        $setting_post_types = get_option(WHEELS_QUOTE_SETTINGS_FIELD);

        // If settings value not exits (not array), define an empty array
        if(!is_array($setting_post_types)) $setting_post_types = array();

        // define post type which we do not want to show
        $exclude = array('attachment','','revision','nav_menu_item');

        // Get all wordpress registered post types
        $post_types = get_post_types();
        foreach($post_types as $post_type){
            // if current post type is not include in our exclude post type
            if(!in_array($post_type, $exclude)){
                // if current post type match with save settings post type value, make it checked
                $checked = (in_array($post_type, $setting_post_types)) ? ' checked="checked"' : '';?>
                <p>
                    <input type="checkbox" name="<?php echo WHEELS_QUOTE_SETTINGS_FIELD?>[]" value="<?php echo $post_type?>"<?php echo $checked?> /> <?php echo ucfirst($post_type)?>
                </p>
                <?php
            }
        }
        ?>
        <p class="submit">
            <input name="Submit" type="submit" class="button-primary"
                   value="<?php esc_attr_e('Save Changes'); ?>"/>
        </p>

    </form>
</div><!-- wrap -->
<?php
}
?>