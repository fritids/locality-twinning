<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb, $post;

$commenModel = new \Emicro\Model\Comment($wpdb);

$postId = (isset($_GET['post_id'])) ? $_GET['post_id'] : 0;

$commentQuery = "SELECT
                wp_comments.comment_ID, wp_comments.comment_author, wp_comments.comment_content, popular.meta_value as popularity, wp_comments.comment_author_email, UNIX_TIMESTAMP(wp_comments.comment_date) as comment_date
                FROM
                wp_comments
                LEFT JOIN wp_commentmeta AS popular ON wp_comments.comment_ID = popular.comment_id
                WHERE
                wp_comments.comment_approved = '1' AND
                wp_comments.comment_parent = 0 AND
                popular.meta_key = 'comment_popularity' AND
                comment_post_ID = '{$postId}'
                ORDER BY popularity DESC
                LIMIT 0, 3";
$comments = $wpdb->get_results($commentQuery);
echo $wpdb->last_error;
$dateFormat = get_option('date_format');

if(!empty($comments)): $totalFound = count($comments);

?>

<div class="reader-reviews">
    <h3>Top Reader Comments</h3>
    <a href="#" data-role="none" class="post-review">Post a comment</a>

    <?php foreach($comments as $comment): $rating = get_comment_meta($comment->comment_ID, 'comment_rating', true);?>
    <div class="reader-review clearfix ">
        <div class="rating small">
            <div class="value rating-<?php echo str_replace('.','-', $rating) ?>"><?php echo $rating ?></div>
        </div>

        <div class="reviewer-profile-icon">
            <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.$comment->comment_author_email.'&size=48') ?>
        </div>

        <div class="review-text">
            <div class="reader-info clearfix">
                <strong class="username"><?php echo $comment->comment_author?></strong>
                <!--<em class="reputation gear-1">1st gear</em>-->
                <span class="date"><?php echo date($dateFormat ,$comment->comment_date); ?></span>
            </div>
            <p data-controller="ExpanderController" data-slicepoint="75"><?php echo $comment->comment_content?></p>
        </div>
    </div>
    <?php endforeach;?>

    <a href="#comment-container" class="primary reviews">See all comments</a>
</div>

<?php endif ?>