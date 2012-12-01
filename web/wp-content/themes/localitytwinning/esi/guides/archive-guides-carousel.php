<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;

$postModel = new \Emicro\Model\Post($wpdb);

$latestNews  = $postModel->getAll(array('post_type' => 'guides', 'limit' => 9, 'custom_field' => true));

?>
<?php

if(!empty($latestNews)):

    ?>
<div class="reviewCarouselContainer">
    <div id="newsfeaturesCarousel" data-controller="CarouselController" class="carousel slide">
        <div class="carousel-inner">
            <div class="item active">

                <?php
                $loop = 1;
                $break_div = array(4,7);
                foreach($latestNews as $post):
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
                    <div class="feature-container <?php echo $elm_class?>">
                        <?php the_post_thumbnail($image_size)?>

                        <div class="copy">
                            <div class="pos">
                                <a href="<?php the_permalink()?>">
                                    <h4><?php echo character_limiter( get_the_title(), 297)?>&nbsp;&raquo;</h4>
                                    <span class="author"><?php the_author()?></span>
                                </a>
                            </div>
                        </div>

                        <a href="<?php the_permalink()?>#comment-container" class="comment-count">
                            <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/comment-count.php?post_id='.$post->ID) ?>
                        </a>
                        <div class="overlay" title="Popularity <?php echo get_post_meta($post->ID, 'wheels_post_popularity', true)?>">&nbsp;</div>

                    </div>
                    <?php

                    $loop++;
                endforeach?>

            </div>
        </div>

        <a href="#newsfeaturesCarousel" data-slide="prev" class="carousel-control left">&lsaquo;</a>
        <a href="#newsfeaturesCarousel" data-slide="next" class="carousel-control right">&rsaquo;</a>

    </div>
</div>
<?php endif; ?>