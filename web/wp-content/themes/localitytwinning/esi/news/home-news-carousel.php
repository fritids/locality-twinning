<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;

$postModel = new \Emicro\Model\Post($wpdb);

$latestNews  = $postModel->getAll(array('post_type' => 'news', 'limit' => 21));
//$popularNews = $postModel->getAll(array('post_type' => 'news', 'limit' => 21, 'type' => 'popular'));

?>

<div class="home-carousel">
    <ul id="home-reviews-pill-menu" class="pill-menu">
        <!--<li class="on ac"><a href="#">Latest</a></li>-->
        <!--<li class="ac"><a href="#">Popular</a></li>-->
    </ul>
    <!-- begin .carousel-->
    <div class="home-reviews-container-latest">
        <div id="homeCarousel" data-controller="CarouselController" class="carousel slide">
            <div class="carousel-inner">
                <div class="item active">

                    <?php

                    $loop = 1;
                    $break_div = array(7, 14);

                    foreach($latestNews as $post):

                        switch($loop) {
                            case 1: case 8: case 15: $elm_class = 'tl'; break;
                            case 2: case 9: case 16: $elm_class = 'bl'; break;
                            case 3: case 10: case 17: $elm_class = 'tm'; break;
                            case 4: case 11: case 18: $elm_class = 'bm'; break;
                            case 5: case 12: case 19: $elm_class = 'tr'; break;
                            case 6: case 13: case 20: $elm_class = 'mr'; break;
                            case 7: case 14: case 21: $elm_class = 'br'; break;
                        }

                        switch($loop){
                            case 1: case 2: case 3: case 8: case 9: case 10: case 15: case 16: case 17: $mobile_class = ' mobile-feature'; break;
                            default: $mobile_class = ''; break;
                        }

                        switch($loop){
                            case 1: case 2: case 8: case 9: case 15: case 16: $image_size = '276x151'; break;
                            case 3: case 10: case 17: $image_size = '183x202'; break;
                            default: $image_size = '183x100'; break;
                        }

                        setup_postdata($post);

                        ?>

                        <div class="feature-container <?php echo $elm_class . $mobile_class ?>">

                            <?php the_post_thumbnail($image_size) ?>

                            <div class="copy">
                                <div class="pos">
                                    <a href="<?php the_permalink() ?>">
                                        <h4><?php echo character_limiter( get_the_title(), 70)?>&nbsp;&raquo;</h4>
                                    </a>
                                </div>
                            </div>

                            <a href="<?php the_permalink() ?>#comment-container" class="comment-count">
                                <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/comment-count.php?post_id='.$post->ID) ?>
                            </a>
                            <div class="overlay">&nbsp;</div>

                        </div>

                        <?php if(in_array($loop, $break_div)) echo '</div><div class="item ">'; $loop++; ?>
                        <?php endforeach; wp_reset_postdata(); ?>

                </div>

            </div>
            <a href="#homeCarousel" data-slide="prev" class="carousel-control left">&lsaquo;</a>
            <a href="#homeCarousel" data-slide="next" class="carousel-control right">&rsaquo;</a>
        </div>
        <!-- end .carousel-->
    </div>
    <!-- end .home-reviews-container-latest-->

    <!-- begin .home-reviews-container-popular-->
    <div class="home-reviews-container-popular">
        <div id="homeCarousel2" data-controller="CarouselController" class="carousel slide">
            <div class="carousel-inner">

                <div class="item active">

                    <?php

                    $loop = 1;
                    $break_div = array(7, 14);

                    foreach($popularNews as $post):

                        switch($loop) {
                            case 1: case 8: case 15: $elm_class = 'tl'; break;
                            case 2: case 9: case 16: $elm_class = 'bl'; break;
                            case 3: case 10: case 17: $elm_class = 'tm'; break;
                            case 4: case 11: case 18: $elm_class = 'bm'; break;
                            case 5: case 12: case 19: $elm_class = 'tr'; break;
                            case 6: case 13: case 20: $elm_class = 'mr'; break;
                            case 7: case 14: case 21: $elm_class = 'br'; break;
                        }

                        switch($loop){
                            case 1: case 2: case 8: case 9: case 15: case 16: $mobile_class = ' mobile-feature'; break;
                            default: $mobile_class = ''; break;
                        }

                        switch($loop){
                            case 1: case 2: case 8: case 9: case 15: case 16: $image_size = '276x151'; break;
                            case 3: case 10: case 17: $image_size = '183x202'; break;
                            default: $image_size = '183x100'; break;
                        }

                        setup_postdata($post);

                        ?>

                        <div class="feature-container <?php echo $elm_class . $mobile_class ?>">

                            <?php the_post_thumbnail($image_size) ?>

                            <div class="copy">
                                <div class="pos">
                                    <a href="<?php the_permalink() ?>">
                                        <h4><?php echo character_limiter( get_the_title(), 70)?>&nbsp;&raquo;</h4>
                                    </a>
                                </div>
                            </div>

                            <a href="<?php the_permalink() ?>#comment-container" class="comment-count">
                                <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/comment-count.php?post_id='.$post->ID) ?>
                            </a>
                            <div class="overlay">&nbsp;</div>

                        </div>

                        <?php if(in_array($loop, $break_div)) echo '</div><div class="item ">'; $loop++; ?>
                        <?php endforeach; wp_reset_postdata(); ?>

                </div>

            </div>
            <a href="#homeCarousel2" data-slide="prev" class="carousel-control left">&lsaquo;</a>
            <a href="#homeCarousel2" data-slide="next" class="carousel-control right">&rsaquo;</a>
        </div>
        <!-- end .carousel-->
    </div>
    <!-- end .home-reviews-container-popular-->
</div>