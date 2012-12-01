<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
global $wpdb;

$postModel = new \Emicro\Model\Post($wpdb);
$latestfeature = $postModel->getAll(array('limit' => 3, 'custom_field' => true, 'post_type' => 'feature'));

?>

<ul>
    <?php foreach ($latestfeature AS $key => $feature) : ?>
    <li <?php if ($key == 2) {
        echo 'class = "last" ';
    }  ?>>
        <a href="<?php echo get_permalink($feature->ID) ?>">
            <?php echo get_the_post_thumbnail($feature->ID, 'driving-guide') ?>
            <?php echo $feature->post_title; ?>
            <?php if (!empty($feature->sponsor_id)) { ?><span class="sponsored">Sponsored</span><?php }?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>