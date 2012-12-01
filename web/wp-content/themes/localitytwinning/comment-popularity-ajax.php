<?php
//echo 4; exit;
$comment_id = (int)$_POST['comment_id'];
if(empty($comment_id)) exit;

require_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';
global $wpdb;

if( !is_user_logged_in() ) exit;

$popularity = (int)get_comment_meta($comment_id, 'comment_popularity', true);

if($_POST['action'] == 'add') $popularity = $popularity + 1;
if($_POST['action'] == 'remove' && $popularity > 0) $popularity = $popularity - 1;

update_comment_meta($comment_id, 'comment_popularity', $popularity);
echo $popularity;