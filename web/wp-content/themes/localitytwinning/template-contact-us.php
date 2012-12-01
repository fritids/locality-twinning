<?php
/*
 * Template Name: Contact Us
 */
$pages = get_pages('sort_column=post_date&parent='.get_the_ID());
?>
<?php get_header('meta')?>
<body class="page contact mobile-page"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
    <?php get_header()?>

    <div id="contact" class="section-container clearfix">

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); $custom_value = get_custom_values($post_id = 0)?>

        <div class="row">
            <h2 class="title"><?php the_title()?></h2>
        </div>
        <div class="row content-row"><!-- begin .tab-section-->
            <div data-controller="TabsController" class="tab-section tabbed-content"><!-- begin .tab-nav-->
                <div class="tab-nav">
                    <ul>

                        <?php foreach($pages as $post):?>
                        <li class="on">
                            <a href="#" class="<?php echo $post->post_name?> active"><?php echo $post->post_title?></a>
                        </li>
                        <?php endforeach;?>

                    </ul>
                </div>
                <!-- end .tab-nav--><!-- begin contact nav for mobile only HREF LINKS MUST MATCH ABOVE-->
                <div class="mobile-contact-nav">
                    <select name="mobile-contact-nav" data-controller="MobileTabController">

                        <?php foreach($pages as $post):?>
                        <li class="on">
                            <option value="#<?php echo $post->post_name?>"><?php echo $post->post_title?></option>
                        </li>
                        <?php endforeach;?>

                    </select>
                </div>
                <!-- end contact nav for mobile")--><!-- begin .tabs-->
                <div class="tabs">

                    <?php foreach($pages as $post): setup_postdata($post);?>
                    <div id="<?php echo $post->post_name?>" class="tab">
                        <?php the_title()?>
                    </div>
                    <?php endforeach;?>

                </div>
                <!-- end .tabs-->
            </div>
            <!-- end .tabs-section-->
        </div>
        <?php endwhile; endif; ?>

    </div>
    <!-- end #main-->
    <?php get_footer()?>
</body>
</html>