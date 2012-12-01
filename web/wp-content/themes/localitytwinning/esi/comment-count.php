<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/wp-content/bootstrap.php';
global $wpdb;
if (empty($_GET['post_id']) ) $_GET['post_id'] = $_GET['ID'];
$sql = $wpdb->prepare("SELECT comment_count FROM wp_posts WHERE ID = %d", $_GET['post_id']);
$count = $wpdb->get_var($sql);
echo $count;
?>
