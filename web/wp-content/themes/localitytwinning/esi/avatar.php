<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';
echo wheels_add_avatar_class(get_avatar($_GET['ID'], $_GET['size']));
?>
