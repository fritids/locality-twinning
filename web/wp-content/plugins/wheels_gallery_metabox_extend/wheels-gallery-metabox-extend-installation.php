<?php
function wheels_gallery_metabox_extend_create_table() {
   global $wpdb;

   $table = $wpdb->prefix . WHEELS_GALLEY_METABOX_EXTEND_TABLE;
   if($wpdb->get_var("show tables like '$table'") != $table) {
      $sql = "CREATE TABLE IF NOT EXISTS $table (
              id int(10) unsigned NOT NULL AUTO_INCREMENT,
              post_id int(10) unsigned NOT NULL,
              title varchar(255) NOT NULL,
              video varchar(255) NOT NULL,
              file text NOT NULL,
              url text NOT NULL,
              type varchar(50) NOT NULL,
              weight int(10) NOT NULL,
              PRIMARY KEY (id)
            ) CHARSET=utf8";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
    }
}
function wheels_gallery_metabox_extend_drop_table() {
	global $wpdb;
    $table = $wpdb->prefix . WHEELS_GALLEY_METABOX_EXTEND_TABLE;
	if($wpdb->get_var("show tables like '$table'") == $table) {
		$sql = "DROP TABLE $table";
		$wpdb->query($sql);
	}
}