<?php
exit;
header("location: ".$_REQUEST['reff']);



require_once("../../../wp-load.php");

global $wpdb;

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['hdn-registration-form'] ) )
{
    //$_POST['user_login'] = $_POST['user_email'];
    //	$role =& get_role('contributor');
    $userdata = array(
        'user_email' => esc_attr( $_POST['email'] ),
        'user_login' => esc_attr( $_POST['username'] ),
        'user_pass' => esc_attr( $_POST['password'] ),
        'user_pass2' => esc_attr( $_POST['password2'] ),
        'role' => get_option( 'default_role' )
        //'role' => get_option( $role )
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
        $error['pass2'] = "Passwords do not match!";
        $bool_error = true;
    }
    if(!$bool_error)
    {
        $new_user_id = wp_insert_user($userdata);
		add_user_meta($new_user_id, 'facebook_user_id', $_POST['hdn_facebook_user_id']);
        wp_new_user_notification($new_user_id, $userdata['user_pass']);
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
        $error['error'] = 'false';
        $error['home_url'] = get_bloginfo('home');
        print_r(json_encode($error));
        exit;
    }
}

function set_fbaction()
{
    global $wpdb;
    $userModel = new \Emicro\Model\User($wpdb);



    if($userModel->facebook_user_exists())
    {
        //==================================
        $user =  new WP_User( '3' );//$userModel->get_user_id()
        wp_set_auth_cookie($user->ID, false);
        do_action('wp_login', $user->user_login, $user);
        //====================================

    }else{
        return 'register';
    }
}
?>