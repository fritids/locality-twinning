<?php

global $wpdb, $adModel;
$postModel = new \Emicro\Model\Post($wpdb);

get_header('meta');

?>
<body class="page events"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
<?php get_header()?>

        <div id="events" class="section-container clearfix">
            <?php wheels_breadcrumb() ?>

            <div class="row">
                <h2 class="title">Events</h2>
            </div>

            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/events/landing-events-latest-carousel.php') ?>


            <div class="row">
                <?php echo wheels_esi_include(get_template_directory_uri().'/esi/events/landing-events-event-calender.php') ?>
                <div class="mrec-ad">
                    <?php echo $adModel->getAd('300x250') ?>
                </div>
            </div>

            <div class="row"><!-- Begin Social Section-->

                <?php echo wheels_esi_include(get_template_directory_uri().'/esi/events/landing-events-twitter-widget.php') ?>

            </div>

            <div class="row"><!-- begin .used-listings-->
                <?php echo wheels_esi_include(get_template_directory_uri().'/esi/answer-center.php')?>
                <?php echo wheels_esi_include(get_template_directory_uri().'/esi/more-news.php')?>
                <?php echo wheels_esi_include(get_template_directory_uri().'/esi/wheels-guides.php')?>
            </div>

        </div>
    <!-- end #main-->

    <!-- begin footer#footer-->

<?php get_footer();?>

    <script type="text/javascript">
        <?php $options = get_option( 'wheels_theme_options' ); ?>
        var VEHICLE_MAKE3 = '<?php echo $options['event_twitter_tag_2'] ?>';
        var VEHICLE_MODEL3 = '<?php echo $options['event_twitter_tag_1'] ?>';

        loadTwitterWidget(VEHICLE_MAKE3, VEHICLE_MODEL3, true);
    </script>
</body>
</html>