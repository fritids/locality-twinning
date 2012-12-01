<?php
require_once("../../../wp-load.php");

global $wpdb;

$userModel = new \Emicro\Model\User($wpdb);

$twitterObj = $userModel->connectTwitter();

if($_GET['oauth_token'] == '')
{ 
	header("location: ".site_url());
	exit;
} 
else
{
    $twitterObj->setToken($_GET['oauth_token']);
    $token = $twitterObj->getAccessToken();
    $twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
    $_SESSION['ot'] = $token->oauth_token;
    $_SESSION['ots'] = $token->oauth_token_secret;
    $twitterInfo = $twitterObj->get_accountVerify_credentials();

    $tr = $twitterInfo->response;

    if($userModel->twitterUserExists($twitterInfo->id))
    {
		$user =  new WP_User( $userModel->getWpUserId() );
		wp_set_auth_cookie($user->ID, false);
		do_action('wp_login', $user->user_login, $user);
		header("location: ".site_url()."/mywheels");
		exit;
	}
	else
	{
		$_SESSION['twitter_oauth_token'] = $token->oauth_token;
        $_SESSION['twitter_oauth_token_secret'] = $token->oauth_token_secret;
        $_SESSION['signup_twitter_user_id'] = $twitterInfo->id;
		$_SESSION['signup_username'] = $twitterInfo->screen_name;
		$_SESSION['signup_email'] = '';
		header("location: ".site_url()."?act=opensignup");
		exit;
	}
 } 
?>