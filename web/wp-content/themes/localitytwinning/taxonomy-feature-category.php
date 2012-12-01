<?php
$term = get_query_var('term');
$taxonomy = get_query_var('taxonomy');
$args['type'] = (isset($_GET['type']) && $_GET['type']) ? 'popular' : '';
$args['post_type'] = 'feature';
$args['term'] = $term;
$args['taxonomy'] = $taxonomy;
?>
<?php get_header('meta')?>
<body class="page news"><!-- begin #container-->
    <div id="container" data-role="page"><!-- begin #topads-->
<?php get_header()?>
    <!-- begin news article-->
    <div id="news" class="section-container clearfix">

        <?php wheels_breadcrumb()?>

        <div class="row">
            <h2><?php echo single_cat_title('', false)?></h2>
            <?php
            $args['limit'] = 9;

            global $wpdb;
            $postModel = new \Emicro\Model\Post($wpdb);
            $latest_news = $postModel->getAll($args);


            if(!empty($latest_news)){
                ?>
                <!-- begin .carousel-->
                <div id="newsfeaturesCarousel" data-controller="CarouselController" class="carousel slide">
                    <div class="carousel-inner">
                        <div class="item active">
                            <?php
                            $loop = 1;
                            $break_div = array(4,7);
                            foreach($latest_news as $post)
                            {
                                switch($loop){
                                    case 1: case 4: case 7: $elm_class = 'prime tl'; break;
                                    case 2: case 5: case 8: $elm_class = 'tm'; break;
                                    case 3: case 6: case 9: $elm_class = 'tr'; break;
                                }
                                switch($loop){
                                    case 1: case 4: case 7: $image_size = 'slide-big'; break;
                                    default: $image_size = 'slide-medium'; break;
                                }
                                setup_postdata($post);

                                if(in_array($loop, $break_div)) echo '</div><div class="item ">';

                                ?>
                                <div class="feature-container <?php echo $elm_class?>"><?php the_post_thumbnail($image_size)?>
                                    <div class="copy">
                                        <div class="pos"><a href="<?php the_permalink()?>">
                                            <h4><?php echo character_limiter( get_the_title(), 297)?>&nbsp;&raquo;</h4>
                                            <span class="author"><?php the_author()?></span></a></div>
                                    </div>
                                    <a href="<?php the_permalink()?>#" class="comment-count">
                                        <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/comment-count.php?post_id='.$post->ID) ?>
                                    </a>
                                    <div class="overlay">&nbsp;</div>
                                </div>
                                <?php

                                $loop++;
                            }?>
                        </div>

                    </div>
                    <a href="#newsfeaturesCarousel" data-slide="prev" class="carousel-control left">&lsaquo;</a>
                    <a href="#newsfeaturesCarousel" data-slide="next" class="carousel-control right">&rsaquo;</a>
                </div>
                <!-- end .carousel-->
                <?php }?>
        </div>

        <div class="row">
            <!-- begin .latest-news-->
            <div class="in-this-section">
                <h3>In This Section</h3>
                <?php
                $latest_news = wp_cache_get('news-future-lading-latest-news', 'wheels');
                if(!$latest_news){
                    $args['start'] = 9;
                    $args['limit'] = 10;
                    $latest_news = wheels_news_get($args);
                    wp_cache_add('news-future-lading-latest-news', $latest_news, 'wheels');
                }
                ?>
                <ul>
                    <?php
                    $loop = 1;
                    foreach($latest_news as $post){ setup_postdata($post);
                        ?>
                        <li>
                            <div class="pos"><a href="<?php the_permalink() ?>"><?php the_post_thumbnail('tiny-thumbnail')?>
                                <p><?php the_title()?></p>
                            </a></div>
                        </li>

                        <?php
                    }?>
                </ul>
            </div>
            <!-- end .latest-news-->

            <div class="mrec-ad">
                <?php global $adModel; echo $adModel->getAd('300x250'); ?>
            </div>

        </div>

        <!-- Call esi: reader's thought-->
        <?php echo wheels_esi_include(get_template_directory_uri().'/esi/readers-thoughts.php?post_type=feature')?>

        <div class="row section-row">
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/poll.php')?>
            <?php if($quote = wheels_news_get_quote_by_term($term)){
                foreach($quote as $post){ setup_postdata($post);
            ?>
            <div class="pull-quote">
                <div class="pos">
                    <p><?php echo stripcslashes(stripcslashes($post->quote)) ?></p>
                    <a href="<?php the_permalink()?>" class="article"><?php the_title()?></a><span class="author"><?php the_author()?></span></div>
            </div>
            <?php }
            }?>
        </div>

        <div class="row"><!-- begin .used-listings-->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/answer-center.php')?>
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/more-news.php')?>
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/wheels-guides.php')?>
        </div>

    </div>
    <!-- end #main-->
    <?php get_footer();?>
        </body>
        </html>