<?php

global $wpdb, $pageTitle, $adModel;

$pageTitle = 'Vehicles & Reviews';
$postModel = new \Emicro\Model\Post($wpdb);
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);

get_header('meta');
?>
<body class="page vehicles-reviews" onLoad="initialize()">

<!-- begin #container-->
<div id="container" data-role="page">

    <!-- begin #topads-->
    <?php get_header()?>

    <div id="vehicles-reviews" class="section-container clearfix">

        <h2 class="title">Vehicles &amp; Reviews</h2>

        <div class="row">

            <!-- begin .latest-reviews-->
            <div class="latest-reviews">

                <h3>Latest Reviews<a href="<?php echo site_url('reviews') ?>" class="view-all">All Reviews</a></h3>

                <!-- begin .features-->
                <div class="features">

                    <div class="grid-container">
                        <?php
                        $loop = 1;
                        $args['limit'] = 5;
                        $args['post_type'] = 'reviews';
                        $latestReviews = $postModel->getAll($args);
                        echo count($latestReviews);
                        foreach ($latestReviews as $key => $post): setup_postdata($post);
                            switch($loop){
                                case 1: $elm_class = 'prime tl'; break;
                                case 2: $elm_class = 'tm'; break;
                                case 3: $elm_class = 'tr'; break;
                                case 4: $elm_class = 'bm'; break;
                                case 5: $elm_class = 'br'; break;
                            }
                            switch($loop){
                                case 1: $image_size = '216x310'; break;
                                default: $image_size = '155x154'; break;
                            }
                            ?>

                            <div class="feature-container <?php echo $elm_class ?>">
                                <?php the_post_thumbnail($image_size) ?>
                                <div class="copy">
                                    <div class="pos">
                                        <h4>
                                            <a href="<?php the_permalink() ?>"><?php the_title() ?> &raquo;
                                                <span class="author"><?php the_author() ?></span>
                                            </a>
                                        </h4>
                                    </div>
                                </div>
                                <div class="overlay">&nbsp;</div>
                            </div>

                            <?php $loop++; endforeach;?>

                    </div>

                </div>
                <!-- end .features-->

                <!-- begin .ads-->
                <div class="ads">
                    <div class="mrec-ad">
                        <?php echo $adModel->getAd('300x250') ?>
                    </div>
                </div>
                <!-- end .ads-->

            </div>
            <!-- end .latest-reviews-->
        </div>

        <?php echo wheels_esi_include(get_template_directory_uri().'/esi/vehicles/landing-vehicles-reviews-popular-vehicles.php')?>

        <div class="row"><!-- begin .used-vehicles-->
            <div class="used-vehicles">
                <h3>Used Vehicles and Dealers<a href="http://vehicles.wheels.ca/used-cars/" class="view-all" target="_blank">Search for more used vehicles and dealers</a></h3>
                <ul class="listing">

                </ul>
            </div>
            <!-- end .used-vehicles-->

            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/find-a-dealer.php')?>

        </div>

        <div class="row"><!-- begin .answer-centre-->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/answer-center.php')?>
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/wheels-video.php')?>
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/wheels-guides.php')?>
        </div>

        <?php /*
        <div class="row">

            <!-- begin .special-offers-->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/reviews/special-offer.php')?>
            <!-- end .special-offers-->

            <div class="mrec-ad">

                <?php echo $adModel->getAd('300x250', '', 1) ?>

            </div>

        </div>
        */ ?>

    </div>

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

    <?php get_footer()?>
    <script type="text/javascript">
        loadUsedCar('','','','','style_2', 6);
    </script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places"></script>
    <script type="text/javascript">
        var script = '<script type="text/javascript" src="/wp-content/plugins/wheels-dealers/googlemap/markerclusterer';
        if (document.location.search.indexOf('packed') !== -1) {
            script += '_packed';
        }
        if (document.location.search.indexOf('compiled') !== -1) {
            script += '_compiled';
        }
        script += '.js"><' + '/script>';
        document.write(script);
    </script>

    <script type="text/javascript" src="/wp-content/plugins/wheels-dealers/googlemap.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){

            $('#search-location').change(function(event, ui){
                open_map_popup();
                return false;
            });

            $('#search-location').keypress(function(e){
                  if(e.which == 13){
                      open_map_popup();
                      return false;
                  }
              });
        });

        function open_map_popup(){
            var dealer_make = $("#dealer-make option:selected").text();
            var dealer_zip = $("#search-location").val();
            $("#find-dealer-frame").attr('src','/find-dealer/?dealer-make='+dealer_make+'&dealer-zip='+dealer_zip);
            $("#popup-find-dealer").trigger($.Events.OPEN);
            return false;
        }
    </script>

</body>
</html>