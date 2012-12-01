<?php
require_once("../../../wp-load.php");

global $wpdb;

$userModel = new \Emicro\Model\User($wpdb);

$facebookObj = $userModel->connectFacebook();

$facebook_user_id = $facebookObj->getUser();

if($facebook_user_id)
{
	$facebook_info = $userModel->getFacebookUser($facebookObj,$facebook_user_id);
	if($userModel->facebook_user_exists($facebook_user_id))
	{
		$user =  new WP_User( $userModel->getWpUserId() );
		wp_set_auth_cookie($user->ID, false);
		do_action('wp_login', $user->user_login, $user);
		header("location: ".site_url()."/mywheels");
		exit;
	}
	else
	{
		$_SESSION['signup_facebook_user_id'] = $facebook_info['id'];
		$_SESSION['signup_username'] = $facebook_info['username'];
		$_SESSION['signup_email'] = $facebook_info['email'];
		header("location: ".site_url()."/?act=opensignup&xx=xx");
		exit;
	}
}
?>