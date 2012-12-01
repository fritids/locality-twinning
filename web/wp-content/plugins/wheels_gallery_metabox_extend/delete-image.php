<?php
include('../../../wp-load.php');
if( !current_user_can('upload_files') ) die('Access denied');
$id = $_POST['id'];
$file_id = $_POST['file_id'];
$post_id = $_POST['post_id'];
global $wpdb;
$table = $wpdb->prefix . WHEELS_GALLEY_METABOX_EXTEND_TABLE;
$attachment_id = $wpdb->get_var( $wpdb->prepare("SELECT file FROM $table WHERE id = '$id' LIMIT 1") );

//echo json_encode(array('status' => 'success')); exit;

$data = array('status' => 'fail');
if(!empty($attachment_id)){
    $delete = wp_delete_attachment($attachment_id);
    if(true){
        $path = $wpdb->query( $wpdb->prepare("DELETE FROM $table WHERE id = '$id' LIMIT 1") );
        $data = array('status' => 'success');
    }else{
        $data = array('status' => 'fail1');
    }
}else{
    $data = array('status' => 'fail2', 'path' => $path);
}

echo json_encode($data);