<?php
if (($_FILES["file"]["type"] == "image/gif")
    || ($_FILES["file"]["type"] == "image/jpeg")
    || ($_FILES["file"]["type"] == "image/pjpeg")
    || ($_FILES["file"]["type"] == "image/png")
    )
{
    if ($_FILES["file"]["error"] > 0)
    {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
    else
    {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-admin/includes/file.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-admin/includes/image.php');

        $status = wp_handle_upload($_FILES["file"], array('test_form' => false, 'action' => ' wp_handle_upload'));

        // getting tantanS3 option
        $s3Options = get_option('tantan_wordpress_s3');
        // get bucket name
        $bucket = $s3Options['bucket'];

        $uploaded_file = $status;
        $attachment = array(
            'post_title' => $uploaded_file['name'],
            'post_content' => '',
            'post_type' => 'attachment',
            'post_parent' => '',
            'post_mime_type' => $uploaded_file['type'],
            'guid' => $uploaded_file['url']
        );

        // Create an Attachment in WordPress
        $id = wp_insert_attachment( $attachment,$uploaded_file[ 'file' ], 0 );
        wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $uploaded_file['file'] ) );

        // Get attachment metadata
        $metadata = wp_get_attachment_metadata($id);

        $imageName = $status['filename'];
        $imageUrl = $status['url'];

        if($metadata)
        {
            foreach($metadata['sizes'] as $name => $image){
                if($image['width'] == '155'){
                    $imageUrl = str_replace($imageName, $image['file'], $imageUrl);
                    break;
                }elseif($image['width'] == '120'){
                    $imageUrl = str_replace($imageName, $image['file'], $imageUrl);
                    break;
                }elseif($image['width'] == '60'){
                    $imageUrl = str_replace($imageName, $image['file'], $imageUrl);
                    break;
                }
            }
        }
        $status['url'] = $imageUrl;

        // Change URL
        if( isset( $bucket ) )
        {
            $status['url'] = str_replace(
                $_SERVER['HTTP_HOST'],
                's3.amazonaws.com/'.$bucket, $status['url']
            );
        }

        $filename = $status['filename'];
        $filepath = $status['url'];

        echo json_encode(array('filename'=>$filename, 'filepath'=>$filepath, 'error'=>'false'));
        exit;
// TO DO:
//        $arr_temp = explode(".",$status['filename']);
//        if(count($arr_temp) >= 2)
//        {
//            $index = count($arr_temp) - 2;
//            $arr_temp[$index] = $arr_temp[$index].'-150x150';
//            $filename = implode(".",$arr_temp);
//            unset($arr_temp);
//            $arr_temp = explode(".",$status['url']);
//            $index = count($arr_temp) - 2;
//            $arr_temp[$index] = $arr_temp[$index].'-150x150';
//            $filepath = implode(".",$arr_temp);
//
//            echo json_encode(array('filename'=>$filename, 'filepath'=>$filepath, 'error'=>'false'));
//            exit;
//        }
//
//        echo json_encode(array('filename'=>$filename, 'filepath'=>$filepath, 'error'=>'true'));
    }
}
else
{
    echo json_encode(array('error'=>'true'));
    exit;
}
?>



