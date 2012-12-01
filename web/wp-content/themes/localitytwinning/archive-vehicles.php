<?php
global $wpdb, $pageTitle, $adModel;
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);
$pageTitle = 'Vehicles';

get_header('meta');
?>
<body class="page vehicles" onLoad="initialize()">

<!-- begin #container-->
<div id="container" data-role="page">

    <!-- begin #topads-->
    <?php get_header()?>

    <div id="vehicles" class="section-container clearfix">

        <?php wheels_breadcrumb() ?>

        <div class="row">

            <h2 class="title">Vehicles</h2>

            <ul class="order-by-list">
                <li class="on"><a href="<?php echo site_url('vehicles/?type=popular')?>">Popular</a></li>
                <li><a href="<?php echo site_url('vehicles')?>">Latest</a></li>
            </ul>

            <!-- begin .tip-->
            <div class="tip">
                <strong>Tip:&nbsp;</strong>Click <img src="<?php echo get_template_directory_uri();?>/img/compare-icon-tip.png" alt="compare icon"/>to compare
            </div>
            <!-- end .tip-->

        </div>

        <?php echo wheels_esi_include( get_template_directory_uri().'/esi/vehicles/landing-vehicles-carousel.php' ) ?>

        <!-- Call esi: road test esi-->
        <div class="row road-test-row"><!-- begin .road-tests-->

            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/road-tests.php')?>

            <div class="mrec-ad">
                <?php
                $adModel = new \Emicro\Model\Ad($wpdb);
                $ad = $adModel->getAd('300x250');
                echo $ad;
                ?>
            </div>

        </div>

        <div class="row"><!-- begin .browse-vehicles-->
            <div class="browse-vehicles"><!-- begin .header-->

                <div class="header">
                    <h3>Browse Vehicles</h3>
                    <!-- begin .model-container-->
                    <div class="model-container">
                        <select data-role="none" name="model-selector" data-controller="ComboboxController" class="compare-selector ui-dark">

                            <option value="all">All</option>
                            <?php
                            global $wpdb;
                            $vehicleModel = new Emicro\Model\Vehicle($wpdb);
                            $makes = $vehicleModel->getMakes();
                            foreach($makes as $make):
                            ?>
                            <option value="<?php echo $make->makeName?>"><?php echo $make->makeName?></option>
                            <?php endforeach;?>

                        </select>
                    </div>
                    <!-- end .model-container-->

                    <!-- begin .pagination-->
                    <div class="pagination">

                    </div>
                    <!-- end .pagination-->

                </div>
                <!-- end .header-->

                <!-- begin .vehicle-navigation-->
                <div class="vehicle-navigation">
                    <div data-controller="TabsController" class="clearfix">

                        <div class="tab-nav">
                            <ul class="clearfix">
                                <li><a href="#">Category</a></li>
                                <li class="last" class="last"><a href="#">Class</a></li>
                            </ul>
                        </div>

                        <div class="tabs">

                            <div class="tab">

                                <div class="viewport">
                                    <ul class="container category-list">

                                        <?php
                                        $categories = $vehicleModel->getCategories();
                                        foreach($categories as $category):
                                            ?>
                                            <li class="slide">
                                                <div class="wrap">
                                                    <a href="#" class="title" rel="<?php echo $category?>"><?php echo $category?></a>
                                                </div>
                                            </li>
                                        <?php endforeach;?>

                                    </ul>
                                </div>

                            </div>

                            <div class="tab" style="display: none;">

                                <div class="viewport">
                                    <ul class="container class-list">

                                        <?php
                                        $classes = $vehicleModel->getClasses();
                                        foreach($classes as $class):
                                            ?>
                                            <li class="slide">
                                                <div class="wrap">
                                                    <a href="#" class="title" rel="<?php echo $class->name?>"><?php echo $class->name?></a>
                                                </div>
                                            </li>
                                        <?php endforeach;?>

                                    </ul>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- end .vehicle-navigation-->

                <!-- begin .vehicle-listing-->
                <div class="vehicle-listing">
                    <ul class="listing">

                    </ul>
                </div>
                <!-- end .vehicle-listing-->
            </div>
            <!-- end .browse-vehicles-->
        </div>

        <div class="row"><!-- begin .used-vehicles-->
            <div class="used-vehicles">
                <h3>Used Vehicles<a href="http://vehicles.wheels.ca/used-cars/" target="_blank" class="view-all">Search for more used vehicles</a></h3>
                <ul class="listing">

                </ul>
            </div>
            <!-- end .used-vehicles-->
        </div>

        <?php /*
        <div class="row last">

            <!-- begin .special-offers-->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/reviews/special-offer.php')?>
            <!-- end .special-offers-->

            <div class="mrec-ad">

                <?php echo $adModel->getAd('300x250', '', 1) ?>

            </div>

        </div>
        */ ?>

        <?php echo wheels_esi_include(get_template_directory_uri().'/esi/vehicles/landing-vehicles-readers-thought.php') ?>

        <div class="row"><!-- begin .review-videos-->
        <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/reviews/archive_reviews_review_video2.php')?>

        <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/find-a-dealer.php')?>
        </div>

        <!-- begin .leaderboard-->
        <div class="leaderboard">
            <?php echo $adModel->getAd('728x90') ?>
        </div>
        <!-- end .leaderboard-->

        <div class="row"><!-- begin .used-listings-->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/answer-center.php') ?>
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/more-news.php') ?>
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/wheels-guides.php') ?>
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

    </div>

    <script type="text/javascript">
        VEHICLE_LANDING_PAGE = true;
    </script>

    <?php get_footer()?>

    <script type="text/javascript">
        jQuery(document).ready(function(){
            loadUsedCar('', '', '', '', 'style', 6);
        });
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