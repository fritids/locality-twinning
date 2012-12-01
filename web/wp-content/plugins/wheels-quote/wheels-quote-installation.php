<?php
function wheels_quote_create_table() {
   global $wpdb;

    $quote_ref_table = $wpdb->prefix . WHEELS_QUOTE_REF_TABLE;
   if($wpdb->get_var("show tables like '$quote_ref_table'") != $quote_ref_table) {
      $sql = "CREATE TABLE `wheels`.`wp_wheels_custom_data` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `post_id` INT( 10 ) UNSIGNED NOT NULL ,
        `sponsor_id` INT( 10 ) UNSIGNED NOT NULL ,
        `quote` TEXT NOT NULL ,
        `vehicle_id_1` VARCHAR( 13 ) NOT NULL
        ) CHARSET=utf8";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
    }
}
function wheels_quote_drop_table() {
	global $wpdb;
    $quote_ref_table = $wpdb->prefix . WHEELS_QUOTE_REF_TABLE;
	if($wpdb->get_var("show tables like '$quote_ref_table'") == $quote_ref_table) {
		//$sql = "DROP TABLE $sponsor_ref_table";
		//$wpdb->query($sql);
        //dbDelta($sql);
	}
}