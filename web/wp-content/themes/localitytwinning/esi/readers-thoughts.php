<?php
require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;

$postModel = new \Emicro\Model\Post($wpdb);
$post_type = (!empty($_GET['post_type'])) ? $_GET['post_type'] : 'news';

$readersThoughts = $postModel->getPostWithComment(array('post_type' => $post_type, 'limit' => 4));

if(!empty($readersThoughts)): $totalFound = count($readersThoughts);

?>
<div class="row"><!-- begin .readers-thoughts-->
    <div class="readers-thoughts"><h3>Reader's Thoughts</h3>
        <ul class="listing">
            <?php global $post; foreach($readersThoughts as $key => $post): setup_postdata($post)?>
            <li<?php if(($key+1) == $totalFound) echo ' class="last"'?>>
                <div class="wrap">
                    <a href="<?php the_permalink()?>" class="title"><?php echo character_limiter(strip_tags($post->post_title), 100,'&hellip;') ?>
                        <strong class="small"><?php the_author()?></strong>
                    </a>
                    <a href="<?php the_permalink()?>#comment-container" class="avatar">
                        <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.$post->comment_author_email.'&size=35') ?>
                    </a>

                    <div class="comment">
                        <div class="nub">&nbsp;</div>
                        <div class="thought">
                            <span class="name"><?php echo $post->comment_author?> says&hellip;</span>

                            <p><?php echo character_limiter(strip_tags($post->comment_content), 100,'&hellip;') ?></p></div>
                    </div>
                </div>
            </li>
            <?php endforeach; wp_reset_postdata(); ?>
        </ul>
    </div>
    <!-- end .readers-thoughts-->
</div>
<?php endif?>