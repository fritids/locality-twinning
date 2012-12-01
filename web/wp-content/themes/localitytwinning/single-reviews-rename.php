<?php

global $vehicle_id, $wpdb, $post;

$postModel = new \Emicro\Model\Post($wpdb);

$vehicleReviewMeta = $postModel->getVehicleReviewData(get_the_ID());

$customValues = $postModel->getCustomValues(get_the_ID());

get_header('meta');

?>
<body class="page article review mobile-page"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
    <?php get_header()?>

        <div id="review" class="section-container clearfix">
            <?php wheels_breadcrumb() ?>

            <div class="row header">
                <?php  if (have_posts()): while (have_posts()): the_post(); ?>
                <div class="main">
                    <h2 class="title"><?php the_title()?></h2>

                    <p class="excerpt"><?php echo apply_filters('the_content', stripslashes($post->post_excerpt));?></p>

                    <div class="author-info">
                        <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.get_the_author_meta('ID').'&size=81') ?>
                        <a href="<?php echo get_the_author_meta('url')?>" class="primary"><?php the_author()?></a>
                        <span><?php echo get_cimyFieldValue(get_the_author_meta('ID'), 'AUTHORBYLINE', false) ?></span>
                    </div>

                 <?php
                if(function_exists('wheels_get_gallery_assets')):
                $medias = wheels_get_gallery_assets(get_the_ID());
                if(!empty($medias)):
                ?>
                <div class="main-content"><!-- Begin .vehicle-gallery-->
                    <div data-controller="GalleryController" class="vehicle-gallery">

                        <div class="img"><img src="<?php echo $medias[0]->url?>" alt="<?php echo $medias[0]->title?>" class="large" />
                            <div class="overlay-container">
                                <div class="copy">
                                    <div class="pos">&nbsp;</div>
                                </div>
                            </div>
                        </div>

                        <div data-controller="SlidesController" class="gallery-nav">
                            <a class="nav left">Left</a>
                            <div class="viewport">
                                <div class="container clearfix">

                                    <?php foreach($medias as $media):?>
                                    <div class="slide galleryContent">
                                        <div class="wrap">
                                            <a href='<?php echo $media->url?>'>
                                                <img src='<?php echo $media->url?>' alt='gallery image' title='<?php echo $media->title?>'/></a>
                                            <span class="caption hidden"><?php echo $media->title?></span>
                                        </div>
                                    </div>
                                    <?php endforeach;?>
                                </div>
                            </div>
                            <a class="nav right">Right</a>
                        </div>

                    </div>
                    <!-- End .vehicle-gallery-->
                </div>
                <?php endif; endif; ?>
                    <!-- End .vehicle-gallery-->
                </div>

                    <div class="sidebar">

                        <div class="ratings-reviews">
                            <div class="wrap">

                                <div class="rating large">
                                    <?php /* if ($vehicleReviewMeta->star_rating != '0.0' ) { ?>
                                    <?php $starRating = explode('.', $vehicleReviewMeta->star_rating); ?>
                                    <div class="value rating-<?php echo $starRating[0] ?>-<?php echo $starRating[1] ?>"><?php echo $vehicleReviewMeta->star_rating ?></div>
                                    <h4>Star Rating</h4>
                                    <?php } */ ?>
                                </div>

                                <div class="rating large">
                                    <?php echo wheels_esi_include(get_template_directory_uri().'/esi/reviews/user_rating.php?post_id='.get_the_ID()) ?>
                                </div>

                                <div class="reviews">
                                    <a href="#" class="review-count"><?php echo wheels_esi_include(get_template_directory_uri() . '/esi/comment-count.php?post_id='.get_the_ID())?></a>
                                    <h4><?php comments_number('Comment', 'Comment', 'Comments') ?></h4>
                                </div>

                            </div>
                        </div>

                </div>
            </div>

            <div data-controller="ReviewNavController" class="row navigation">
                <ul id="review-nav">
                    <li class="first active">
                        <a href="#Introduction" name="introduction-row">Reviews</a>
                        <img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>

                    <li class="last">
                        <a href="#comments" name="comments-row">Comments</a>
                        <img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>
                </ul>
            </div>



            <div class="row intro section"><!-- begin .share-->

                <?php echo html_entity_decode(html_entity_decode( apply_filters('the_content', get_the_content()) ));?>

            </div>

            <!--Gallery Here Moved-->

    <?php endwhile; endif; ?>
    <?php
    global $withcomments, $rating;
    $withcomments = true;
    $rating = true; ?>

    <!-- Call comment esi to include comment list and form -->
    <?php echo wheels_esi_include(get_template_directory_uri().'/esi/comments.php?'.http_build_query($_GET).'&post_id='.get_the_ID().'&redirect='.urlencode( get_permalink(get_the_ID()) ).'&rating=true' ) ?>

</div>

<?php get_footer()?>
</body>
</html>