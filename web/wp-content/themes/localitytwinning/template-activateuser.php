<?php
/*
 * Template Name: Activateuser
 */
?>
<?php
$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
$scode = isset($_REQUEST['sc']) ? $_REQUEST['sc'] : '';
$error = false;
if( $token=='7c958ed9615862689883f828a5e69c2d' )
{
    require_once($_SERVER["DOCUMENT_ROOT"] ."/wp-load.php");
    global $wpdb;
    $row = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'uae_user_activation_code' AND meta_value = '".$scode."'");
    if(!empty($row->user_id)){
        $wpdb->query("UPDATE ".$wpdb->prefix."usermeta SET meta_value = 'active' WHERE meta_key = 'uae_user_activation_code' AND meta_value = '".$scode."'");
        $user =  new WP_User( $row->user_id );
        wp_set_auth_cookie($user->ID, false);
        do_action('wp_login', $user->user_login, $user);
        header("location: ".site_url()."/mywheels");
    }else{
        $error = true;
    }
}
else
{
    $error = true;
}
?>
<?php get_header('meta')?>
<body class="page article news mobile-page"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
    <?php get_header()?>
    <!-- begin news article-->
    <div id="news-article" class="section-container">

        <?php
        if($error){
            echo "<br/>Sorry! account did not activate.<br/>";
        }else{
            echo "<br/>Your account activated. Please Sign-in now.<br/>";
        }
        ?>

    </div>
    <?php get_footer()?>
    <script type="text/javascript" src="<?php echo plugin_dir_url('wheels-my-wheels') ?>wheels-my-wheels/rajax.js"></script>
</body>
</html>