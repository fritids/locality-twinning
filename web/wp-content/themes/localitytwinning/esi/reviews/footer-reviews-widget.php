<?php
require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;

$postModel = new \Emicro\Model\Post($wpdb);

$latestReviews = $postModel->getAll(array('post_type' => 'reviews', 'limit'=>4, 'custom_field' => true));
$popularReviews = $postModel->getAll(array('post_type' => 'reviews', 'type'=>'popular', 'limit'=>4, 'custom_field' => true));
?>
<!-- begin .more-news-->
<div class="module news clearfix">
    <div data-controller="TabsController" class="clearfix related">

        <h3>More Reviews</h3>

        <div class="tab-nav">
            <ul class="clearfix">
                <li><a>Related</a></li>
                <li class="last"><a class="last">Trending</a></li>
            </ul>
        </div>

        <div class="tabs">

            <div data-controller="SlidesController" data-mobileonly="true" class="tab related">
                <div class="viewport">

                    <ul class="container">

                        <?php foreach($latestReviews as $post): setup_postdata($post);?>
                        <li class="article-info slide<?php if(!empty($post->sponsor_id)) echo ' sponsored';?>">
                            <div class="wrap">
                                <a href="<?php the_permalink()?>" class="title"><?php the_title()?><strong>By <?php the_author()?></strong></a>
                                <?php if(!empty($post->sponsor_id)){?><span class="sponsor">Sponsored</span><?php }?>
                            </div>
                        </li>
                        <?php endforeach; wp_reset_postdata();?>

                    </ul>

                </div>
            </div>

            <div data-controller="SlidesController" data-mobileonly="true" class="tab trending" style="display: none;">

                <div class="viewport">

                    <ul class="container">

                        <?php foreach($popularReviews as $post): setup_postdata($post);?>
                        <li class="article-info slide<?php if(!empty($post->sponsor_id)) echo ' sponsored';?>">
                            <div class="wrap">
                                <a href="<?php the_permalink()?>" class="title">
                                    <?php the_title()?><strong>By <?php the_author()?></strong>
                                </a>
                                <?php if(!empty($post->sponsor_id)):?>
                                <span class="sponsor">Sponsored</span>
                                <?php endif;?>
                            </div>
                        </li>
                        <?php endforeach; wp_reset_postdata();?>

                    </ul>

                </div>

            </div>

        </div>
    </div>
    <a href="<?php echo site_url('news')?>" class="primary">More</a>
</div>
<!-- end .more-news-->