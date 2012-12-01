<?php
require_once("../../../wp-load.php");
use \Emicro\Plugin\Varnish;

function wheels_avatar_invalidate($ID,$size=81)
{
    $urls = array(
        'avatar' => get_template_directory_uri() . '/esi/avatar.php?ID='.$ID.'&size='.$size,
    );

    Varnish::purgeAll($urls);
}

global $wpdb;

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['hdn-registration-form'] ) )
{
    $userdata = array(
        'user_email' => esc_attr( $_POST['email'] ),
        'user_login' => esc_attr( $_POST['username'] ),
        'user_pass' => esc_attr( $_POST['password'] ),
        'user_pass2' => esc_attr( $_POST['password2'] ),
        'role' => get_option( 'default_role' )
    );
    $bool_error = false;
    $error = array('error'=>'false');
    if (!is_email($userdata['user_email'], true)){
        $error['email'] = "Invalid email address!";
        $bool_error = true;
    }
    else if (email_exists($userdata['user_email'])){
        $error['email'] = "Email already registered!";
        $bool_error = true;
    }
    if (!$userdata['user_login']){
        $error['username'] = "Invalid username!";
        $bool_error = true;
    }
    else if (username_exists($userdata['user_login'])){
        $error['username'] = "Username already exists";
        $bool_error = true;
    }
    if (!$userdata['user_pass']){
        $error['pass'] = "Incorrect password!";
        $bool_error = true;
    }
    if ($userdata['user_pass'] != $userdata['user_pass2']){
        $error['pass'] = "Passwords do not match!";
        $bool_error = true;
    }
    if(!$bool_error)
    {
        $new_user_id = wp_insert_user($userdata);
		add_user_meta($new_user_id, 'facebook_user_id', $_POST['hdn_facebook_user_id']);
        add_user_meta($new_user_id, 'twitter_user_id', $_POST['hdn_twitter_user_id']);
        //add_user_meta($new_user_id, 'twitter_oauth_token', $_POST['hdn_twitter_oauth_token']);
		//add_user_meta($new_user_id, 'twitter_oauth_token_secret', $_POST['hdn_twitter_oauth_token_secret']);
        add_user_meta($new_user_id, 'newsletter', $_POST['subscribe-newsletter']);
        add_user_meta($new_user_id, 'deals', $_POST['subscribe-deals']);

        if(isset($_POST['hdn_avatar']) && $_POST['hdn_avatar']!='')
        {
            $avatar = $_POST['hdn_avatar'];
            $avatar_path = $_POST['hdn_avatar_path'];

            $default_path = "wp-content/plugins/wheels-my-wheels/default-avatars/";
            $default_avatar_path = "wp-content/Cimy_User_Extra_Fields/";

            //--------
            //fetching default avatars
            $avatar_defaults = array();
            $handler = opendir($_SERVER["DOCUMENT_ROOT"].'/'.$default_path);
            while ($file = readdir($handler)) {
                if ($file != ".") {
                    $avatar_defaults[$file] = $file;
                }
            }
            closedir($handler);
            //--------
            if(in_array($avatar,$avatar_defaults))
            {
                $wpdb->query("UPDATE ".$wpdb->prefix."cimy_uef_data SET VALUE = '".site_url().'/'.$default_avatar_path.$avatar."' WHERE USER_ID = '$new_user_id' AND FIELD_ID = '1'");

                wheels_avatar_invalidate($new_user_id,81);
            }
            else
            {
                $wpdb->query("UPDATE ".$wpdb->prefix."cimy_uef_data SET VALUE = '".$avatar_path."' WHERE USER_ID = '$new_user_id' AND FIELD_ID = '1'");

                wheels_avatar_invalidate($new_user_id,81);
            }

        }

        wp_new_user_notification_link($new_user_id, $userdata['user_pass']);
        print_r(json_encode($error));
    }
    else
    {
        $error['error'] = 'true';
        print_r(json_encode($error));
    }
}

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['hdn-signin-form'] ) )
{
    $creds = array();
    $creds['email'] = $_POST['email'];
    $creds['user_password'] = $_POST['password'];
    $creds['remember'] = (isset($_POST['remember']) && $_POST['remember'] == 'yes') ? true : false;

    $bool_error = false;
    $error = array('error'=>'false','email'=>'','pass'=>'');

    $x = get_user_by('email',$creds['email']);
    $user_login = (isset($x->user_login))?$x->user_login:'';
    $creds['user_login'] = $user_login;
    $user = wp_signon( $creds, false );

    if ( is_wp_error($user) ){
        $error['error'] = 'true';
        $error['login'] = 'Incorrect email or password!';
        print_r(json_encode($error));
        exit;
    }
    else
    {
        $response['error'] = 'false';
        $response['username'] = $user->data->user_login;
        $redir_url = $_SERVER["HTTP_REFERER"];
        if($_GET['token']=='7c958ed9615862689883f828a5e69c2d'){
            $redir_url = site_url();
        }
        $response['redir_url'] = $redir_url;
        $response['site_url'] = site_url();
        echo json_encode($response);
        exit;
    }
}

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['hdn-profile-form'] ) )
{
    $userdata = array(
        'first_name' => esc_attr( $_POST['first_name'] ),
        'last_name' => esc_attr( $_POST['last_name'] ),
    );
    $userdata['ID'] = 0;
    $userdata['user_login'] = '';
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    if ( $current_user_id ) {
        $userdata['ID'] = $current_user_id;
        $userdata['user_login'] = $current_user->user_login;
    }else{exit;}
    $bool_error = false;
    $error = array('error'=>'false');
    if (!$userdata['first_name']){
        $error['first_name'] = "Invalid firstname!";
        $bool_error = true;
    }
    if (!$userdata['last_name']){
        $error['last_name'] = "Invalid lastname!";
        $bool_error = true;
    }
    if ($_POST['password']){
        if ($_POST['password'] != $_POST['password2']){
            $error['pass2'] = "Passwords do not match!";
            $bool_error = true;
        }else{
            $userdata['user_pass'] = esc_attr( $_POST['password'] );
        }
    }

    if(!$bool_error)
    {
        wp_update_user($userdata);
        update_user_meta($current_user_id, 'first_name', esc_attr( $_POST['first_name'] ));
        update_user_meta($current_user_id, 'last_name', esc_attr( $_POST['last_name'] ));
        update_user_meta($current_user_id, 'newsletter', $_POST['subscribe-newsletter']);
        update_user_meta($current_user_id, 'deals', $_POST['subscribe-deals']);

        if(isset($_POST['hdn_avatar']) && $_POST['hdn_avatar']!='')
        {
            $avatar = $_POST['hdn_avatar'];
            $avatar_path = $_POST['hdn_avatar_path'];

            $default_path = "wp-content/plugins/wheels-my-wheels/default-avatars/";
            $default_avatar_path = "wp-content/Cimy_User_Extra_Fields/";

            //--------
            //fetching default avatars
            $avatar_defaults = array();
            $handler = opendir($_SERVER["DOCUMENT_ROOT"].'/'.$default_path);
            while ($file = readdir($handler)) {
                if ($file != ".") {
                    $avatar_defaults[$file] = $file;
                }
            }////
            closedir($handler);
            //--------
            if(in_array($avatar,$avatar_defaults))
            {
                $wpdb->query("UPDATE ".$wpdb->prefix."cimy_uef_data SET VALUE = '".site_url().'/'.$default_avatar_path.$avatar."' WHERE USER_ID = '$current_user_id' AND FIELD_ID = '1'");

                all_size_avatar_invalidate($current_user_id);

            }
            else
            {
                $wpdb->query("UPDATE ".$wpdb->prefix."cimy_uef_data SET VALUE = '".$avatar_path."' WHERE USER_ID = '$current_user_id' AND FIELD_ID = '1'");

                all_size_avatar_invalidate($current_user_id);
            }

        }
        $error['redir_url'] = site_url()."/myprofile";
        print_r(json_encode($error));
    }
    else
    {
        $error['error'] = 'true';
        $error['redir_url'] = site_url()."/myprofile";
        print_r(json_encode($error));
    }
}

function all_size_avatar_invalidate($current_user_id)
{
    wheels_avatar_invalidate($current_user_id,81);
    wheels_avatar_invalidate($current_user_id,30);
    wheels_avatar_invalidate($current_user_id,48);
    wheels_avatar_invalidate($current_user_id,35);
    wheels_avatar_invalidate($current_user_id,50);
}
?>