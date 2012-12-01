<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-content/bootstrap.php';

global $wpdb;

$userModel = new \Emicro\Model\User($wpdb);
$twitterUserId = $_REQUEST['twid'];

if ($userModel->loadTwitterUser($twitterUserId)) {
    echo md5($userModel->getWpUserId() + 'tw');
} else {
    echo 'NOT FOUND';
}