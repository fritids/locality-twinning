<?php get_header('meta')?>
<body class="page article guide"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
    <?php get_header()?>
    <!-- begin news article-->
    <div id="guide-article" class="section-container">
        <?php wheels_breadcrumb()?>

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
            $postModel = new \Emicro\Model\Post($wpdb);
            $custom_value = $postModel->getCustomValues(get_the_ID());
        ?>
        <div class="row">

            <div class="main-content heading">
                <div class="news-article-title">
                    <h2 class="title"><?php the_title()?></h2>
                    <p><?php echo $post->post_excerpt;?></p>
                    <span>Published <?php the_date()?></span>
                </div>
            </div>

            <div class="right-column author-and-comments">
                <!--author info-->
                <div class="author-info">
                    <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.get_the_author_meta('ID').'&size=81') ?>
                    <a href="<?php echo get_the_author_meta('url')?>" class="primary"><?php the_author()?></a>
                    <span class="publication"><?php echo get_cimyFieldValue(get_the_author_meta('ID'), 'AUTHORBYLINE', false) ?></span>
                </div>

                <div class="ratings-reviews">
                    <div class="wrap">
                        <div class="reviews">
                            <a href="#comment-container" class="review-count"><?php echo wheels_esi_include(get_template_directory_uri() . '/esi/comment-count.php?post_id='.get_the_ID())?></a>
                            <h4><?php comments_number('Comment', 'Comment', 'Comments') ?> </h4>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <?php
        if(function_exists('wheels_get_gallery_assets')):
            $medias = wheels_get_gallery_assets(get_the_ID());
            if(!empty($medias)):
                ?>
        <div class="row">

            <div class="main-content"><!-- Begin .vehicle-gallery-->

                <div data-controller="GalleryController" class="vehicle-gallery">

                    <div class="img"><img src="<?php echo $medias[0]->url?>" alt="<?php echo stripcslashes($medias[0]->title) ?>" class="large" />
                        <div class="overlay-container">
                            <div class="copy">
                                <div class="pos">&nbsp;</div>
                            </div>
                        </div>
                    </div>

                    <div data-controller="SlidesController" class="gallery-nav">
                        <a class="nav left">Left</a>
                        <div class="viewport">
                            <div class="container clearfix">

                                <?php foreach($medias as $media):?>
                                <div class="slide galleryContent">
                                    <div class="wrap">
                                        <a href='<?php echo $media->url?>'>
                                            <img src='<?php echo $media->url?>' alt='gallery image' title='<?php echo $media->title?>'/></a>
                                        <span class="caption hidden"><?php echo stripcslashes($media->title) ?></span>
                                    </div>
                                </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                        <a class="nav right">Right</a>
                    </div>

                </div>
                <!-- End .vehicle-gallery-->
            </div>

            <div class="right-column"><!--author info-->
                <?php
                if(!empty($medias)):
                    include_once 'sidebars/single-post-right-sidebar-1.php';
                endif;
                ?>
            </div>

        </div>

        <?php endif; endif; ?>

        <!--Begin Content  -->
        <div class="row"><!-- begin .share-->

            <?php include 'sidebars/addthis.php' ?>

            <div class="main-content">

                <?php the_content()?>
                <p></p>
            </div>
            <div class="right-column"><!--Pull Quote-->

                <?php

                include_once 'sidebars/single-post-right-sidebar-1.php';

                include 'sidebars/single-post-right-sidebar-2.php';

                ?>

            </div>
        </div>

        <?php endwhile; endif; ?>

        <!-- Call comment esi to include comment list and form -->
        <?php echo wheels_esi_include(get_template_directory_uri().'/esi/comments.php?'.http_build_query($_GET).'&post_id='.get_the_ID().'&redirect='.urlencode( get_permalink(get_the_ID()) ) ) ?>

    </div>
<?php get_footer()?>
</body>
</html>
