<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;

$postModel = new \Emicro\Model\Post($wpdb);

$latestNews  = $postModel->getAll(array('post_type' => 'news', 'limit' => 10, 'start' => 9));
?>
<div class="latest-news">
    <h3>Latest News</h3>
    <div class="col">

        <div class="pos">
            <?php
            $loop = 1;
            foreach($latestNews as $post){ setup_postdata($post);
                ?>
                <a href="<?php the_permalink()?>"><?php the_title()?></a>
                <?php
                if($loop == 5) echo '</div></div><div class="col"><div class="pos">';
                $loop++;
            }?>
        </div>

    </div>
</div>