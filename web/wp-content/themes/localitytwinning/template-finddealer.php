<?php
/*
 * Template Name: find dealer
 */

global $wpdb, $pageTitle;

$pageTitle = 'Vehicles & Reviews';
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);

get_header('meta');
?>

<body class="page vehicles-reviews" onLoad="initialize()">

<!-- begin #container-->
<div id="container" data-role="page" style="width: 680px">

<?php $makes = $vehicleModel->getMakes(); ?>

<!-- begin #main-->
<div id="main" role="main" class="clearfix" style="overflow: hidden;">

    <!--header ends here-->

        <div id="vehicles-reviews" class="section-container clearfix" style="width: 680px;padding: 0px 0px;overflow: hidden;">

            <div class="row">
                <?php $dealer_make = urlencode((isset($_REQUEST['dealer-make']))?$_REQUEST['dealer-make']:''); ?>
                <?php $dealer_zip = urlencode((isset($_REQUEST['dealer-zip']))?$_REQUEST['dealer-zip']:''); ?>
                <?php echo wheels_esi_include(get_template_directory_uri().'/esi/find-a-dealer-full.php?dealer-make='.$dealer_make.'&dealer-zip='.$dealer_zip ); ?>
            </div>

        </div>
        <!--footer starts here-->
</div>
</div>
<!-- end #container-->

<!-- libraries-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo get_template_directory_uri();?>/js/libs/jquery-1.7.1.min.js"><\/script>')</script>
<script>document.write('<script src="<?php echo get_template_directory_uri();?>/js/libs/jquery-ui-1.8.16.custom.min.js"><\/script>')</script>

<?php
\Emicro\Plugin\Assets::js(WP_CONTENT_URL . "/themes/wheels/js/libs/jquery.easing.1.3.js");
\Emicro\Plugin\Assets::js(WP_CONTENT_URL . "/themes/wheels/js/script.js");
\Emicro\Plugin\Assets::includeScripts();
?>

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

</body>
</html>