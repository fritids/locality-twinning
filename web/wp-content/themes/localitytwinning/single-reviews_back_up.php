<?php
global $vehicle_id, $wpdb, $post, $adModel;

$postModel = new \Emicro\Model\Post($wpdb);
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);

$vehicleReviewMeta = $postModel->getVehicleReviewData(get_the_ID());
$customValues = $postModel->getCustomValues(get_the_ID());
$make = wp_get_post_terms(get_the_ID(), 'make');
$model = wp_get_post_terms(get_the_ID(), 'model');
$class = wp_get_post_terms(get_the_ID(), 'class');

$ad = $adModel->getAd('300x250');
if(!empty($customValues->vehicle_id_1)){
    $vehicles = $vehicleModel->getVehicleByAcode($customValues->vehicle_id_1);
    if( $vehicles['result'] ){
        $relatedVehicles = $vehicleModel->getVehicles(array('model_id' => $vehicles['result'][0]->model_id ),0,6,'',true);
    }
}

?>
<?php get_header('meta')?>
<body class="page article review mobile-page"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
    <?php get_header()?>

        <div id="review" class="section-container clearfix">
            <?php wheels_breadcrumb() ?>

            <?php if($vehicles['result']):?>
            <div class="row subheader">

                <h3 class="subtitle"><?php echo getVehicleProfileTitle($vehicles['result'][0])?></h3>
                <a href="<?php echo getVehicleProfileLink($vehicles['result'][0])?>" class="primary">View Vehicle Profile</a>

            </div>
            <?php endif;?>

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
                    <!-- Begin .vehicle-gallery-->
                 <?php
                if(function_exists('wheels_get_gallery_assets')):
                $medias = wheels_get_gallery_assets(get_the_ID());
                if(!empty($medias)):
                ?>
                <div class="main-content"><!-- Begin .vehicle-gallery-->
                    <div data-controller="GalleryController" class="vehicle-gallery">

                        <div class="img"><img src="<?php echo $medias[0]->url?>" alt="<?php echo $medias[0]->title?>" class="large" width="556" height="315" />
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
                                    <?php  if ($vehicleReviewMeta->star_rating != '0.0' ) { ?>
                                    <?php $starRating = explode('.', $vehicleReviewMeta->star_rating); ?>
                                    <div class="value rating-<?php echo $starRating[0] ?>-<?php echo $starRating[1] ?>"><?php echo $vehicleReviewMeta->star_rating ?></div>
                                    <h4>Star Rating</h4>
                                    <?php } ?>
                                </div>

                                <div class="rating large">
                                    <?php echo wheels_esi_include(get_template_directory_uri().'/esi/reviews/user_rating.php') ?>
                                </div>

                                <div class="reviews">
                                    <a href="#" class="review-count"><?php echo wheels_esi_include(get_template_directory_uri() . '/esi/comment-count.php?post_id='.get_the_ID())?></a>
                                    <h4><?php comments_number('Comment', 'Comment', 'Comments') ?></h4>
                                </div>

                            </div>
                        </div>

                        <ul id="compare-utility">
                            <?php if($vehicles['result']):?>
                            <li>
                                <a rel="<?php echo $vehicles['result'][0]->acode?>" href="#" class="compare-vehicle"><span>&nbsp;</span>Compare</a>
                            </li>
                            <?php endif;?>
                            <!--
                            <li class="my-wheels">
                                <a href="#" class="my-wheels"><span>&nbsp;</span>Add to My Wheels</a>
                            </li>
                            -->
                        </ul>

                        <?php if($vehicles['result']):?>

                        <div class="vehicle-badge">
                            <a href="<?php echo getVehicleProfileLink($vehicles['result'][0])?>">
                                <img src="<?php echo getVehicleImageLink($vehicles['result'][0]->images[0], 84)?>" alt="<?php echo getVehicleProfileTitle($vehicles['result'][0])?>"/>
                                <p>
                                    <strong><?php echo getVehicleProfileTitle($vehicles['result'][0])?></strong>
                                    View the vehicle profile
                                </p>
                            </a>
                        </div>
                        <?php endif?>

                        <div class="mrec-ad">
                            <?php echo $ad?>
                        </div>


                </div>
            </div>

                <?php $vehicleIntroduction = unserialize($vehicleReviewMeta->introduction);
                      $whats =unserialize($vehicleReviewMeta->whats_new);
                      $performance =unserialize($vehicleReviewMeta->performance);
                      $theVerdict =unserialize($vehicleReviewMeta->the_verdict);
                ?>


            <div data-controller="ReviewNavController" class="row navigation">
                <ul id="review-nav">
                    <li class="first active">
                        <a href="#Introduction" name="introduction-row"><?php echo stripslashes((!empty($vehicleIntroduction['introduction_title'])) ? $vehicleIntroduction['introduction_title'] : "Introduction" ) ?></a>
                        <img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>
                    <li>
                        <a href="#whatsnew" name="whatsnew-row"><?php echo stripslashes((!empty($whats['whats_new_title'])) ? $whats['whats_new_title'] : "What's New") ?></a>
                        <img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>
                    <?php if($vehicles['result']): ?>
                    <li>
                        <a href="#gallery" name="gallery-row">Gallery</a>
                        <img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="#performance" name="performance-row"><?php echo stripslashes((!empty($performance['performance_title'])) ? $performance['performance_title'] : "Performance") ?></a>
                        <img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>
                    <li>
                        <a href="#specs" name="specs-row">Specs</a>
                        <img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>
                    <li>
                        <a href="#verdict" name="verdict-row"><?php echo stripslashes((!empty($theVerdict['the_verdict_title'])) ? $theVerdict['the_verdict_title'] : "The Verdict") ?></a>
                        <img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>
                    <li class="last">
                        <a href="#comments" name="comments-row">Comments</a>
                        <img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>
                </ul>
            </div>



            <div class="row intro section"><!-- begin .share-->

                <?php include 'sidebars/addthis.php' ?>
                <!-- end .share-->

                <h4><?php echo stripslashes((!empty($vehicleIntroduction['introduction_title'])) ? $vehicleIntroduction['introduction_title'] : "Introduction" ) ?></h4>

                <div class="wrap">
                    <div class="span3">
                        <div class="pos"><h5><?php echo stripslashes((!empty($vehicleIntroduction['whats_best_title'])) ? $vehicleIntroduction['whats_best_title'] : "What's Best") ?></h5>
                            <ul>
                                <?php $whatsNew = explode("\n",$vehicleIntroduction['whats_best']) ?>
                                <?php foreach ($whatsNew AS $whats_New) : ?>
                                <li><?php echo stripslashes($whats_New) ;?></li>
                                <?php endforeach; ?>

                            </ul>
                        </div>
                    </div>
                    <div class="span3">
                        <div class="pos"><h5><?php echo stripslashes((!empty($vehicleIntroduction['whats_worst_title'])) ? $vehicleIntroduction['whats_worst_title'] : "What's Worst") ?></h5>
                            <ul>

                                <?php $whatsWorst = explode("\n",$vehicleIntroduction['whats_worst']) ?>
                                <?php foreach ($whatsWorst AS $whats_Worst) : ?>
                                <li><?php echo stripslashes($whats_Worst) ;?></li>
                                <?php endforeach; ?>

                            </ul>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="pos"><h5><?php echo stripslashes((!empty($vehicleIntroduction['whats_interesting_title'])) ? $vehicleIntroduction['whats_interesting_title'] : "What's Interesting") ?></h5>

                            <p><?php echo stripslashes($vehicleIntroduction['whats_interesting']); ?></p>

                        </div>
                    </div>
                    <div class="span2">

                        <?php if($vehicles['result']):?>
                        <div class="pos">
                            <h5>Estimated Price</h5>
                            <p>$<?php echo number_format($vehicles['result'][0]->price) ?></p>
                        </div>
                        <?php endif?>

                    </div>
                </div>
            </div>
            <div class="row intro-copy">
                <div class="main">

                    <div class="img-wrap">
                        <?php the_post_thumbnail(array(492, 277))?>
                    </div>
                    <!-- begin .comparables-->

                    <?php if (!empty($relatedVehicles['result'])):?>

                    <div class="comparables"><h3>Comparables</h3>
                        <ul class="listing">

                        <?php
                        foreach($relatedVehicles['result'] as $key => $relatedVehicle):
                        ?>
                            <li<?php if($key == 3) echo ' style="clear:both"' ?> >
                                <div class="wrap">
                                    <a href="<?php echo getVehicleProfileLink($relatedVehicle) ?>">
                                        <img alt="Vehicle listing" src="<?php echo getVehicleImageLink($relatedVehicle->images[0], 132, 74); get_template_directory() ?>"/>
                                        <p><?php echo getVehicleProfileTitle($relatedVehicle) ?></p>
                                    </a>
                                </div>
                                <a data-id="" href="#" class="compare callout" rel="<?php echo $relatedVehicle->acode ?>">Compare
                                    <img alt="Compare this vehicle" src="<?php echo get_template_directory_uri() ?>/img/compare-callout.png"/>
                                </a>
                            </li>

                        <?php endforeach; ?>
                        </ul>
                    </div>
                    <!-- end .used-vehicles-->
                </div>

                <?php endif;?>

                <div class="sidebar">
                    <div class="pos">

                        <?php echo apply_filters('the_content', stripslashes($vehicleIntroduction['introduction'])) ?>

                    </div>
                </div>
            </div>
            <!-- begin .leaderboard-->

            <div class="leaderboard">
                <?php echo $adModel->getAd('728x90') ?>
            </div>
            <!-- end .leaderboard-->

            <div class="row whatsnew section">
                <h3><?php echo stripslashes((!empty($whats['whats_new_title'])) ? $whats['whats_new_title'] : "What's New") ?></h3>



                <div class="column-copy">

                    <?php echo apply_filters('the_content', stripslashes($whats['whats_new'])) ?>

                </div>
            </div>

            <?php if($vehicles['result']): $vehicle = $vehicles[result][0]; ?>
            <div class="row gallery-row section" style="margin-bottom: 20px;"><!-- begin .vehicle-gallery-->
                <div class="review-videos">
                    <h3>Gallery</h3>

                    <?php
                    $medias = $vehicle->images;

                    if (!empty($medias)):
                        ?>
                        <div data-controller="GalleryController" class="vehicle-gallery" style="margin-left: 125px;">

                            <div class="img"><img src="<?php echo getVehicleImageLink($medias[0], 556, 315) ?>" alt="Large Gallery Image" class="large"/>
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
                                                <a href='<?php echo getVehicleImageLink($media, 556, 315) ?>'>
                                                    <img src='<?php echo getVehicleImageLink($media, 101, 57) ?>' alt='' title=''/></a>
                                                <span class="caption hidden"><!-- Image Title Here --></span>
                                            </div>
                                        </div>
                                        <?php endforeach;?>

                                    </div>
                                </div>

                                <a class="nav right">Right</a>
                            </div>
                        </div>
                        <?php endif;?>
                    <!-- End .vehicle-gallery-->
                </div>
                <!-- end .vehicle-gallery-->
            </div>
            <?php endif; ?>

            <?php /*
            <div class="row"><!-- BEGIN special offers - build and price section-->
                <div class="special-offers">


                    <div class="build"><h3>Build & Price</h3><a href="#"><img src="<?php get_template_directory() ?>/img/profile/build-vehicle.jpg"
                                                                              alt="Build and Price"/>

                        <p>Build your 2012 Volkswagen Jetta TDI</p></a></div>
                    <div class="special-offer-1"><h3>Special Offers</h3><a href="#"><img
                            src="<?php get_template_directory() ?>/img/profile/offer-placeholder.jpg" alt="Offer 1"/><h4>Loyalty Offer</h4>

                        <p>1% cash back and an additional year of roadside assistance on any new Volkswagen model from
                            now until Dec 12, 2011</p></a></div>
                    <div class="special-offer-2"><a href="#"><img src="<?php get_template_directory() ?>/img/profile/offer-placeholder.jpg"
                                                                  alt="Offer 2"/><h4>Loyalty Offer</h4>

                        <p>1% cash back and an additional year of roadside assistance on any new Volkswagen model from
                            now until Dec 12, 2011</p></a></div>

                </div>
                <!--End .special-offers section-->
            </div>
            */ ?>

            <div class="row performance section">
                <div class="main"><h3><?php echo stripslashes((!empty($performance['performance_title'])) ? $performance['performance_title'] : "Performance") ?></h3>

                    <?php echo apply_filters('the_content', stripslashes($performance['performance'])) ?>

                </div>
                <div class="sidebar">

                    <div class="mrec-ad">
                        <?php echo $ad?>
                    </div>

                    <div class="pull-quote">
                        <p><?php $quote = htmlentities(strip_tags($customValues->quote)); if(!empty($quote)): ?>
                            <?php echo $quote ; ?>
                        </p>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <div class="row used"><!-- begin .used-vehicles-->

                <div class="used-vehicles used-vehicles-review-page">
                    <h3>
                        Used
                        <?php if(isset($make[0]->name)) echo $make[0]->name;?>
                        <?php if(isset($model[0]->name)) echo $model[0]->name;?>
                        <a href="http://vehicles.wheels.ca/used-cars/" target="_blank" class="view-all">All Used Vehicles</a>
                    </h3>
                    <ul class="listing">

                    </ul>
                </div>

            </div>

            <div class="row tech section">

                <?php if($vehicles['result']):?>

                <h3>Tech Specs</h3>

                <div class="data">
                    <img src="<?php echo getVehicleImageLink($vehicles['result'][0]->images[0], 492);//492x277 ?>" />

                    <div class="spec first">
                        <div class="pos"><h5>Engine</h5>
                            <ul>
                                <li><?php echo $vehicles['result'][0]->engine ?></li>
                            </ul>
                        </div>
                    </div>

                    <div class="spec">
                        <div class="pos"><h5>Power</h5>
                            <ul>
                                <li><?php echo $vehicles['result'][0]->drive_type ?></li>
                            </ul>
                        </div>
                    </div>

                    <div class="spec last">
                        <div class="pos"><h5>Fuel Consumption</h5>
                            <ul>
                                <li><?php echo $vehicles['result'][0]->fuel_type ?></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php endif?>

                <div class="mrec-ad">
                    <?php echo $ad?>
                </div>

            </div>

            <div class="row verdict section"><h4><?php echo stripslashes((!empty($theVerdict['the_verdict_title'])) ? $theVerdict['the_verdict_title'] : "The Verdict") ?></h4>

                <div class="ratings-reviews">
                    <div class="wrap">
                        <div class="rating large">
                            <?php  if ($vehicleReviewMeta->star_rating != '0.0' ) { ?>
                            <div class="value rating-<?php echo $starRating[0] ?>-<?php echo $starRating[1] ?>"><?php echo $vehicleReviewMeta->star_rating ?></div>
                            <h4>Star Rating</h4>
                            <?php } ?>
                        </div>
                        <div class="rating large">
                            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/reviews/user_rating.php') ?>
                        </div>
                        <div class="reviews"><a href="#" class="review-count"><?php comments_number('0', '1', '%') ?></a><h4><?php comments_number('Comment', 'Comment', 'Comments') ?></h4></div>
                    </div>
                </div>
                <div class="copy">

                    <?php echo apply_filters('the_content', stripslashes($theVerdict['the_verdict'])) ?>

                </div>
            </div>
            <?php endwhile; endif; ?>
             <?php
            global $withcomments, $rating;
            $withcomments = true;
            $rating = true;
            comments_template('', true) ?>

    <div class="row"><!-- begin .used-listings-->
        <?php echo wheels_esi_include(get_template_directory_uri().'/esi/answer-center.php') ?>
        <?php echo wheels_esi_include(get_template_directory_uri().'/esi/reviews/footer-reviews-widget.php') ?>
        <?php echo wheels_esi_include(get_template_directory_uri().'/esi/wheels-guides.php') ?>
    </div>

</div>

<?php if(!$vehicles['result']): ?>

</div>
<?php endif; ?>

<?php
if( isset($make[0]->name) ):
?>
<script type="text/javascript">
// DEFINE VAR FOR LOAD USED CAR
var LOAD_REVIEW_USED_CAR = true;
var VEHICLE_MAKE2 = '<?php echo $make[0]->name ?>';
var VEHICLE_MODEL2 = '<?php echo $model[0]->name ?>';
var VEHICLE_YEAR2 = '';
var VEHICLE_CLASS2 = '';
</script>
<?php endif?>

<?php get_footer()?>
</body>
</html>