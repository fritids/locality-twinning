<?php get_header('meta')?>
<body class="page about mobile-page"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
    <?php get_header()?>

    <div id="guides" class="section-container clearfix">
    <?php wheels_breadcrumb()?>

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); $custom_value = get_custom_values($post_id = 0)?>
        <div class="row">
        <?php echo wheels_esi_include(get_template_directory_uri().'/esi/answer-center/answer-center-main.php?category='.$_GET['category']) ?>

        </div>

        <?php endwhile; endif; ?>

    </div>
<!-- end #main-->
<?php get_footer()?>
</body>
</html>