<?php get_header('meta'); ?>
<body class="page home mobile-page">

<!-- begin #container-->
<div id="container" data-role="page">

    <!-- begin #topads-->
    <?php get_header()?>

    <div class="content">

        <div id="home" class="section-container">

        <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/news/breaking.php') ?>

        <div class="row">
            <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/news/home-news-carousel.php') ?>
            <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/vehicles/homepage-vehicle-finder-block.php') ?>
        </div>

        <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/guides/homepage-latest-guides.php') ?>

        <div class="row">

            <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/our-experts.php') ?>

            <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/answer-center/home-answer-center-widget.php') ?>

        </div>

    </div>

    <?php get_footer() ?>

</body>