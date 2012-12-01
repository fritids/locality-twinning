<?php
/*
Plugin Name: Answer Center
Plugin URI: http://emicrograph.com
Description: Answer center for Wheels.ca.
Author: eMicrograph Dev Team
Version: 1.0
Author URI: http://emicrograph.com
*/

define('WHEELS_AC_CAT_TABLE', 'wheels_ac_categories');
define('WHEELS_AC_QUESTION_TABLE', 'wheels_ac_questions');
define('WHEELS_AC_ANSWER_TABLE', 'wheels_ac_answers');

register_activation_hook( __FILE__, 'wheels_ac_install' );
register_deactivation_hook( __FILE__, 'wheels_ac_uninstall' );

add_action('init', 'answerCenterSaveOption');

function wheels_ac_install(){
    include('wheels-ac-installation.php');
    wheels_ac_create_table();
}

function wheels_ac_uninstall(){
    include('wheels-ac-installation.php');
    wheels_ac_drop_table();
}

function wheels_ac_create_menu()
{
    add_menu_page('Answer Centre', 'Answer Centre', 'administrator', 'answer-centre', 'wheels_answer_centre_list',plugins_url('/wheels-ad-tag/images/icon_pref_settings.gif','wheels-answer-centre'));
    add_submenu_page('answer-centre','Answer Centre Category', 'Category', 'administrator', 'answer-centre-categories', 'wheels_answer_centre_category_list');
    add_submenu_page('answer-centre','Answer Centre Option', 'Option', 'administrator', 'answer-centre-option', 'wheels_answer_centre_option');
}
add_action('admin_menu', 'wheels_ac_create_menu');

function wheels_answer_centre_list(){
    include("wheels-ac-list.php");
}

function wheels_answer_centre_category_list(){
    include("wheels-ac-category.php");
}

function wheels_answer_centre_option(){
    include("wheels-ac-option.php");
}

function ac_is_question_exists($qid)
{
    global $wpdb;
    $qid = $wpdb->escape($qid);
    return $wpdb->get_var( $wpdb->prepare("SELECT id FROM ". $wpdb->prefix.WHEELS_AC_QUESTION_TABLE ." WHERE id = {$qid}") );
}

add_action('wp_footer',
    function()
    {
        global $template;
        if(basename($template) == 'archive-guides.php' || is_home())
        {
            echo '<script type="text/javascript">var WHEELS_AC_AJAX_ACTION = "' .plugins_url('ajax-data.php', __FILE__). '"</script>';
            echo '<script type="text/javascript" src="' .plugins_url('js.js', __FILE__). '"></script>';
        }
    }
);

function answerCenterSaveOption()
{
    if(isset($_POST['update-option']))
    {
        update_option('ac_option', $_POST['ac_option'] );
        wp_redirect( '/wp-admin/admin.php?page=answer-centre-option&option_update=true' );
    }
}
?>