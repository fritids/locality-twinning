<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
global $wpdb;

$postModel = new \Emicro\Model\Post($wpdb);
$reviews = $postModel->getAll(array('post_type' => 'reviews','limit' => 12));

?>
<div class="row">

    <!-- begin .reviews-->
    <div data-controller="SlidesController" data-nthchild="4" class="reviews home-module">

        <div class="header">
            <h3>Reviews</h3>
            <a href="/reviews">All Reviews</a>
        </div>

        <div class="viewport" style="overflow: hidden;">

            <div class="container" style="width: 3000px;">

                <ul>

                    <?php
                    foreach ($reviews as $post):

                        setup_postdata($post);

                        // Get Make, Model, Class taxonomy name
                        $make  = wp_get_post_terms(get_the_ID(), 'make');
                        $model = wp_get_post_terms(get_the_ID(), 'model');
                        $class = wp_get_post_terms(get_the_ID(), 'class');

                        $subTitle = '';

                        if (isset($make[0]->name))
                            $subTitle .= $make[0]->name;
                        if (isset($model[0]->name))
                            $subTitle .= ' ' . $model[0]->name;
                        if (isset($class[0]->name))
                            $subTitle .= ' ' . $class[0]->name;

                        ?>

                        <li class="slide">
                            <div class="pos">
                                <a href="<?php the_permalink() ?>">
                                    <?php the_post_thumbnail('204x115') ?>
                                    <p>
                                        <strong><?php echo $subTitle ?></strong>
                                        <?php echo character_limiter(get_the_title(), 60) ?>
                                        <span class="author"><?php the_author() ?></span>
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
    <!-- end .reviews-->

</div>