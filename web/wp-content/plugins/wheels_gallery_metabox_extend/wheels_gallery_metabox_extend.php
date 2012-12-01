<?php
/*
Plugin Name: Gallery Metabox Extend
Author: Samiul Amin
*/

/*
 * TODO
 * 1. Access restriction
 * 2. Replace message show instead alert message
 * 3. Link, Button style to match Wordpress theme
 * 4. Image resize
 * 5. Re-orginize code
 * 6. Create option page
 */

define('WHEELS_GALLEY_METABOX_EXTEND_TABLE', 'wheels_gallery');

// Define setting field name
define('WHEELS_GALLERY_METABOX_SETTINGS_FIELD', 'wheels-gallery-metabox-extend-postypes');

register_activation_hook( __FILE__, 'wheels_gallery_metabox_extend_install' );
register_deactivation_hook( __FILE__, 'wheels_gallery_metabox_extend_uninstall' );

function wheels_gallery_metabox_extend_install(){
    include('wheels-gallery-metabox-extend-installation.php');
    wheels_gallery_metabox_extend_create_table();
}

function wheels_gallery_metabox_extend_uninstall(){
    include('wheels-gallery-metabox-extend-installation.php');
    wheels_gallery_metabox_extend_drop_table();
}

/* Define the custom box */
add_action( 'admin_enqueue_scripts', 'wheels_gallery_metabox_extend_enqueue' );
/* Doen't need this function anymore
 */
function wheels_gallery_metabox_extend_enqueue() {
    //if(!($condition_to_check_your_page))// adjust this if-condition according to your theme/plugin
    //    return;

    wp_enqueue_script('plupload-all');

    wp_register_script('wheels-gallery-metabox-extend-js', plugins_url('js.js', __FILE__), array('jquery'));
    wp_enqueue_script('wheels-gallery-metabox-extend-js');

    wp_register_style('wheels-gallery-metabox-extend-css', plugins_url('css.css', __FILE__));
    wp_enqueue_style('wheels-gallery-metabox-extend-css');
}

add_action("admin_head", "wheels_gallery_metabox_extend_admin_head");
/* Doen't need this function anymore
 */
function wheels_gallery_metabox_extend_admin_head() {

}

add_action('wp_ajax_plupload_action', "wheels_gallery_metabox_extend_upload_action");
function wheels_gallery_metabox_extend_upload_action() {

    // getting tantanS3 option
    $s3Options = get_option('tantan_wordpress_s3');
    // get bucket name
    $bucket = $s3Options['bucket'];


    if( !current_user_can('upload_files') ) die('Access denied');
    // check ajax noonce
    $imgid = $_POST["imgid"];
    $postId = $_POST['post_id'];
    check_ajax_referer($imgid . 'pluploadan');

    // handle file upload
    $status = wp_handle_upload($_FILES[$imgid . 'async-upload'], array('test_form' => true, 'action' => 'plupload_action'));

    // Integrate with S3
    $override['action'] = 'editpost';

    // Upload File
    $uploaded_file = $status;//wp_handle_upload($file, $override);
    $attachment = array(
        'post_title' => $uploaded_file['name'],
        'post_content' => '',
        'post_type' => 'attachment',
        'post_parent' => $postId,
        'post_mime_type' => $uploaded_file['type'],
        'guid' => $uploaded_file['url']
    );

    // Create an Attachment in WordPress
    $id = wp_insert_attachment( $attachment,$uploaded_file[ 'file' ], $postId );
    wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $uploaded_file['file'] ) );

    //Remove it from the array to avoid duplicating during autosave/revisions.
    #unset($_FILES[$imgid . 'async-upload']);

    // Change URL
    if( isset( $bucket ) )
    {
        $status['url'] = str_replace(
            $_SERVER['HTTP_HOST'],
            's3.amazonaws.com/'.$bucket, $status['url']
        );
    }

    global $wpdb;
    $table = $wpdb->prefix . WHEELS_GALLEY_METABOX_EXTEND_TABLE;
    $data = array(
        'post_id' => $postId,
        'file' => $id,
        'url' => $status['url'],
        'type' => $status['type']
    );
    $wpdb->insert($table, $data, array('%d', '%s', '%s', '%s'));
    $status['id'] = $wpdb->insert_id;
    $status['post_id'] = $_POST['post_id'];
    // send the uploaded file url in response
    echo json_encode($status);
    //echo $status['url'];
    exit;
}

add_action( 'add_meta_boxes', 'wheels_gallery_metabox_extend_metabox' );
function wheels_gallery_metabox_extend_metabox() {
    $post_types = get_option(WHEELS_GALLERY_METABOX_SETTINGS_FIELD);
    if(!is_array($post_types)) $post_types = array();
    foreach($post_types as $post_type){
        add_meta_box(
            'wheels_gallery_metabox_extend_metabox_id',
            __( 'Gallery Manager' ),
            'wheels_gallery_metabox_extend_metabox_content',
            $post_type
        );
    }
}

function wheels_gallery_metabox_extend_metabox_content( $post ) {
    global $wpdb;
    $table = $wpdb->prefix . WHEELS_GALLEY_METABOX_EXTEND_TABLE;
    $post_id = $post->ID;
    $results = $wpdb->get_results("SELECT * FROM $table WHERE post_id = '$post_id' ORDER BY weight ASC");
    echo '<a onclick="return false;" title="Add Media" id="content-add_media" class="thickbox" href="'.plugins_url('wheels_gallery_metabox_extend_list.php?post_id='.$post->ID.'&amp;TB_iframe=1&amp;width=640&amp;height=214', __FILE__).'">Open Gallery Maneger</a>';
    echo '<br /><br />';
    echo '<div id="wheels-gallery-container">';
    foreach($results as $row){
        echo '<img width="50" src="'.$row->url.'">';
    }
    echo '</div>';
}


/**
 * Wheels Get Gallery Assets
 * Return gallery info of a post
 * @param int $post_id
 * @return array|mixed
 */
function wheels_get_gallery_assets($post_id = 0){
    if($post_id == 0) return array();
    global $wpdb;

    // Getting gallery image from custom table
    $table = $wpdb->prefix . WHEELS_GALLEY_METABOX_EXTEND_TABLE;
    $results = $wpdb->get_results("SELECT * FROM $table WHERE post_id = '".$post_id."' ORDER BY weight ASC");
    $imageFound = $wpdb->num_rows;
    $searchPath = 'wp-content/uploads';

    $matchImages = array();
    foreach($results as $row)
    {
        $pos = strpos($row->url, $searchPath);
        $matchImages[] = substr($row->url, $pos+strlen($searchPath));
    }

    $sqlWhere = '';
    foreach($matchImages as $key => $matchImage)
    {
        $sqlWhere .= ($key > 0) ? ' OR ' : '';
        $sqlWhere .= " guid LIKE '%$matchImage' ";
    }

    // Getting wordpress attached images
    $sql = "SELECT ID, guid FROM wp_posts WHERE " . $sqlWhere;
    $attachments = $wpdb->get_results($sql);

    if($imageFound == 0)
    {
        $defaultImage = true;
        $post_thumbnail_id = get_post_thumbnail_id( $post_id );
        if($post_thumbnail_id)
        {

            $img = wp_get_attachment_image_src( $post_thumbnail_id, array(556, 371));
            if(isset($img[0]))
            {
                $data = new stdClass();
                $data->title = '';
                $data->url = $img[0];
                $results = array($data);
                $defaultImage = false;
            }

        }

        if($defaultImage){
            $data = new stdClass();
            $data->title = '';
            $data->url = get_template_directory_uri().'/img/gallery-default-image.jpg';
            $results = array($data);
        }
    }else{
        // We take small image size if wordpress attached image has more then from custom gallery table
        if( count($attachments) ){
            $newResults = array();
            foreach ( $results as $key => $row ) {
                $img = wp_get_attachment_image_src( $attachments[$key]->ID, array(556, 371));
                $data = $row;
                if(isset($img[0]))
                {
                    $data->url = $img[0];
                }
                $newResults[] = $data;
            }
            $results = $newResults;
        }
    }
    return $results;
}

/*
 * Add a setting page under wordpress Settings menu
 */
add_action('admin_menu', function(){
    //edit.php?post_type=news
    add_submenu_page('options-general.php', 'Wheels Gallery Settings', 'Wheels Gallery Settings', 'manage_options', 'wheels-gallery-metabox-settings-page', 'wheels_gallery_metabox_settings_page');
});

/*
 * Setting page callback function
 */
function wheels_gallery_metabox_settings_page(){
    // If user submit setting form
    if($_POST['Submit']){
        // Update setting value
        update_option(WHEELS_GALLERY_METABOX_SETTINGS_FIELD, $_POST[WHEELS_GALLERY_METABOX_SETTINGS_FIELD]);
        // Set $update = true to show Notification message
        $update = true;//var_dump(get_option(WHEELS_GALLERY_METABOX_SETTINGS_FIELD));
    }
    wp_nonce_field('update-options');
    ?>
<div class="wrap">
    <div class="icon32" id="icon-options-general"></div>
    <h2>Vehicle Browser meta box show in Post Types:</h2>
    <?php
    // If $update retun true, show successfull message
    if($update){
        echo '<div class="updated below-h2" id="message"><p>Settings saved.</a></div>';
    }
    ?>
    <form action="" method="post">
        <?php
        // Get saved settings value
        $setting_post_types = get_option(WHEELS_GALLERY_METABOX_SETTINGS_FIELD);

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
                    <input type="checkbox" name="<?php echo WHEELS_GALLERY_METABOX_SETTINGS_FIELD?>[]" value="<?php echo $post_type?>"<?php echo $checked?> /> <?php echo ucfirst($post_type)?>
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