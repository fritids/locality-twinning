<?php global $adModel; get_header('meta') ?>
<body class="page news-features">

<!-- begin #container-->
<div id="container" data-role="page">

    <!-- begin #topads-->
    <?php get_header() ?>

    <div id="news-features" class="section-container clearfix">

        <div class="row">
            <h2 class="title">গাইড            </h2>

        </div>

        <div class="row">
            <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/guides/archive-guides-carousel.php') ?>

        </div>

    </div>

    <?php get_footer()?>

</body>
</html>