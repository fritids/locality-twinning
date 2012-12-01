<div class="row">
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
global $wpdb;
$prefix = $wpdb->prefix;
$str = "SELECT ".$prefix."posts.ID, post_title, post_date FROM ".$prefix."posts LEFT JOIN ".$prefix."wheels_custom_data ON ".$prefix."posts.ID = ".$prefix."wheels_custom_data.post_id";
$str .= " WHERE post_type = 'news' AND post_status = 'publish' AND news_breaking = 1 ORDER BY post_date DESC";
$sql = $wpdb->prepare($str);
$row = $wpdb->get_row($sql);
if($row):
?>
    <div class="breaking"><span>Breaking:</span>
        <a href="<?php echo get_permalink($row->ID); ?>"><?php echo $row->post_title; ?></a>
        <time pubdate="pubdate" datetime="<?php echo date("Y-m-d",strtotime($row->post_date)); ?>">Updated <?php echo date("h:ma",strtotime($row->post_date)); ?> EST</time>
    </div>
<?php endif; ?>
    &nbsp;
</div>