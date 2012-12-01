<?php

    require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

    global $wpdb;
    $postModel = new \Emicro\Model\Post($wpdb);
    $args['limit'] = 9;
    $args['type'] = 'latest';
    $args['post_type'] = 'reviews';
    $latest_reviews = $postModel->getAll($args);

    if (!empty($latest_reviews)) {
        ?>

        <div class="row reviewCarouselContainer">

            <div id="reviewCarousel" data-controller="CarouselController" class="carousel slide">
                <div class="carousel-inner">
                    <div class="item active">
                        <?php
                        $loop = 1;
                        $break_div = array(4, 7);
                        foreach ($latest_reviews as $post)
                        {

                            switch ($loop) {
                                case 1:
                                case 4:
                                case 7:
                                    $elm_class = 'prime tl';
                                    break;
                                case 2:
                                case 5:
                                case 8:
                                    $elm_class = 'tm';
                                    break;
                                case 3:
                                case 6:
                                case 9:
                                    $elm_class = 'tr';
                                    break;
                            }
                            switch ($loop) {
                                case 1:
                                case 4:
                                case 7:
                                    $image_size = 'slide-big';
                                    break;
                                default:
                                    $image_size = 'slide-medium';
                                    break;
                            }
                            setup_postdata($post);

                            if(in_array($loop, $break_div)) echo '</div><div class="item ">';

                            ?>
                            <div class="feature-container <?php echo $elm_class?>"><?php the_post_thumbnail($image_size)?>

                                <div class="copy">
                                    <div class="pos">
                                        <h4>
                                            <a href="<?php the_permalink()?>"> <?php the_title()?>&nbsp;&raquo;
                                                <span class="author"><?php the_author()?></span>
                                            </a>
                                        </h4>
                                        <?php
                                        //$vehicleReviewMeta = $postModel->getVehicleReviewData($post->ID);
                                        //$starRating = explode('.', $vehicleReviewMeta->star_rating);
                                        ?>
                                        <?php //if ($vehicleReviewMeta->star_rating != '0.0' ) { ?>
                                        <!--<div class="rating black">
                                            <div
                                                class="value rating-<?php /*//echo $starRating[0] */?>-<?php /*//echo $starRating[1] */?>"><?php /*//echo $vehicleReviewMeta->star_rating */?></div>
                                        </div>-->
                                        <?php //} ?>
                                    </div>
                                </div>
                                <div class="overlay">&nbsp;</div>
                            </div>

                            <?php

                            $loop++;
                        }?>

                    </div>


                </div>
                <a href="#reviewCarousel" data-slide="prev" class="carousel-control left">&lsaquo;</a>
                <a href="#reviewCarousel" data-slide="next" class="carousel-control right">&rsaquo;</a>
            </div>

        </div>

        <!-- end .carousel-->
        <?php }?>

    <?php
    $args['type'] = 'popular';
    $popular_reviews = $postModel->getAll($args);
    if (!empty($popular_reviews)) {
        ?>
        <div class="row reviewCarouselContainer" style="display: none;">
            <div id="reviewCarousel2" data-controller="CarouselController" class="carousel slide">
                <div class="carousel-inner">
                    <div class="item active">
                        <?php
                        $loop = 1;
                        $break_div = array(3, 6);
                        foreach ($popular_reviews as $post)
                        {

                            switch ($loop) {
                                case 1:
                                case 4:
                                case 7:
                                    $elm_class = 'prime tl';
                                    break;
                                case 2:
                                case 5:
                                case 8:
                                    $elm_class = 'tm';
                                    break;
                                case 3:
                                case 6:
                                case 9:
                                    $elm_class = 'tr';
                                    break;
                            }
                            switch ($loop) {
                                case 1:
                                case 4:
                                case 7:
                                    $image_size = 'slide-big';
                                    break;
                                default:
                                    $image_size = 'slide-medium';
                                    break;
                            }
                            setup_postdata($post)
                            ?>
                            <div class="feature-container <?php echo $elm_class?>"><?php the_post_thumbnail($image_size)?>

                                <div class="copy">
                                    <div class="pos">
                                        <h4>
                                            <a href="<?php the_permalink()?>"> <?php the_title()?>&nbsp;&raquo;
                                                <span class="author"><?php the_author()?></span>
                                            </a>
                                        </h4>
                                        <?php
                                        //$vehicleReviewMeta = $postModel->getVehicleReviewData($post->ID);
                                        //$starRating = explode('.', $vehicleReviewMeta->star_rating);
                                        ?>
                                        <?php //if ($vehicleReviewMeta->star_rating != '0.0' ) { ?>
                                        <!--<div class="rating black">
                                            <div
                                                class="value rating-<?php /*//echo $starRating[0] */?>-<?php /*//echo $starRating[1] */?>"><?php /*//echo $vehicleReviewMeta->star_rating */?></div>
                                        </div>-->
                                        <?php //} ?>
                                    </div>
                                </div>
                                <div class="overlay">&nbsp;</div>
                            </div>

                            <?php
                            if (in_array($loop, $break_div)) echo '</div><div class="item ">';
                            $loop++;
                        }?>

                    </div>
                </div>
                <a href="#reviewCarousel2" data-slide="prev" class="carousel-control left">&lsaquo;</a>
                <a href="#reviewCarousel2" data-slide="next" class="carousel-control right">&rsaquo;</a>
            </div>
        </div>
        <?php }?>
