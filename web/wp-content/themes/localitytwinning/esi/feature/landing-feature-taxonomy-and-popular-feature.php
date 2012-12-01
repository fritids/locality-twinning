<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;

$postModel = new \Emicro\Model\Post($wpdb);

?>
<div class="row">

    <!-- begin .features-->
    <div class="features">
        <h3>Features</h3>
        <ul>
            <?php
            $catIds = array();
            foreach(get_terms('feature-category', array('hide_empty'=>false, 'number' => 3)) as $term):
                $catIds[] = $term->term_id;
                $latestFeature = $postModel->getAll(array('limit' => 1, 'post_type' => 'feature', 'taxonomy' => 'feature-category', 'term' => $term->slug));
                if(isset($latestFeature[0])) : $post = $latestFeature[0];
            ?>

                <li>
                    <a href="<?php echo get_term_link($term)?>">
                        <?php the_post_thumbnail('204x115')?>
                        <p><?php echo $term->name?></p>
                    </a>
                </li>

                <?php
            endif;
        endforeach;
        ?>
        </ul>
        <div class="col">

            <div class="pos">
                <h4>More Features</h4>
                <ul>
                    <?php foreach(get_terms('feature-category', array('hide_empty'=>false, 'exclude' => $catIds)) as $term) {?>
                    <li><a href="<?php echo get_term_link($term)?>"><?php echo $term->name?></a></li>
                    <?php }?>
                </ul>
            </div>

        </div>
    </div>
    <!-- end .features-->

</div>

<div class="row"><!-- begin .popular-vehicles-->
    <div class="popular-features">

        <h3>Popular Features</h3>
        <div class="col" style="width:270px;">

            <div class="pos">
                <?php
                $loop = 1;
                $features = wheels_news_get(array('post_type'=>'feature', 'type'=>'popular','limit'=>18, 'start' => 0));
                foreach($features as $post):
                    ?>
                    <a href="<?php the_permalink()?>"><?php the_title()?></a>
                    <?php
                    if (in_array($loop, array(6, 12))):
                        echo '</div></div><div class="col" style="width:270px;"><div class="pos">';
                    endif;
                    $loop++;
                endforeach;?>
            </div>

        </div>

    </div>
    <!-- end .popular-vehicles-->
</div>