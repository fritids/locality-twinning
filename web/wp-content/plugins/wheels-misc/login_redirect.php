<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;

$userModel = new \Emicro\Model\User($wpdb);
$facebookUserId = $_REQUEST['fbid'];
$twitterUserId = $_REQUEST['twid'];

if (!empty($facebookUserId)) {

    if ($userModel->loadFacebookUser($facebookUserId)) {

        $hash = md5($userModel->getWpUserId() + 'fb');

        if ($hash != $_REQUEST['hash']) {
            die("Invalid token");
        }

        $user = new WP_User($userModel->getWpUserId());
        wp_set_auth_cookie($user->ID, false);

        do_action('wp_login', $user->user_login, $user);
        header("Location: " . $_SERVER['HTTP_REFERER']);

        exit;
    }

} else {

    if ($userModel->loadTwitterUser($twitterUserId)) {

        $hash = md5($userModel->getWpUserId() + 'tw');

        if ($hash != $_REQUEST['hash']) {
            die("Invalid token");
        }

        $user = new WP_User($userModel->getWpUserId());
        wp_set_auth_cookie($user->ID, false);

        do_action('wp_login', $user->user_login, $user);
        header("Location: " . $_SERVER['HTTP_REFERER']);

        exit;
    }

}