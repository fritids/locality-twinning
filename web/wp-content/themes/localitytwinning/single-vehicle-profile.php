<?php
global $vehicle_id, $wpdb, $post, $postIdofComment, $relatedVehicles, $pageTitle, $adTagMap, $adModel;

list($year, $make, $model, $trim) = explode('-', $vehicle_id);

$postModel = new \Emicro\Model\Post($wpdb);
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);
$acode = strtoupper($vehicle_id);//$vehicleModel->getVehicleByParam($year, $make, $model, $trim);
$vehicle = $vehicleModel->getVehicleByAcode($acode);

// Set page title
$pageTitle = getVehicleProfileTitle($vehicle['result'][0]);

if($vehicle['result'])
{
    $relatedVehicles = $vehicleModel->getVehicles(array('model_id' => $vehicle['result'][0]->model_id ),0,10,'',true);
    $review = $postModel->getPostByVehicleId($acode, 'reviews');
    $comments = array();

    if($review) {
        //$secondOpinion = $postModel->getSecondOpinionByVehicleId($acode, 'second-opinion');
        $post = $review;
        $postIdofComment = $post->ID;
    }
    $adTagMap = array(
        'class' => $vehicle['result'][0]->class[0],
        'make' => $vehicle['result'][0]->make,
        'year' =>  $vehicle['result'][0]->year,
        'model' =>  $vehicle['result'][0]->model,
    );

}
?>
<?php get_header('meta')?>
<body class="page article vehicle mobile-page"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->

    <?php get_header()?>

    <div class="content">

        <div id="vehicle-profile" class="section-container clearfix">
        <?php
        if(!$vehicle['result']):
        ?>
            <h1>No vehicle found</h1>
        <?php
        else:
            $vehicle = $vehicle['result'][0];
        ?>

            <div class="vehicle-options clearfix">

                <h2 title=""><?php echo getVehicleProfileTitle($vehicle)?></h2>

                <div class="vehicle-profile-container">

                    <?php if (!empty($relatedVehicles['result'])):?>
                    <select id="temp_test_id" data-role="none" data-controller="ComboboxController" data-readonly="true" class="ui-dark vehicle-profile">
                    <?php
                    foreach($relatedVehicles['result'] as $relatedVehicle):
                        if($relatedVehicle->acode != $vehicle_id ):
                    ?>
                        <option title="<?php echo $vehicle->acode?>" value="<?php echo getVehicleProfileLink($relatedVehicle)?>"><?php echo $relatedVehicle->style?></option>
                    <?php
                        endif;
                    endforeach;
                    ?>
                    </select>
                    <?php endif;?>

                </div>

            </div>

            <div class="row"><!-- Begin .vehicle-gallery-->
                <?php
                $medias = $vehicle->images;
                if (empty($medias)) array('/wp-content/themes/wheels/img/no-car-image.jpg');
                if (!empty($medias)):
                ?>
                <div data-controller="GalleryController" class="vehicle-gallery">

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

                <div class="user-options">

                    <div class="mrec-ad">
                        <?php
                        echo  $adModel->getAd('300x250');
                        ?>
                    </div>

                    <ul id="compare-utility">
                        <li><a href="#" class="compare-vehicle" rel="<?php echo $vehicle->acode ?>"><span>&nbsp;</span>Compare </a></li>
                        <!--<li class="my-wheels"><a href="#" class="my-wheels"><span>&nbsp;</span>Add to My Wheels </a></li>-->
                    </ul>

                </div>
            </div>

            <div class="details"><!-- begin .share-->

                <?php include 'sidebars/addthis.php'?>

                <!-- end .share-->
                <h3>Details</h3>
                <div class="brief-specs">

                    <div class="price">
                        <h4>MSRP</h4>
                        <strong>$<?php echo number_format($vehicle->price)?></strong>
                        <!--
                        <a href="#" class="build-price">Build &amp; Price</a>
                        <a href="#" class="insurance-quotes">Insurance Quotes</a>
                        <a href="#" class="locate-dealer">Locate a dealer</a>
                        -->
                    </div>

                    <div class="overview">
                        <h4>Overview</h4>
                        <ul>
                            <?php
                            echo $vehicle->style;
                            /*
                            if (is_array($vehicle['overview'])):
                                foreach($vehicle['overview'] as $text):
                                    echo '<li>' .$text. '</li>';
                                endforeach;
                            endif;
                            */
                            ?>
                        </ul>
                    </div>

                </div>

                <div class="ratings-reviews">

                    <?php if($review):?>

                    <div class="wrap clearfix">

                        <div class="rating large">
                            <?php /*
                            <div class="value rating-<?php echo str_replace('.', '-', $review->star_rating)?>"><?php echo $review->editor_rating?></div>
                            <h4>Star Rating</h4>
                            */ ?>
                        </div>

                        <div class="rating large">
                            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/reviews/user_rating.php?post_id='.$review->ID) ?>
                        </div>

                        <div class="reviews">
                            <a href="#" class="review-count">
                                <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/comment-count.php?post_id='.$review->ID)?>
                            </a>
                            <h4>Comments</h4>
                        </div>
                    </div>

                    <?php endif;?>

                </div>
            </div>

            <!--
            <div class="special-offers">
                <div class="build">
                    <h3>Build & Price</h3>
                    <a href="#"><img src="<?php /*echo get_template_directory_uri();*/?>/img/profile/build-vehicle.jpg" alt="Build and Price"/>
                        <p>Build your 2012 Volkswagen Jetta TDI</p>
                    </a></div>
                <div class="special-offer-1">
                    <h3>Special Offers</h3>
                    <a href="#"><img src="<?php /*echo get_template_directory_uri();*/?>/img/profile/offer-placeholder.jpg" alt="Offer 1"/>
                        <h4>Loyalty Offer</h4>
                        <p>1% cash back and an additional year of roadside assistance on any new Volkswagen model from now until Dec 12, 2011</p>
                    </a></div>
                <div class="special-offer-2"><a href="#"><img src="<?php /*echo get_template_directory_uri();*/?>/img/profile/offer-placeholder.jpg" alt="Offer 2"/>
                    <h4>Loyalty Offer</h4>
                    <p>1% cash back and an additional year of roadside assistance on any new Volkswagen model from now until Dec 12, 2011</p>
                </a></div>
            </div>
            -->

            <div data-controller="TabsController" class="tab-section clearfix">
                <div class="tab-nav">
                    <ul class="clearfix">

                        <?php if($review):?>
                        <li class="reviews"><a class="reviews">Reviews</a></li>
                        <?php endif; ?>
                        <li class="specifications"><a class="specifications">Specifications</a></li>

                    </ul>
                </div>
                <div class="find-a-dealer clearfix">

                <?php echo wheels_esi_include(get_template_directory_uri().'/esi/find-a-dealer-short.php'); ?>

                </div>

                <div class="tabs">

                    <?php if($review):?>

                    <div id="review" class="tab clearfix" style="height:auto">
                        <div class="review-blurb">
                            <div class="wrap">
                                <h3 class="star">Our Review</h3>
                                <h4><?php echo $review->post_title?></h4>
                                <?php echo get_the_post_thumbnail($review->ID, array(276,155), array('class'=>'review-header'))?>

                                <div class="author-info">
                                    <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.$review->post_author.'&size=81') ?>
                                    <a href="<?php echo get_author_posts_url($review->post_author);?>" class="primary">
                                        <?php echo get_the_author_meta('display_name', $review->post_author)?>
                                    </a>
                                    <span class="date"><?php echo get_the_date()?></span>
                                </div>

                                <?php /*
                                <div class="rating small">
                                    <div class="value rating-<?php echo str_replace('.', '-', $review->star_rating)?>"><?php echo $review->star_rating?></div>
                                </div>
                                */?>

                            </div>
                        </div>

                        <div class="opinions-reviews">
                            <div class="reader-reviews" style="padding-top: 48px">

                                <?php echo character_limiter( strip_tags( html_entity_decode( $review->post_content), '<p><br>') , 1000, '...') ?>

                                <a href="<?php echo site_url() .'/reviews/'. $review->post_name?>" class="primary full">Read the full review</a>

                            </div>
                        </div>
                    </div>

                    <?php endif?>

                    <div id="specifications" class="tab">

                        <div data-controller="TabsController" class="clearfix tab-section">
                            <div class="tab-nav">
                                <ul class="clearfix">
                                    <li><a>Consumer</a></li>
                                    <?php
                                    /*
                                    if(is_array($vehicle['specs'])):
                                    $last = key( end($vehicle['specs']) );
                                    foreach($vehicle['specs'] as $type => $categories):
                                    */?><!--
                                    <li<?php /*if ($last == $name) ' class="last"'*/?>><a><?php /*echo ucfirst( str_replace('-', ' ', $type) )*/?></a></li>
                                    --><?php /*endforeach;
                                    endif;
                                    */

                                    ?>
                                </ul>
                            </div>
                            <div class="tabs"><!-- begin .tab.consumer-->

                                <div class="tab consumer"><!-- begin ul.acc-menu-->

                                    <ul data-controller="AccordionController" class="acc-menu">

                                        <li class="open clearfix"><!-- begin .section-head-->
                                            <div class="section-head">
                                                <h5><a href="#" class="heading">Performance</a></h5>
                                            </div>
                                            <!-- end .section-head--><!-- begin .collapsible-->
                                            <div class="collapsible clearfix">

                                                <dl>

                                                    <dt><span>Engine</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->engine)?></span></dd>

                                                    <dt><span>Horsepower</span></dt>
                                                    <dd><span><?php echo $vehicleModel->getHorsePowerFormattedValue($vehicle) ?></span></dd>

                                                    <dt><span>Transmission</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->transmission)?></span></dd>

                                                    <dt><span>Drive Type</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->drive_type)?></span></dd>

                                                    <dt><span>Cylinder</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->cylinder)?></span></dd>

                                                    <dt><span>Transmission Speed</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->transmission_speed)?></span></dd>

                                                </dl>

                                            </div>
                                            <!-- end .collapsible-->
                                        </li>

                                        <li class="clearfix"><!-- begin .section-head-->
                                            <div class="section-head">
                                                <h5><a href="#" class="heading">Fuel Economy</a></h5>
                                            </div>
                                            <!-- end .section-head--><!-- begin .collapsible-->
                                            <div class="collapsible clearfix">

                                                <dl>

                                                    <dt><span>City</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->fuel_economy_city)?> L/100KM</span></dd>

                                                    <dt><span>Highway</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->fuel_economy_highway)?> L/100KM</span></dd>

                                                    <dt><span>Fuel Type</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->fuel_type)?></span></dd>

                                                    <dt><span>Fuel Tank Low/High</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->fuel_tank_low .'/'. $vehicle->fuel_tank_high)?></span></dd>

                                                </dl>

                                            </div>
                                            <!-- end .collapsible-->
                                        </li>

                                        <li class="clearfix"><!-- begin .section-head-->
                                            <div class="section-head">
                                                <h5><a href="#" class="heading">Safety</a></h5>
                                            </div>
                                            <!-- end .section-head--><!-- begin .collapsible-->
                                            <div class="collapsible clearfix">

                                                <dl>

                                                    <dt><span>Airbags</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->safety_airbags)?></span></dd>

                                                    <dt><span>ABS Brakes</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->safety_abs_brakes)?></span></dd>

                                                    <dt><span>Traction Control</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->safety_traction_control)?></span></dd>

                                                    <dt><span>Stability Control</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->safety_stability_control)?></span></dd>

                                                    <dt><span>Safety Rating</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->safety_safety_rating)?></span></dd>

                                                    <dt><span>Child Sensor</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->safety_airbags)?></span></dd>

                                                    <dt><span>Park Distance Control</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->safety_airbags)?></span></dd>

                                                </dl>

                                            </div>
                                            <!-- end .collapsible-->
                                        </li>

                                        <li class="clearfix"><!-- begin .section-head-->
                                            <div class="section-head">
                                                <h5><a href="#" class="heading">Comfort & Convenience</a></h5>
                                            </div>
                                            <!-- end .section-head--><!-- begin .collapsible-->
                                            <div class="collapsible clearfix">

                                                <dl>

                                                    <dt><span>Sunroof</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_sunroof)?></span></dd>

                                                    <dt><span>Air Conditioning</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_air_conditioning)?></span></dd>

                                                    <dt><span>Power Windows</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_power_windows)?></span></dd>

                                                    <dt><span>Power Door Locks</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_power_door_locks)?></span></dd>

                                                    <dt><span>Leather Seats</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_leather_seats)?></span></dd>

                                                    <dt><span>Power Seats</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_power_seats)?></span></dd>

                                                    <dt><span>Music System</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_music_cd_in_dash)?></span></dd>

                                                    <dt><span>Navigation System</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_navigation_system)?></span></dd>

                                                    <dt><span>Cruise</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_cruise)?></span></dd>

                                                    <dt><span>Keyless Entry</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_keyless_entry)?></span></dd>

                                                    <dt><span>Rain Sensing Wipers</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_rain_sensing_wipers)?></span></dd>

                                                    <dt><span>Heated Seats</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_heated_seats)?></span></dd>

                                                    <dt><span>Climate Control</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_climate_control)?></span></dd>

                                                    <dt><span>Steering Wheel Controls</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_steering_wheel_control)?></span></dd>

                                                    <dt><span>Power Mirrors</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_power_mirrors)?></span></dd>

                                                </dl>

                                            </div>
                                            <!-- end .collapsible-->
                                        </li>

                                        <li class="clearfix"><!-- begin .section-head-->
                                            <div class="section-head">
                                                <h5><a href="#" class="heading">Interior</a></h5>
                                            </div>
                                            <!-- end .section-head--><!-- begin .collapsible-->
                                            <div class="collapsible clearfix">

                                                <dl>

                                                    <dt><span>Max. Seating</span></dt>
                                                    <dd><span><?php echo $vehicleModel->getSeatingFormattedValue($vehicle)?></span></dd>

                                                    <dt><span>Number of Doors</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->number_of_doors)?></span></dd>

                                                    <dt><span>Seats</span></dt>
                                                    <dd><span><?php echo $vehicleModel->getSeatingFormattedValue($vehicle)?></span></dd>

                                                    <dt><span>Power Adjustable Seats</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_power_adjustable_seats)?><?php /*echo $vehicleModel->vehicleFormatValue($vehicle->safety_airbags)*/?></span></dd>

                                                    <dt><span>Interior</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->safety_airbags)?></span></dd>

                                                    <dt><span>Leather Seats</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_leather_seats)?></span></dd>

                                                    <dt><span>Power Seats</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_power_seats)?></span></dd>

                                                    <dt><span>Heated Seats</span></dt>
                                                    <dd><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->comfort_heated_seats)?></span></dd>

                                                </dl>

                                            </div>
                                            <!-- end .collapsible-->
                                        </li>

                                        <li class="clearfix"><!-- begin .section-head-->
                                            <div class="section-head">
                                                <h5><a href="#" class="heading">Awards</a></h5>
                                            </div>
                                            <!-- end .section-head--><!-- begin .collapsible-->
                                            <div class="collapsible clearfix">

                                                <dl>

                                                    <dt><span>Awards</span></dt>
                                                    <dd style="width: auto;"><span><?php echo $vehicleModel->vehicleFormatValue($vehicle->awards)?></span></dd>

                                                </dl>

                                            </div>
                                            <!-- end .collapsible-->
                                        </li>

                                    </ul>
                                    <!-- end ul.acc-menu-->
                                </div>
                                <!-- end .tab.standard-equipment-->

                            </div>
                        </div>

                    </div>

                </div>
            </div>

        <div data-controller="TabsController" class="social">
            <h3>The Word</h3>
            <div class="twitter-tags tab-nav">
                <ul class="clearfix">
                    <li><a id="make_tags" href="#" class="tag">#</a></li>
                    <li><a id="model_tags" href="#" class="tag">#</a></li>
                </ul>
                <!--<a href="https://twitter.com/#!/VWCanada" class="follow" target="_blank">Follow @VWCanada</a>-->
            </div>
                <div class="tabs">
                    <div data-controller="SlidesController" class="twitter-feed tab">
                        <div class="tweet-navigation navigation"><a class="nav left">Left</a><a class="nav right">Right</a></div>
                        <div class="tweets viewport">
                            <div class="tweet-container container clearfix">
                                <div class="tweet slide">
                                    <div id="make_slide_wrap_0" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                                <div class="tweet slide">
                                    <div id="make_slide_wrap_1" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                                <div class="tweet slide">
                                    <div id="make_slide_wrap_2" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                                <div class="tweet slide">
                                    <div id="make_slide_wrap_3" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                                <div class="tweet slide">
                                    <div id="make_slide_wrap_4" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                                <div class="tweet slide">
                                    <div id="make_slide_wrap_5" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                                <div class="tweet slide">
                                    <div id="make_slide_wrap_6" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                                <div class="tweet slide">
                                    <div id="make_slide_wrap_7" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="sponsor">Sponsored</a>
                    </div>
                    <div data-controller="SlidesController" class="twitter-feed tab">
                        <div class="tweet-navigation navigation"><a class="nav left">Left</a><a class="nav right">Right</a></div>
                        <div class="tweets viewport">
                            <div class="tweet-container container clearfix">
                                <div class="tweet slide">
                                    <div id="model_slide_wrap_0" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                                <div class="tweet slide">
                                    <div id="model_slide_wrap_1" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                                <div class="tweet slide">
                                    <div id="model_slide_wrap_2" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                                <div class="tweet slide">
                                    <div id="model_slide_wrap_3" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                                <div class="tweet slide">
                                    <div id="model_slide_wrap_4" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                                <div class="tweet slide">
                                    <div id="model_slide_wrap_5" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                                <div class="tweet slide">
                                    <div id="model_slide_wrap_6" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                                <div class="tweet slide">
                                    <div id="model_slide_wrap_7" class="wrap">
                                        Loading...
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="sponsor">Sponsored</a>
                    </div>
                </div>
            </div>

            <?php /*
            <div class="media clearfix">

                <div class="video-player" style="background-color: #FFFFFF !important;">
                 <?php echo $adModel->getAd('300x250');; ?>
                </div>

                <?php if($review) : ?>
                <div class="pull-quote">
                    <p><?php echo stripcslashes($review->quote); ?></p>
                </div>
                <?php endif; ?>
            </div>
            */ ?>

            <?php
                global $withcomments, $rating;
                $withcomments = true;
                $rating = true;
            ?>
            <!-- Call comment esi to include comment list and form -->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/comments.php?'.http_build_query($_GET).'&post_id='.$postIdofComment.'&redirect='.urlencode( $_SERVER['REQUEST_URI'] ).'&rating=true' ) ?>

            <!-- Call esi: similar option -->
            <?php $class = ( isset($vehicle->class[0])) ? $vehicle->class[0] : 0;?>
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/similar-vehicle-option.php?class='.urlencode($class).'&year='.$vehicle->year.'&make='.$vehicle->make) ?>

            <!-- Call esi: used car listing 2 -->
            <div class="module used-listings clearfix">
                <h3>Used Listings</h3>
                <span class="metroland">Powered By Metroland</span>
                <ul class="listing">
                </ul>
                <a class="primary" href="http://vehicles.wheels.ca/used-cars/" target="_blank">More</a>
            </div>

            <!-- Call esi: more news 2 -->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/more-news2.php?post_id='.get_the_ID()) ?>

            <div class="leaderboard">
                <?php echo $adModel->getAd('728x90'); ?>
            </div>

            <!-- Call esi: contest-poll -->
            <?php //echo wheels_esi_include(get_template_directory_uri().'/esi/contest-poll.php?post_id='.get_the_ID()) ?>

            <!-- Call esi: special : electric car -->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/special.php') ?>

            <!-- Call esi: popular alternative fuel cars-->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/featured_event.php?post_id='.get_the_ID()) ?>

        <?php endif;?>

    </div>

    </div>
    <!-- end #main-->

    <!-- begin #modal-screens-->
    <div data-controller="ModalController">
        <!-- begin #popup find dealer.modal-->
        <div id="popup-find-dealer" style="display: none;" class="modal" data-controller="ModalController">
            <div class="content">
                <iframe id="find-dealer-frame" src ="/find-dealer/" frameborder="0" width="680" height="470" scrolling="no" style="border-bottom:none;border-top: none;border-left: none; overflow:hidden;overflow-y:hidden;margin: 0 0;padding: 0 0;">
                <p>No content found</p>
                </iframe>
                <a href="#" class="close">X</a>
            </div>
            <div class="mask"></div>
        </div>
        <!-- end #popup find dealer.modal-->
    </div>
    <!-- end ##modal-screens-->

    <?php get_footer();?>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#form-find-dealer").submit(function(){
                open_map_popup();
            });

            $("#dealer-search-go").click(function(){
                open_map_popup();
            });

            function open_map_popup(){
                var dealer_make = '<?php echo $vehicle->make; ?>';
                var dealer_zip = $("#dealer-search-zip").val();
                $("#find-dealer-frame").attr('src','/find-dealer/?dealer-make='+dealer_make+'&dealer-zip='+dealer_zip);
                $("#popup-find-dealer").trigger($.Events.OPEN);
                return false;
            }

        });
    </script>

    <script type="text/javascript">

        var LOAD_VEHICLE_PAGE_USED_CAR = true;
        var VEHICLE_MAKE3 = '<?php echo $vehicle->make ?>';
        var VEHICLE_MODEL3 = '<?php echo $vehicle->model ?>';
        var VEHICLE_YEAR3 = '<?php echo $vehicle->year ?>';
        var VEHICLE_CLASS3 = '<?php echo $vehicle->class[0] ?>';

        loadTwitterWidget(VEHICLE_MAKE3, VEHICLE_MODEL3);
    </script>

    <script type="text/javascript">
        var profileText = $("#vehicle-profile .vehicle-options h2").text();
        _gaq.push(['_trackEvent', 'Vehicle Profile Page', 'Page Views', profileText]);
         _gaq.push(['_trackEvent', 'Vehicle Profile - Make', 'Page Views', VEHICLE_MAKE3]);
        _gaq.push(['_trackEvent', 'Vehicle Profile - Model', 'Page Views', VEHICLE_MODEL3]);
    </script>
</body>
</html>