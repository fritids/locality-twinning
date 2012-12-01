<?php
$post_id = (int)$_POST['post_id'];
if(empty($post_id)) exit;

require_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';
global $wpdb;

$orderby_fields = array('recent', 'popular', 'alpha');
$order = (isset($_POST['order']) && strtoupper($_POST['order']) == 'ASC') ? 'ASC' : 'DESC';
$orderby = (isset($_POST['sort']) && in_array( strtolower($_POST['sort']), $orderby_fields)) ? $wpdb->_escape($_POST['sort']) : 'recent';

$page = ($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = ($_POST['limit']) ? (int)$_POST['limit'] : 5;
$start = ($page - 1) * $limit;
$dateFormat = get_option('date_format');
$showRating = ( get_post_type($post_id) == 'reviews' ) ? true : false;

switch($orderby)
{
    case 'popular': $orderby = " ORDER BY popularity {$order}"; break;
    case 'recent': $orderby = " ORDER BY comment_date {$order}"; break;
    case 'alpha': $orderby = " ORDER BY comment_content ASC"; break;
    default: $orderby = " ORDER BY comment_date {$order}"; break;
}

$totalCommentParentQuery = "SELECT count(comment_ID) as total
                            FROM
                            wp_comments
                            WHERE
                            wp_comments.comment_approved = '1' AND
                            wp_comments.comment_parent = 0 AND
                            comment_post_ID = '{$post_id}'";
$totalCommentParent = $wpdb->get_var($totalCommentParentQuery);

$totalComment = $wpdb->get_var("SELECT comment_count FROM wp_posts WHERE ID = '{$post_id}'");

// Generate pagination link
$totalPageNumber = ceil( $totalCommentParent / $limit );
$paging = wheels_pagination($totalPageNumber, $page);
// ================ End pagination

$commentQuery = "SELECT DISTINCT
                wp_comments.comment_ID, wp_comments.comment_author, wp_comments.comment_content, popular.meta_value as popularity, wp_comments.comment_author_email, UNIX_TIMESTAMP(wp_comments.comment_date) as comment_date
                FROM
                wp_comments
                LEFT JOIN wp_commentmeta AS popular ON wp_comments.comment_ID = popular.comment_id
                WHERE
                wp_comments.comment_approved = '1' AND
                wp_comments.comment_parent = 0 AND
                popular.meta_key = 'comment_popularity' AND
                comment_post_ID = '{$post_id}'
                {$orderby}
                LIMIT $start, $limit";
$comments = $wpdb->get_results($commentQuery);

$commentIds = array();
foreach($comments as $row)
{
    $commentIds[] = $row->comment_ID;
}
$commentIds = implode(',', $commentIds);

$childCommentsQuery = "SELECT DISTINCT comment_ID, comment_author, comment_content, comment_parent, comment_author_email, UNIX_TIMESTAMP(comment_date) as comment_date
                        FROM wp_comments
                        WHERE
                        comment_approved = '1' AND
                        comment_parent IN ({$commentIds}) ORDER BY comment_date ASC";
$childComments = $wpdb->get_results($childCommentsQuery);
?>
<input type="hidden" id="currentCommentPage" name="currentCommentPage" value="<?php echo $page?>" />
<input type="hidden" id="commentPostId" name="commentPostId" value="<?php echo $post_id?>" />
<div class="heading">
    <h3>Comments</h3>
    <div class="sort-options">
        <span>Sort</span>
        <div class="sort-container">
            <select name="sort-selector" data-controller="ComboboxController" data-readonly="true" class="ui-dark sort-comments">
                <option value="recent"<?php if($_POST['sort'] == 'recent') echo ' selected="selected"'?>>Most Recent</option>
                <option value="popular"<?php if($_POST['sort'] == 'popular') echo ' selected="selected"'?>>Most Popular</option>
                <option value="alpha"<?php if($_POST['sort'] == 'alpha') echo ' selected="selected"'?>>Alphabetical</option>
            </select>
        </div>
    </div>
    <!-- begin .pagination-->
    <div class="pagination">
        <?php echo $paging?>
    </div><!-- end .pagination  -->
    <div class="comment-total"><?php echo $totalComment; echo ($totalComment > 1) ? ' Comments' : ' Comment'?></div>
</div>
<div class="comments">
    <ul class="commentlist">
<?php
foreach ($comments as $comment) {

    $childCommentsData = array();
    foreach($childComments as $childComment)
    {
        if( $comment->comment_ID == $childComment->comment_parent )
        {
            $childCommentsData[] = $childComment;
        }
    }
    $comment->child = $childCommentsData;
    $comment->childCount = count($childCommentsData);
    ?>
    <li id="li-comment-<?php echo $comment->comment_ID; ?>" class="answer">
        <article id="comment-<?php echo $comment->comment_ID; ?>" class="comment"><!-- like dislike comments-->
            <a name="comment-<?php echo $comment->comment_ID; ?>"></a>
            <div class="like-wrap">
                <a href="#" class="like" rel="<?php echo $comment->comment_ID?>"><span>&nbsp;</span></a>
                <span><?php echo $comment->popularity?></span>
                <a href="#" class="dislike" rel="<?php echo $comment->comment_ID?>"><span>&nbsp;</span></a>
            </div>

            <!-- end like dislike comments-->

            <!-- avatar image-->
            <div class="avatar-wrap">
                <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.$comment->comment_author_email.'&size=48') ?>
            </div>
            <!-- end avatar image-->

            <!--comment text-->
            <div class="comment-wrap">

                <footer class="comment-meta">
                    <div class="comment-author vcard"><span class="fn"><?php echo $comment->comment_author?></span>
                        <!--<em class="reputation gear-1">1st gear</em>-->
                        <time pubdate="pubdate" datetime="02172012"><?php echo date($dateFormat ,$comment->comment_date); //echo $comment->comment_date?></time>
                    </div>
                </footer>

                <div class="comment-content"><?php echo $comment->comment_content?></div>

                <a href="#comment-form-here" class="primary" onclick="document.getElementById('comment_parent').value = '<?php echo $comment->comment_ID?>';">Reply</a>
                <a href="#" class="primary reply"><span><?php echo $comment->childCount?> Reply</span></a>

            </div>
            <!--end comment text-->
            <!--end comment text-->

            <?php
            if($showRating):
                $rating = get_comment_meta($comment->comment_ID, 'comment_rating', true);
                if(!empty($rating) || $rating != '0'):
            ?>
                <div class="rating small"><div class="value rating-<?php echo str_replace('.','-', $rating) ?>"><?php echo $rating ?></div></div>
            <?php
                endif;
            endif
            ?>
        </article>
    </li>
<?php

    foreach($comment->child as $comment){?>
        <li id="li-comment-<?php echo $comment->comment_ID; ?>" class="reply">
            <article id="comment-<?php echo $comment->comment_ID; ?>" class="comment"><!-- like dislike comments-->
                <a name="comment-<?php echo $comment->comment_ID; ?>"></a>
                <!-- avatar image-->
                <div class="avatar-wrap">
                    <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.$comment->comment_author_email.'&size=48') ?>
                </div>
                <!-- end avatar image-->

                <!--comment text-->
                <div class="comment-wrap">

                    <footer class="comment-meta">
                        <div class="comment-author vcard"><span class="fn"><?php echo $comment->comment_author?></span>
                            <!--<em class="reputation gear-1">1st gear</em>-->
                            <time pubdate="pubdate" datetime="02172012"><?php echo human_time_diff($comment->comment_date, time())?> ago</time>
                        </div>
                    </footer>

                    <div class="comment-content"><?php echo $comment->comment_content?></div>

                </div>
                <!--end comment text-->
            </article>
        </li>
    <?php
    }

}
?>
    </ul>
</div>