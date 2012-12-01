<?php

/**
 * News Web Service
 *
 * @category  Model
 * @author    Md. Sirajus Salayhin <salayhin@gmail.com>
 * @copyright Right Brain Solution Ltd. <http://www.rightbrainsolution.com>
 */

namespace Emicro\Resource;

include_once WP_CONTENT_DIR . '/bootstrap.php';
include_once $_SERVER['DOCUMENT_ROOT'] . "/wp-load.php";

class News
{
    const WHEELS_QUOTE_REF_TABLE = 'wheels_custom_data';
    const WHEELS_NEWS_GET_API_REF_TABLE = 'wheels_news_get_post_reference';

    public function add($data, $files)
    {
        $authorEmail = $data['author_email'];
        $authorID = $this->getAuthorId($authorEmail);

        $postID = $this->createNews($data, $authorID);

        if (!empty($files['featured_image'])) {
            $this->attachFeaturedImage($postID, $files['featured_image'], $data['featured_image_caption'],$authorID);
        }

        if (!empty($files['gallery_images'])) {
            $this->uploadGalleryImages($files['gallery_images'], $postID);
        }

        $this->successMassage();
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



        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $_POST['action'] = 'wp_handle_upload';

        $status = wp_handle_upload($files, array('test_form' => false, 'action' => ' wp_handle_upload'));

        if(!file_exists($status[ 'file' ]))
        {
            return false;
        }

        $attachment = array(
            'post_title' => $status['name'],
            'post_content' => '',
            'post_type' => 'attachment',
            'post_parent' => $postID,
            'post_mime_type' => $status['type'],
            'guid' => $status['url']
        );

        $attach_id = wp_insert_attachment( $attachment,$status[ 'file' ], $postID );
        $attach_data = wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $status['file'] ) );

        // Update post thumbnail attachment id
        update_post_meta($postID, '_thumbnail_id', $attach_id);

    }

    private function createNews($data , $authorID)
    {
        global $wpdb;

        $quote_ref_table = $wpdb->prefix . self::WHEELS_QUOTE_REF_TABLE;
        $newsgetRefTable = $wpdb->prefix . self::WHEELS_NEWS_GET_API_REF_TABLE;

        $newsget_unique_id = "news+" . $data['newsget_unique_id'];

        $ref_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $newsgetRefTable WHERE newsget_unique_id = %s", $newsget_unique_id));


        unset($data['featured_image_caption']);

        $make     = $data['make'][0];
        $year     = $data['year'][0];
        $model    = $data['model'][0];
        $category = $data['category'][0];
        $class    = $data['class'][0];

        $makeId     = get_term_by('name', $make, 'make');
        $yearId     = get_term_by('name', $year, 'vehicle-year');
        $modelId    = get_term_by('name', $model, 'model');
        $categoryId = get_term_by('name', $category, 'news-category');
        $classId    = get_term_by('name', $class, 'class');

        $post_data = array(
            'post_title'     => $data['title'],
            'post_content'   => $data['description'],
            'post_excerpt'   => $data['excerpt'],
            'post_author'    => $authorID,
            'post_type'      => 'news',
            'comment_status' => 'open',
            'post_status'    => 'draft', // 'draft' | 'publish' | 'pending'| 'future',
            'tax_input'      => array('make'          => $makeId->term_id,
                                      'class'         => $classId->term_id,
                                      'model'         => $modelId->term_id,
                                      'year'          => $yearId->term_id,
                                      'news-category' => $categoryId->term_id
            )
        );


        if ($ref_id) {
            $post_data['ID'] = $ref_id ;

            $post_id = wp_update_post($post_data);
            $postAction = "update";

        }else{
            $post_id = wp_insert_post($post_data);
            $postAction = "insert";
        }



        $cimyData = array(
            'USER_ID'  => $authorID,
            'FIELD_ID' => 3,
            'VALUE '   => $data['author_byline']
        );

        $cimyTable = $wpdb->prefix . 'cimy_uef_data';

        if ($postAction == 'insert') {
            $wpdb->insert($cimyTable, $cimyData, array('%d', '%d', '%s'));
        } else {
            $wpdb->update($cimyTable,$cimyData,array('USER_ID' => $authorID));

        }

        if ($postAction == 'insert') {
            $wpdb->insert($quote_ref_table, array( 'quote' => $data['quotation'], 'vehicle_id_1' => $data['acode']), array('%d', '%s','%s'));
        } else {
            $wpdb->update($quote_ref_table, array('post_id' => $post_id, 'quote' => $data['quotation'], 'vehicle_id_1' => $data['acode']),array('post_id' => $post_id));

        }

        if($postAction =='update'){
           $attachments = get_children( array('post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment') );

                    foreach($attachments as $attachment)
                    {
                      wp_delete_attachment($attachment->ID);
                    }

           $galleryTable = $wpdb->prefix . 'wheels_gallery';

           $wpdb->query( $wpdb->prepare("DELETE FROM $galleryTable WHERE post_id = %d	", $post_id));

        }

        $this->newsGetPostRefferenceData($post_id,$postAction, $newsget_unique_id);

        return $post_id;
    }

    private function newsGetPostRefferenceData($post_id,$postAction,$newsget_unique_id)
    {
        global $wpdb;

        $newsGetRefTable = $wpdb->prefix . self::WHEELS_NEWS_GET_API_REF_TABLE;
        $newsGetData['post_id'] = $post_id;
        $newsGetData['newsget_unique_id'] = $newsget_unique_id;

        if ($postAction == 'insert') {
            $wpdb->insert($newsGetRefTable, $newsGetData, array('%d','%s'));
        } else {
            $wpdb->update($newsGetRefTable, $newsGetData, array('post_id' => $post_id));
        }

    }

    private function uploadGalleryImages($files, $newsId)
    {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        global $wpdb;

        $key = 0;
        foreach ($files['name'] as $value) {

            $file = array(
                'name'     => $files['name'][$key],
                'type'     => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error'    => $files['error'][$key],
                'size'     => $files['size'][$key]
            );

            $_POST['action'] = 'wp_handle_upload';
            $status = wp_handle_upload($file, array(
                'test_form' => false,
                'action'    => ' wp_handle_upload'
            ));

            if(!file_exists($status[ 'file' ]))
            {
                continue;
            }

            /* Prepare image for uploaidng S3*/
            $postID = $newsId;
            $attachment = array(
                'post_title' => $status['name'],
                'post_content' => '',
                'post_type' => 'attachment',
                'post_parent' => $postID,
                'post_mime_type' => $status['type'],
                'guid' => $status['url']
            );

            $attach_id = wp_insert_attachment( $attachment,$status[ 'file' ], $postID );
            $attach_data = wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $status['file'] ) );

            // S3 bucket name
            $S3Bucket = $this->getBucketName();
            if( isset( $S3Bucket ) )
            {
                $status['url'] = str_replace(
                    $_SERVER['HTTP_HOST'],
                    's3.amazonaws.com/'.$S3Bucket, $status['url']
                );
            }

            $table = $wpdb->prefix . 'wheels_gallery';
            $data  = array(
                'post_id' => $newsId,
                'file'    => $status['file'],
                'url'     => $status['url'],
                'type'    => $status['type'],
                'title'   => $_POST['gallery_images_caption'][$key],
                'source'  => $_POST['gallery_images_source'][$key],
                'credit'  => $_POST['gallery_images_credit'][$key]
            );

            $wpdb->insert($table, $data, array('%d', '%s', '%s', '%s', '%s', '%s', '%s'));
            $key++;
        }
    }

    private function gallery_io_upload_action($files, $postId) {

        $s3Options = get_option('tantan_wordpress_s3');
        $bucket = $s3Options['bucket'];

        $key = 0;
        foreach($files['name'] AS $value):;

        $file = array(
                    'name'     => $files['name'][$key],
                    'type'     => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error'    => $files['error'][$key],
                    'size'     => $files['size'][$key]
                );

        $status = wp_handle_upload($_FILES[$file . 'async-upload'], array('test_form' => true, 'action' => 'plupload_action'));
        $uploaded_file = $status;
        $attachment = array(
            'post_title' => $uploaded_file['name'][$key],
            'post_content' => '',
            'post_type' => 'attachment',
            'post_parent' => $postId,
            'post_mime_type' => $uploaded_file['type'][$key],
            'guid' => $uploaded_file['url'][$key]
        );

         $id = wp_insert_attachment( $attachment,$uploaded_file[ 'file' ], $postId );
        wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $uploaded_file['file'] ) );

        if( isset( $bucket ) )
        {
            $status['url'] = str_replace(
                $_SERVER['HTTP_HOST'],
                's3.amazonaws.com/'.$bucket, $status['url']
            );
        }

        global $wpdb;
        $table = $wpdb->prefix . 'wheels_gallery';

         $data  = array(
                    'post_id' => $postId,
                    'file'    => $id,
                    'url'     => $status['url'],
                    'type'    => $status['type'],
                    'title'   => $_POST['gallery_images_caption'][$key],
                    'source'  => $_POST['gallery_images_source'][$key],
                    'credit'  => $_POST['gallery_images_credit'][$key]
                );

                $wpdb->insert($table, $data, array('%d', '%s', '%s', '%s', '%s', '%s', '%s'));
                $key++;

        endforeach;

    }


    private function successMassage()
    {
        $response = array('Success' => 'News created successfully');
        header('Content-type: application/json', true, 201);
        echo json_encode($response);
        exit();
    }

    public function getBucketName()
    {
        // getting tantanS3 option
        $s3Options = get_option('tantan_wordpress_s3');
        // get bucket name
        $bucket = (isset($s3Options['bucket'])) ? $s3Options['bucket'] : '';
        return $bucket;
    }

}