<?php

global $wpdb, $adModel;
$postModel = new \Emicro\Model\Post($wpdb);

get_header('meta');

?>
<body class="page news-features">

<!-- begin #container-->
<div id="container" data-role="page">

    <!-- begin #topads-->
    <?php get_header()?>

    <!-- begin news article-->
    <div id="news-features" class="section-container clearfix">

        <div class="row">
            <h2 class="title">News</h2>
        </div>

        <div class="row">
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/news/landing-news-latest-carousel.php') ?>
        </div>

        <div class="row">
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/news/landing-news-latest-list.php') ?>
        </div>


    </div>
    <!-- end #main-->

    <?php get_footer();?>

</body>
</html>