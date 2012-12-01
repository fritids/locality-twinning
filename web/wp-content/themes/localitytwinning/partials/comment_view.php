<li id="li-comment-<?php comment_ID(); ?>" class="<?php echo $class?>">
    <article id="comment-<?php comment_ID(); ?>" class="comment"><!-- like dislike comments-->
        <div class="like-wrap">
            <?php if (!$is_depth) { ?>
            <a href="#" class="like"><span>&nbsp;</span></a>
            <span><?php echo ($popularity = get_comment_meta(get_comment_ID(), 'popularity')) ? $popularity : '0'?></span>
            <a href="#" class="dislike"><span>&nbsp;</span></a>
            <?php }?>
        </div>
        <!-- end like dislike comments--><!-- avatar image-->
        <div class="avatar-wrap">
            <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.$comment.'&size=48') ?>
        </div>
        <!-- end avatar image--><!--comment text-->
        <div class="comment-wrap">
            <footer class="comment-meta">
                <div class="comment-author vcard"><span class="fn"><?php the_author()?></span><em
                    class="reputation gear-3">3rd gear</em>
                    <time pubdate="pubdate" datetime="02172012"><?php comment_date()?></time>
                </div>
            </footer>
            <div class="comment-content"><?php comment_text(); ?></div>
            <?php if (!$is_depth) { ?>
            <a href="#comment-form-here" class="primary"
               onclick="document.getElementById('comment_parent').value = '<?php comment_ID()?>';">Reply</a>
            <a href="#" class="primary reply"><span>1 Reply</span></a>
            <?php }?>
        </div>
        <!--end comment text-->
    </article>
</li>