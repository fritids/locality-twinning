<?php global $adModel; get_header('meta') ?>
<body class="page guides">

<!-- begin #container-->
<div id="container" data-role="page">

    <!-- begin #topads-->
    <?php get_header() ?>

    <div id="guides" class="section-container clearfix">

        <?php echo wheels_esi_include(get_template_directory_uri().'/esi/answer-center/answer-center-main.php?category='.$_GET['category']) ?>

    </div>

    <?php get_footer()?>

</body>
</html>
