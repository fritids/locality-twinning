<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;

$eventsRefTable = $wpdb->prefix . WHEELS_EVENTS_REF_TABLE;
$today = date("Y-m-d");

$eventsData = $wpdb->get_results($wpdb->prepare("SELECT wp_wheels_events.wheels_events_id, wp_wheels_events.post_id,wp_wheels_events.is_featured_event ,
                                        wp_wheels_events.events_start_date,wp_wheels_events.events_end_date,
                                        wp_posts.ID,wp_posts.post_title FROM wp_posts
                                        INNER JOIN wp_wheels_events ON wp_posts.ID = wp_wheels_events.post_id
                                        WHERE post_status = 'publish' AND events_start_date >= '$today'
                                        AND is_featured_event = 1 ORDER BY events_start_date ASC, post_date ASC LIMIT 3"));
global $post;
if(!empty($eventsData)):
?>
<div class="row">
    <div class="featured-events">
        <div class="section-head">
            <h4>Featured Events</h4>
            <a href="/events/" class="primary event-calendar">Event Calendar</a>
        </div>

        <div class="event-list">
            <?php foreach($eventsData AS $post): ?>
            <?php setup_postdata($post); ?>
            <div class="event">
                <a href="<?php echo get_permalink( get_the_ID() ) ?>">
                    <?php echo get_the_post_thumbnail(get_the_ID(), 'featured-events'); ?>
                    <?php echo $post->post_title; ?>
                </a>
            </div>
            <?php endforeach ?>

        </div>
    </div>

</div>
<?php endif ?>