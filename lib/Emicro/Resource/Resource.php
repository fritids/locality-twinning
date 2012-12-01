<?php
/**
 * Resource Base Class
 *
 * @category		Model
 * @author          Md. Sirajus Salayhin <salayhin@gmail.com>
 * @copyright		Right Brain Solution Ltd. <http://www.rightbrainsolution.com>
 */


namespace Emicro\Resource;
include_once WP_CONTENT_DIR . '/bootstrap.php';
include_once $_SERVER['DOCUMENT_ROOT'] . "/wp-load.php";

class Resource
{
    const WHEELS_QUOTE_REF_TABLE = 'wheels_custom_data';

    public function add($data, $files)
    {
        $authorEmail = $data['author_email'];
        $authorID = $this->getAuthorId($authorEmail);
        $postID = $this->createReview($data, $authorID);
        $featuredImage = $this->attachFeaturedImage($postID, $files['featured_image'], $data['featured_image_caption']);
        $this->uploadGalleryImages($files['gallery_images'], $postID);
    }

private function getAuthorId($authorEmail)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $sql = $wpdb->prepare("SELECT * FROM {$prefix}users WHERE user_email = '$authorEmail' ");
        $user_meta = $wpdb->get_row($sql);

        if(count(($user_meta)))
            return $user_meta->ID;
        else
            return 1;

    }


private function attachFeaturedImage($postID, $files, $caption)
    {

        if (!current_user_can('upload_files')) die('Access denied');
        // check ajax noonce

        require_once(ABSPATH . 'wp-admin/includes/file.php');
        //foreach ($files as $key => $value) {

            // handle file upload -
            $_POST['action'] = 'wp_handle_upload';
            $status = wp_handle_upload($files, array('test_form' => false, 'action' => ' wp_handle_upload'));

            $filename = $status['file'];

            $wp_filetype = wp_check_filetype(basename($filename), null);
            $wp_upload_dir = wp_upload_dir();
            $attachment = array(
                'guid' => $wp_upload_dir['baseurl'] . _wp_relative_upload_path($filename),
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => $caption,
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment($attachment, $filename, $postID);

            // you must first include the image.php fi  le
            // for the function wp_generate_attachment_metadata() to work
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
            $status['attach_data'] = $attach_data;
            wp_update_attachment_metadata($attach_id, $attach_data);

            update_post_meta($postID, '_thumbnail_id', $attach_id);

        //}

    }

    private function uploadGalleryImages($files, $newsId)
    {

        if (!current_user_can('upload_files')) die('Access denied');
        // check ajax noonce

        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $key = 0;
        global $wpdb;
        foreach ($files as $value) {
            $file = array(
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error' => $files['error'][$key],
                'size' => $files['size'][$key]
            );


            // handle file upload -
            $_POST['action'] = 'wp_handle_upload';
            $status = wp_handle_upload($file, array('test_form' => false, 'action' => ' wp_handle_upload'));

            $table = $wpdb->prefix . 'wheels_gallery';
            $data = array(
                'post_id' => $newsId,
                'file' => $status['file'],
                'url' => $status['url'],
                'type' => $status['type'],
                'title'=>$_POST['gallery_images_caption'][$key]
            );
            $wpdb->insert($table, $data, array('%d', '%s', '%s', '%s','%s'));

            $key++;
        }
    }

}
