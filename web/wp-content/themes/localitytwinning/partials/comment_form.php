<div class="post-review">
    <div class="wrap">
        <h3>Post a Comment</h3>

        <?php if (isset($rating) && $rating == true): ?>
        <div id="review-rating">
            <strong class="title">Your Rating</strong>
            <strong class="rating">0</strong>
            <div class="slider"></div>
        </div>
        <?php endif;?>

        <form action="<?php echo site_url('/wp-comments-post.php'); ?>" method="post" id="<?php echo esc_attr($args['id_form']); ?>" class="<?php echo $args['id_form'] ?>">

            <a name="comment-form-here" id="comment-form-here"></a>
            <?php do_action('comment_form_top'); ?>

            <?php if (isset($rating) && $rating == true): ?>
            <input id="user-rating" type="hidden" name="user-rating" value="0"/>
            <?php endif;?>

            <?php if (is_user_logged_in()) : ?>
                <?php //echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ); ?>
                <?php do_action('comment_form_logged_in_after', $commenter, $user_identity); ?>
            <?php else : ?>
                <?php echo $args['comment_notes_before']; ?>
                <?php
                do_action('comment_form_before_fields');
                foreach ((array)$args['fields'] as $name => $field) {
                    echo apply_filters("comment_form_field_{$name}", $field) . "\n";
                }
                do_action('comment_form_after_fields');
                ?>
            <?php endif; ?>

            <label class="review">Your Comment<!--&nbsp;<span>(Optional)</span>--></label>
            <?php echo apply_filters('comment_form_field_comment', $args['comment_field']); ?>
            <?php //echo $args['comment_notes_after']; ?>

            <?php if (isset($rating) && $rating == true): ?>
            <!--<fieldset class="owned">
                <label for="ownthis">I own this</label>
                <input type="checkbox" id="ownthis" name="owned"/>
            </fieldset>-->
            <?php endif;?>

            <?php do_action('comment_form', $post_id); ?>

            <input type="submit" value="Post Review" class="formbtn green"/>
            <?php comment_id_fields($post_id); ?>

            <?php if (is_user_logged_in()): ?>
                <span class="user-info">Not <?php echo $user_identity?>?
                <a href="<?php echo wp_logout_url(get_permalink($post_id)); ?>">Sign-out</a></span>
            <?php endif;?>

            <input type="hidden" name="redirect_to" value="<?php echo $redirectURL?>">

        </form>
    </div>
    <!-- end .commentform-->
</div>