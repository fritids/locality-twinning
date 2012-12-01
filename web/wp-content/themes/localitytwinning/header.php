<?php

global $wpdb, $adModel;

$adModel = new \Emicro\Model\Ad($wpdb);
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);

$classes = $vehicleModel->getClasses();
$makes = $vehicleModel->getMakes();

$submenu = new \Emicro\Model\Submenu($wpdb);
?>

<!-- begin #main-header-->
<header id="main-header" data-controller="MainHeaderController">

    <!-- begin .logo-bar-->
    <div class="logo-bar">

        <div class="logo"><a href="<?php echo site_url(); ?>">
            <img src="<?php echo get_template_directory_uri() ;?>/img/logo.png">
        </a></div>

        <a href="#" id="mediaSwitcher" onclick="switchDevice();">View Desktop</a>

        <div class="mobile-share">
            <!-- Begin AddThis Button-->
            <div class="addthis_toolbox addthis_default_style"><a class="addthis_counter addthis_pill_style"></a></div>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f47debb668d5070"></script>
            <!-- End AddThis Button-->
        </div>

        <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/top-user-panel.php') ?>

        <div class="search">
            <!--
            <div class="search-box">
                <form>
                    <label for="search-query">Search</label>
                    <input data-role="none" type="text" id="search-query" name="search-query" placeholder="Search Articles &amp; Vehicles" class="global-inner-shadow">
                    <a data-role="none" href="" class="search-link">Search</a>
                </form>
            </div>
            -->
        </div>

    </div>
    <!-- end .logo-bar-->

    <!-- begin .nav-container-->
    <div data-controller="MainNavigationController" class="nav-container">

        <!-- begin .navbar-->
        <nav id="mainnavbar" class="navbar">

            <img src="<?php echo get_template_directory_uri();?>/img/small-e-logo.png" alt="wheels.ca" class="small-brand">

            <ul>

                <li id="home-menu-item" class="home first">
                    <a href="<?php echo site_url(); ?>">প্রথম পাতা</a>
                </li>

                <li id="news-features-menu-item" data-window="bg-navprimary-menu-0" class="news-features dropdown">
                    <a href="<?php echo site_url(); ?>/news/">বার্তা</a>
                </li>

                <li id="vehicles-reviews-menu-item" data-window="bg-navprimary-menu-1" class="vehicles-reviews">
                    <a href="<?php echo site_url(); ?>/reviews/">সফল প্রকল্প সমূহ</a>
                </li>

                <li id="guides-menu-item" data-window="bg-navprimary-menu-2" class="guides">
                    <a href="<?php echo site_url(); ?>/guides/">গাইড</a>
                </li>

                <!--<li id="videos-menu-item" data-window="bg-navprimary-menu-3" class="videos dropdown">
                    <a href="#">Videos</a>
                </li>-->

            </ul>

        </nav>
        <!-- end .navbar-->

    </div>
    <!-- end .nav-container-->

</header>
<!-- end #main-header-->

<!-- begin #main-->
<div id="main" role="main" class="clearfix">
