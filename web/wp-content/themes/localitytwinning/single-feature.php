<?php get_header('meta')?>
<body class="page article news mobile-page"><!-- begin #container-->
    <div id="container" data-role="page"><!-- begin #topads-->
    <?php get_header()?>
        <!-- begin news article-->
        <div id="news-article" class="section-container">
            <?php wheels_breadcrumb() ?>
                         <!--Feature Top Section Start-->

        <?php
        global $post;
        if (have_posts()): while (have_posts()): the_post();

            $postModel = new \Emicro\Model\Post($wpdb);
            $custom_value = $postModel->getCustomValues(get_the_ID());

        $taxonomyOptions = array('taxonomy' => 'feature-category', 'post_type' => 'feature', 'limit'=>12);
        global $wpdb;
        $postModel = new \Emicro\Model\Post($wpdb);
        $data = $postModel->getPostsForTaxonomy(get_the_ID(),$taxonomyOptions );
        $title = $data['name'];
        $rows = $data['data'];
        if(count($rows))
         {
         ?>
        <div class="row">
        <div class="article-carousel">

        <div class="title">
            <h3><?php echo $title; ?></h3>

            <?php //echo get_sponsor_markup('badge') ?>

            <!--JavaScript Tag // Tag for network 5214: Olive Media // Website: Wheels // Page: News & Features // Placement: wheels_newsandfeatures_columnsadvice_hub_240x50_1 (2433970) // created at: Jun 15, 2012 12:21:29 PM-->
             <?php if(!DISABLE_ALL_AD):?>
            <script language="javascript">
                <!--
                var curDateTime = new Date();
                var offset = -(curDateTime.getTimezoneOffset());
                if (offset > 0)
                    offset = "+" + offset;

                if (window.adgroupid == undefined) {
                    window.adgroupid = Math.round(Math.random() * 1000);
                }
                document.write('<scr'+'ipt language="javascript1.1" src="http://adserver.adtechus.com/addyn/3.0/5214.1/2433970/0/1282/ADTECH;loc=100;size=;target=_blank;key=key1+key2+key3+key4;grp='+window.adgroupid+';misc='+new Date().getTime()+';aduho='+offset+';rdclick="></scri'+'pt>');
                //-->
            </script>
            <noscript>
                <a href="http://adserver.adtechus.com/adlink/3.0/5214.1/2433970/0/1282/ADTECH;loc=300;size=; key=key1+key2+key3+key4;rdclick=" target="_blank"><img src="http://adserver.adtechus.com/adserv/3.0/5214.1/2433970/0/1282/ADTECH;loc=300;key=key1+key2+key3+key4" border="0" width="240" height="50"></a>
            </noscript>
            <!-- End of JavaScript Tag -->
            <?php endif; ?>
    </div>

    <div id="guideCarousel" data-controller="CarouselController" data-usebullets="false" class="carousel">
        <div class="carousel-inner">
             <?php
                        $i = 0;
                        foreach($rows as $post)
                        {
                            if($i==0){echo '<div class="item active">';}
                            else if($i%4==0){echo '<div class="item">';}
                         ?>
                            <div class="col <?php echo ($i==3 || $i==7 || $i==(count($rows)-1))?'last':''; ?>">
                                <a href="<?php the_permalink()?>">
                                    <?php  if ( has_post_thumbnail() ) { the_post_thumbnail( array(132,74) ); } ?>
                                    <p><?php echo the_title()?> </p>
                                </a>
                            </div>
                        <?php
                            if($i==3 || $i==7 || $i==11 ||  $i==(count($rows)-1)){echo '</div>';}
                            $i++;
                        }

                        ?>
        </div>
        <?php if($i>4){ ?>
                    <a href="#guideCarousel" data-slide="prev" class="carousel-control left">&lsaquo;</a>
                    <?php } ?>
                    <?php if($i>4){ ?>
                    <a href="#guideCarousel" data-slide="next" class="carousel-control right">&rsaquo;</a>
                    <?php } ?>

    </div>
    <!-- end .carousel--></div>
    </div>
    <?php
    }
    wp_reset_postdata();
    ?>
        <!--Feature Top Section End-->

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
                        <?php echo wheels_add_avatar_class(get_avatar(get_the_author_meta('ID'), 81))?>
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

                    <?php the_content() ?>
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

            <!-- Call esi: related video-->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/related-videos.php?post_id='.get_the_ID()) ?>

            <!-- Call esi: popular alternative fuel cars-->
            <?php   $term = wp_get_post_terms( get_the_ID(), 'class', array("fields" => "names") );

                    if(isset($term[0])):
            ?>
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/popular-alternative-fuel-cars.php?class='.urlencode($term[0])) ?>


            <!-- Hide data table  -->


            <?php endif ?>

            <div class="row">
                <!-- begin .leaderboard-->
                <div class="leaderboard"><?php echo $adModel->getAd('728x90') ?></div>
                <!-- end .leaderboard-->
            </div>

            <!-- Call comment esi to include comment list and form -->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/comments.php?'.http_build_query($_GET).'&post_id='.get_the_ID().'&redirect='.urlencode( get_permalink(get_the_ID()) ) ) ?>

            <!-- Call esi: special : electric car -->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/special.php') ?>

            <!-- Call esi: popular alternative fuel cars-->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/featured_event.php') ?>

            <div class="row"><!-- begin .used-listings-->
                <?php include('sidebars/used-car-wrapper.php') ?>
                <?php echo wheels_esi_include(get_template_directory_uri().'/esi/more-news.php') ?>
                <?php echo wheels_esi_include(get_template_directory_uri().'/esi/wheels-guides.php') ?>
            </div>
        </div>
    <?php get_footer()?>
</body>
</html>
