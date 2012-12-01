<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-content/bootstrap.php';

global $wpdb;

$userModel = new \Emicro\Model\User($wpdb);
$facebookUserId = $_REQUEST['fbid'];

if ($userModel->loadFacebookUser($facebookUserId)) {
    echo md5($userModel->getWpUserId() + 'fb');
} else {
    echo 'NOT FOUND';
}