<?php require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php'; ?>
<!-- begin .carousel-->
<div id="guideCarousel" data-controller="CarouselController" class="carousel slide">
    <div class="carousel-inner">

        <?php
        global $wpdb;

        $args['type'] = 'latest';
        $args['post_type'] = 'guides';
        $args['limit'] = 3;

        $postModel = new \Emicro\Model\Post($wpdb);
        $latestGuides = $postModel->getAll($args);

        $loop = 1;
        if(!empty($latestGuides)):
            foreach($latestGuides as $post):
                setup_postdata($post)
                ?>

                <div class="item <?php if($loop == 1) echo 'active' ?>">
                    <div class="feature-container"><?php the_post_thumbnail('driving-guide-medium')?>
                        <div class="copy">
                            <div class="pos"><a href="<?php the_permalink()?>">
                                <p><?php the_author()?></p>
                                <h4><?php the_title()?> &raquo;</h4>
                            </a></div>
                        </div>
                        <a href="<?php the_permalink()?>#comment-container" class="comment-count">
                        <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/comment-count.php?post_id='.$post->ID) ?>
                        </a>
                        <div class="overlay">&nbsp;</div>
                    </div>
                </div>

                <?php
                $loop++;
            endforeach;
        endif;
        ?>

    </div>
    <a href="#guideCarousel" data-slide="prev" class="carousel-control left">&lsaquo;</a>
    <a href="#guideCarousel" data-slide="next" class="carousel-control right">&rsaquo;</a>
</div>
<!-- end .carousel-->

<!-- begin .carousel-->
<div id="guideCarousel2" data-controller="CarouselController" class="carousel slide" style="display: none;">
    <div class="carousel-inner">

        <?php
        global $wpdb;

        $args['type'] = 'popular';
        $args['post_type'] = 'guides';
        $args['limit'] = 3;

        $postModel = new \Emicro\Model\Post($wpdb);
        $latestGuides = $postModel->getAll($args);

        $loop = 1;
        if(!empty($latestGuides)):
            foreach($latestGuides as $post):
                setup_postdata($post)
                ?>

                <div class="item <?php if($loop == 1) echo 'active' ?>">
                    <div class="feature-container"><?php the_post_thumbnail('driving-guide-medium')?>
                        <div class="copy">
                            <div class="pos"><a href="<?php the_permalink()?>">
                                <p><?php the_author()?></p>
                                <h4><?php the_title()?> &raquo;</h4>
                            </a></div>
                        </div>
                        <a href="<?php the_permalink()?>#comment-container" class="comment-count">
                            <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/comment-count.php?post_id='.$post->ID) ?>
                        </a>
                        <div class="overlay">&nbsp;</div>
                    </div>
                </div>

                <?php
                $loop++;
            endforeach;
        endif;
        ?>

    </div>
    <a href="#guideCarousel2" data-slide="prev" class="carousel-control left">&lsaquo;</a>
    <a href="#guideCarousel2" data-slide="next" class="carousel-control right">&rsaquo;</a>
</div>
<!-- end .carousel-->
