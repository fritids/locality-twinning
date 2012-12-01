<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/wp-content/bootstrap.php");
$modelList = json_encode($wpdb->get_results("SELECT ModelDesc AS modelName, DivCode AS makeCode FROM wp_model ORDER BY ModelDesc ASC"));
echo "\n";
echo "var modelList = $modelList";
echo "\n";