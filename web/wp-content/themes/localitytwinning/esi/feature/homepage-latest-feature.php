<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;

$postModel = new \Emicro\Model\Post($wpdb);
$features    = $postModel->getAll(array('post_type' => 'feature', 'limit' => 12));
?>
<div class="row">

    <!-- begin .features-->
    <div data-controller="SlidesController" data-nthchild="4" class="features home-module">

        <div class="header">
            <h3>Features</h3>
            <a href="/news/">All Features</a>
        </div>

        <div class="viewport" style="overflow: hidden;">

            <div class="container" style="width: 3000px;">

                <ul>
                    <?php foreach($features as $post): setup_postdata($post); ?>
                    <li class="slide">
                        <div class="pos">
                            <a href="<?php the_permalink() ?>">
                                <?php the_post_thumbnail('204x115') ?>
                                <p>
                                    <strong><?php echo strip_tags(get_the_term_list( $post->ID, 'feature-category', '', ', ', '' )) ;?></strong>
                                    <?php echo character_limiter( get_the_title(), 60) ?>
                                </p>
                            </a>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="navigation">
            <a href="#" class="nav left">Left</a>
            <a href="#" class="nav right">Right</a>
        </div>

    </div>
    <!-- end .features-->
</div>