<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
global $wpdb;

$postModel = new \Emicro\Model\Post($wpdb);
$latestNews = $postModel->getAll(array('limit' => 5, 'post_type' => 'news'));

?>

<ul>
    <?php foreach ($latestNews AS $news): ?>
    <li><a href="<?php echo get_permalink($news->ID) ?>"><?php echo $news->post_title; ?></a></li>
    <?php endforeach; ?>
</ul>