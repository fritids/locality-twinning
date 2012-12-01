<?php

global $vehicle_id, $wpdb, $post, $adModel;

$postModel = new \Emicro\Model\Post($wpdb);
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);

$vehicleReviewMeta = $postModel->getVehicleReviewData(get_the_ID());
$customValues = $postModel->getCustomValues(get_the_ID());
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
                <div class="main"><h2 class="title"><?php the_title()?></h2>

                    <p class="excerpt"><?php echo apply_filters('the_content', stripslashes($post->post_excerpt));?></p>

                    <div class="author-info">
                        <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.get_the_author_meta('ID').'&size=81') ?>
                        <a href="#" class="primary"><?php the_author()?></a>
                        <span>Special to the Star</span>
                    </div>
                    <!-- Begin .vehicle-gallery-->
                    <?php
                if(function_exists('wheels_get_gallery_assets')):
                $medias = wheels_get_gallery_assets(get_the_ID());
                if(!empty($medias)):
                ?>
                    <div data-controller="GalleryController" class="vehicle-gallery">
                        <div class="img"><img src="<?php echo $medias[0]->url?>" alt="<?php echo $medias[0]->title?>" class="large" width="556" height="315"/>
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
                            <a class="nav right">Right</a></div>
                    </div>
                    <!-- End .vehicle-gallery-->
                <?php endif; endif; ?>
                </div>
                <div class="sidebar">
                    <div class="ratings-reviews">
                        <div class="wrap">
                            <div class="rating large">
                                <?php $starRating = explode('.', $vehicleReviewMeta->star_rating); ?>
                                <div class="value rating-<?php echo $starRating[0] ?>-<?php echo $starRating[1] ?>"><?php echo $vehicleReviewMeta->star_rating ?></div>
                                <h4>Star Rating</h4></div>
                            <div class="rating large">
                                <?php $userRating = explode('.', $vehicleReviewMeta->user_rating); ?>
                                <div class="value rating-<?php echo $userRating[0] ?>-<?php echo $userRating[1] ?>"><?php echo $vehicleReviewMeta->user_rating ?></div>
                                <h4>Consumer Rating</h4></div>
                            <div class="reviews">
                                <a href="#" class="review-count"><?php comments_number('0', '1', '%') ?></a>
                                <h4><?php comments_number('Comment', 'Comment', 'Comments') ?></h4></div>
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
                        <a href="#Introduction" name="introduction-row">
                            <?php echo stripslashes((!empty($vehicleIntroduction['introduction_title'])) ? $vehicleIntroduction['introduction_title'] : "Introduction" ) ?>
                        </a>
                        <img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>

                    <li>
                        <a href="#whatsnew" name="whatsnew-row">
                            <?php echo stripslashes((!empty($whats['whats_new_title'])) ? $whats['whats_new_title'] : "What's New") ?>
                        </a>
                        <img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>

                    <li>
                        <a href="#gallery" name="gallery-row">Gallery</a>
                        <img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>

                    <li>
                        <a href="#performance" name="performance-row">
                        <?php echo stripslashes((!empty($performance['performance_title'])) ? $performance['performance_title'] : "Performance") ?>
                        </a><img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>

                    <li>
                        <a href="#specs" name="specs-row">Specs</a>
                        <img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>

                    <li>
                        <a href="#verdict" name="verdict-row">
                        <?php echo stripslashes((!empty($theVerdict['the_verdict_title'])) ? $theVerdict['the_verdict_title'] : "The Verdict") ?>
                        </a><img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>

                    <li class="last">
                        <a href="#comments" name="comments-row">Comments</a>
                        <img src="<?php echo get_template_directory_uri() ?>/img/review-nav-nub.png" alt="active"/>
                    </li>

                </ul>
            </div>

            <div class="row intro section"><!-- begin .share-->
                <div class="share"><!-- Begin AddThis Button-->
                    <div class="addthis_toolbox addthis_default_style"><a class="addthis_counter"></a></div>
                    <script type="text/javascript"
                            src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f47debb668d5070"></script>
                    <!-- End AddThis Button--></div>
                <!-- end .share--><h4><?php echo stripslashes((!empty($vehicleIntroduction['introduction_title'])) ? $vehicleIntroduction['introduction_title'] : "Introduction" ) ?></h4>

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
                        <div class="pos"><h5>Estimated Price</h5>

                            <p>$<?php echo number_format($vehicles['result'][0]->price) ?></p>
                        </div>
                     <?php endif?>
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
                            <?php foreach($relatedVehicles['result'] as $relatedVehicle): ?>

                            <li>
                                <div class="wrap">
                                    <a href="<?php echo getVehicleProfileLink($relatedVehicle) ?>">
                                        <img alt="Vehicle listing" src="<?php echo getVehicleImageLink($relatedVehicle->images[0], 132, 74); echo get_template_directory_uri() ?>"/>
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
            <div class="leaderboard"><a href="#" target="_blank"><img src="<?php echo get_template_directory_uri() ?>/img/ads/leaderboard.png" width="728" height="90" alt=""/></a></div>
            <!-- end .leaderboard-->
            <div class="row whatsnew section"><h3><?php echo stripslashes((!empty($whats['whats_new_title'])) ? $whats['whats_new_title'] : "What's New") ?></h3>

                <div class="column-copy">
                    <?php echo apply_filters('the_content', stripslashes($whats['whats_new'])) ?>
                </div>
            </div>
            <div class="row gallery-row section"><!-- begin .vehicle-gallery-->
                <div class="review-videos"><h3>Gallery</h3>

                    <div class="video-container">&nbsp;</div>
                </div>
                <!-- end .vehicle-gallery--></div>
            <div class="row"><!-- BEGIN special offers - build and price section-->
                <div class="special-offers">
<!--                    <div class="build"><h3>Build & Price</h3><a href="#"><img src="/img/profile/build-vehicle.jpg"-->
<!--                                                                              alt="Build and Price"/>-->
<!---->
<!--                        <p>Build your 2012 Volkswagen Jetta TDI</p></a></div>-->
<!--                    <div class="special-offer-1"><h3>Special Offers</h3><a href="#"><img-->
<!--                            src="/img/profile/offer-placeholder.jpg" alt="Offer 1"/><h4>Loyalty Offer</h4>-->
<!---->
<!--                        <p>1% cash back and an additional year of roadside assistance on any new Volkswagen model from-->
<!--                            now until Dec 12, 2011</p></a></div>-->
<!--                    <div class="special-offer-2"><a href="#"><img src="/img/profile/offer-placeholder.jpg"-->
<!--                                                                  alt="Offer 2"/><h4>Loyalty Offer</h4>-->
<!---->
<!--                        <p>1% cash back and an additional year of roadside assistance on any new Volkswagen model from-->
<!--                            now until Dec 12, 2011</p></a></div>-->
                </div>
                <!--End .special-offers section--></div>
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
                <div class="used-vehicles"><h3>Used BMW 3 Series<a href="#" class="view-all">All Used Vehicles</a></h3>
                    <ul class="listing">

                    </ul>
                </div>
                <!-- end .used-vehicles--><!-- begin .find-dealer-->
                <?php /*
                <div data-controller="FindADealerController" class="find-dealer"><h3>Find A Dealer</h3>

                    <div class="form-container">
                        <form>
                            <fieldset>
                                <ol>
                                    <li><label for="search-reviews">Make</label><!-- begin .make-container-->
                                        <div class="make-container"><select data-role="none" name="make-selector"
                                                                            data-controller="ComboboxController"
                                                                            data-readonly="true"
                                                                            class="compare-selector find-dealer-selector ui-dark">
                                            <option>Make</option>
                                            <option>Acura</option>
                                            <option>Audi</option>
                                            <option>BMW of North America</option>
                                            <option>Buick</option>
                                            <option>Cadillac</option>
                                            <option>Chevrolet</option>
                                            <option>Chrysler</option>
                                            <option>Dodge</option>
                                            <option>Eagle</option>
                                            <option>Ferrari</option>
                                            <option>Ford</option>
                                            <option>General Motors</option>
                                            <option>GMC</option>
                                            <option>Honda</option>
                                            <option>Hummer</option>
                                            <option>Hyundai</option>
                                            <option>Infiniti</option>
                                            <option>Isuzu</option>
                                            <option>Jaguar</option>
                                            <option>Jeep</option>
                                            <option>Kia</option>
                                            <option>Lamborghini</option>
                                            <option>Land Rover</option>
                                            <option>Lexus</option>
                                            <option>Lincoln</option>
                                            <option>Lotus</option>
                                            <option>Mazda</option>
                                            <option>Mercedes-Benz</option>
                                            <option>Mercury</option>
                                            <option>Mitsubishi</option>
                                            <option>Nissan</option>
                                            <option>Oldsmobile</option>
                                            <option>Peugeot</option>
                                            <option>Pontiac</option>
                                            <option>Porsche</option>
                                            <option>Saab</option>
                                            <option>Saturn</option>
                                            <option>Subaru</option>
                                            <option>Suzuki</option>
                                            <option>Toyota</option>
                                            <option>Volkswagen</option>
                                            <option>Volvo</option>
                                        </select></div>
                                        <!-- end .make-container--></li>
                                    <li><label for="search-location">City, Postal Code</label><input data-role="none"
                                                                                                     type="text"
                                                                                                     id="search-location"
                                                                                                     name="search-location"
                                                                                                     placeholder="Toronto, ON"
                                                                                                     class="global-inner-shadow full"/>
                                    </li>
                                </ol>
                            </fieldset>
                        </form>
                    </div>
                    <div id="map_canvas"></div>
                </div>
                <!-- end .find-dealer-->
                */ ?>
            </div>
            <div class="row tech section">
                <?php if($vehicles['result']):?>
                <h3>Tech Specs</h3>

                <div class="data"><img src="<?php echo getVehicleImageLink($vehicles['result'][0]->images[0], 492);//492x277 ?>" />

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
            <div class="row verdict section">
                <h4><?php echo stripslashes((!empty($theVerdict['the_verdict_title'])) ? $theVerdict['the_verdict_title'] : "The Verdict") ?></h4>

                <div class="ratings-reviews">
                    <div class="wrap">
                        <div class="rating large">
                            <div class="value rating-<?php echo $starRating[0] ?>-<?php echo $starRating[1] ?>"><?php echo $vehicleReviewMeta->star_rating ?></div>
                            <h4>Star Rating</h4></div>
                        <div class="rating large">
                            <?php $userRating = explode('.', $vehicleReviewMeta->user_rating); ?>
                                <div class="value rating-<?php echo $userRating[0] ?>-<?php echo $userRating[1] ?>"><?php echo $vehicleReviewMeta->user_rating ?></div>
                            <h4>Consumer Rating</h4></div>
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
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/more-news.php') ?>
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/wheels-guides.php') ?>
            </div>

            </div>
<?php get_footer()?>
</body>
</html>