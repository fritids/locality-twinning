<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
global $wpdb;

$postModel = new \Emicro\Model\Post($wpdb);
$latestGuides = $postModel->getAll(array('limit' => 5, 'post_type' => 'guides'));

?>


<ul>
    <?php foreach ($latestGuides AS $guide) : ?>
    <li><a href="<?php echo get_permalink($guide->ID) ?>"><?php echo $guide->post_title; ?></a></li>
    <?php endforeach; ?>
</ul>