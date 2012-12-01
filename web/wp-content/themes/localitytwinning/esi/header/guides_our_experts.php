<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
global $wpdb;

$postModel = new \Emicro\Model\Post($wpdb);
$experts = $postModel->getExpertsPost(4);

?>

<ul>
    <?php foreach ($experts AS $key => $post): setup_postdata($post); ?>
    <li class="<?php echo $key % 2 ? 'even' : 'odd'; ?>">
        <a href="<?php the_permalink() ?>">
            <div class="avatar">
                <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.get_the_author_meta('ID').'&size=50') ?>
            </div>
            <div class="copy">
                <strong><?php the_author(); ?></strong>
                <span><?php the_title(); ?></span>
            </div>
        </a>
    </li>
    <?php endforeach;?>

</ul>