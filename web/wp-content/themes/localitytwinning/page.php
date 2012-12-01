<?php get_header('meta')?>
<body class="page about mobile-page"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
    <?php get_header()?>

    <div id="contact" class="section-container clearfix">
    <?php wheels_breadcrumb()?>
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); $custom_value = get_custom_values($post_id = 0)?>
        <div class="row">
            <div class="row">
                    <h2 class="title"><?php the_title()?></h2>
            </div>
            <div class="row content-row"><!-- begin .tab-section-->
                <div data-controller="TabsController" class="tab-section tabbed-content">
                    <?php the_content()?>
                </div>
                    <!-- end .tabs-section-->
            </div>
            <div class="row">
                <?php wheels_esi_include(get_template_directory_uri().'/esi/answer-center.php')?>
                <?php wheels_esi_include(get_template_directory_uri().'/esi/more-news.php')?>
                <?php wheels_esi_include(get_template_directory_uri().'/esi/wheels-guides.php')?>
            </div>
        </div>
    <?php endwhile; endif; ?>

    </div>
<!-- end #main-->
<?php get_footer()?>
</body>
</html>