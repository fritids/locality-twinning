<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;

$postModel = new \Emicro\Model\Post($wpdb);

$today = date("Y-m-d");

$latestEvents = $wpdb->get_results($wpdb->prepare("SELECT wp_wheels_events.wheels_events_id, wp_wheels_events.post_id,wp_wheels_events.is_featured_event ,
                                        wp_wheels_events.events_start_date,wp_wheels_events.events_end_date,
                                        wp_posts.ID, wp_posts.* FROM wp_posts
                                        INNER JOIN wp_wheels_events ON wp_posts.ID = wp_wheels_events.post_id
                                        WHERE post_status = 'publish' AND events_start_date >= '$today'
                                        AND is_featured_event = 1 ORDER BY events_start_date ASC, post_date ASC LIMIT 9"));

if (!empty($latestEvents)):

    ?>
<div class="row">
    <div id="eventsCarousel" data-controller="CarouselController" class="carousel slide">
        <div class="carousel-inner">
            <div class="item active">

                <?php

                $loop = 1;
                $break_div = array(4, 7);
                foreach ($latestEvents as $post):
                    switch ($loop) {
                        case 1:
                        case 4:
                        case 7:
                            $elm_class = 'prime tl';
                            break;
                        case 2:
                        case 5:
                        case 8:
                            $elm_class = 'tm';
                            break;
                        case 3:
                        case 6:
                        case 9:
                            $elm_class = 'tr';
                            break;
                    }
                    switch ($loop) {
                        case 1:
                        case 4:
                        case 7:
                            $image_size = 'slide-big';
                            break;
                        default:
                            $image_size = 'slide-medium';
                            break;
                    }
                    setup_postdata($post);

                    if (in_array($loop, $break_div)) echo '</div><div class="item ">';

                    ?>
                    <div class="feature-container <?php echo $elm_class?>">
                        <?php the_post_thumbnail($image_size)?>

                        <div class="copy">
                            <div class="pos">
                                <h4>
                                <a href="<?php the_permalink()?>">

                                        <?php echo character_limiter(get_the_title(), 297)?>&nbsp;&raquo;
                                        <span class="author"><?php the_author()?></span>

                                </a>
                                </h4>
                            </div>
                        </div>

                        <a href="<?php the_permalink()?>#comment-container" class="comment-count">
                         <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/comment-count.php?post_id='.$post->ID) ?>
                        </a>

                        <div class="overlay">&nbsp;</div>

                    </div>
                    <?php

                    $loop++;
                endforeach?>

            </div>
        </div>

        <a href="#eventsCarousel" data-slide="prev" class="carousel-control left">&lsaquo;</a>
        <a href="#eventsCarousel" data-slide="next" class="carousel-control right">&rsaquo;</a>

    </div>
</div>

<?php endif; ?>