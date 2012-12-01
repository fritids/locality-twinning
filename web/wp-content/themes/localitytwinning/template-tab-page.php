<?php
/*
 * Template Name: Tab Page
 */
$pages = get_pages('sort_column=post_date&parent='.get_the_ID());
?>
<?php get_header('meta')?>
<body class="page about mobile-page"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
    <?php get_header()?>

    <?php
    if ( have_posts() ) : while ( have_posts() ) : the_post();

        $subPages = get_posts('post_type=page&orderby=menu_order&post_parent='.get_the_ID());
    ?>

    <div id="about" class="section-container clearfix">
    <div class="row">
        <h2 class="title"><?php the_title() ?></h2>
    </div>
    <div class="row content-row"><!-- begin .tab-section-->
        <div data-controller="TabsController" class="tab-section tabbed-content"><!-- begin .tab-nav-->
            <div class="tab-nav">
                <ul>
                <?php foreach($subPages as $key => $post){ setup_postdata($post) ?>
                    <li class="<?php if($key == 0) echo 'on' ?>"><a href="#" class="<?php echo $post->post_name ?> active"><?php the_title() ?></a></li>
                <?php } wp_reset_postdata(); ?>
                </ul>
            </div>
            <!-- end .tab-nav--><!-- begin about nav for mobile only HREF LINKS MUST MATCH ABOVE-->
            <div class="mobile-about-nav">
                <select name="mobile-about-nav" data-controller="MobileTabController">
                    <?php foreach($subPages as $post){ setup_postdata($post) ?>
                    <option value="#<?php echo $post->post_name ?>"><?php the_title() ?></option>
                    <?php } wp_reset_postdata(); ?>
                </select>
            </div>
            <!-- end about nav for mobile")--><!-- begin .tabs-->
            <div class="tabs">

                <?php foreach($subPages as $post){ setup_postdata($post) ?>

                <div id="<?php echo $post->post_name ?>" class="tab">
                    <?php the_content() ?>
                </div>

                <?php } wp_reset_postdata(); ?>

            </div>
            <!-- end .tabs-->
        </div>
        <!-- end .tabs-section-->
    </div>

    <div class="row"><!-- begin .used-listings-->
        <?php echo wheels_esi_include(get_template_directory_uri().'/esi/answer-center.php') ?>
        <?php echo wheels_esi_include(get_template_directory_uri().'/esi/more-news.php') ?>
        <?php echo wheels_esi_include(get_template_directory_uri().'/esi/wheels-guides.php') ?>
    </div>

    <?php endwhile; endif; ?>
</div>
    <!-- end #main-->
    <?php get_footer()?>
</body>
</html>