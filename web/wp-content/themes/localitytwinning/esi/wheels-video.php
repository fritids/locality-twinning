<?php
require '../../../../wp-load.php';
if(!function_exists('wheels_news_get')) exit;
$latestNews = wheels_news_get(array('limit'=>4, 'custom_field' => true));
$popularNews = wheels_news_get(array('type'=>'popular', 'limit'=>4, 'custom_field' => true));
?>
<div class="module wheels-video clearfix">
    <div data-controller="TabsController" class="clearfix related">
        <h3>More News</h3>
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
                        <?php foreach($latestNews as $post): setup_postdata($post);?>
                        <li class="article-info slide<?php if(!empty($post->sponsor_id)) echo ' sponsored';?>">
                            <div class="wrap">
                                <a href="<?php the_permalink()?>" class="title"><?php the_title()?></a>
                                <strong>By <?php the_author()?></strong>
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
                        <?php foreach($popularNews as $post): setup_postdata($post);?>
                        <li class="article-info slide<?php if(!empty($post->sponsor_id)) echo ' sponsored';?>">
                            <div class="wrap">
                                <a href="<?php the_permalink()?>" class="title"><?php the_title()?></a>
                                <strong>By <?php the_author()?></strong>
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
    <a href="/news/" class="primary">More</a>
</div>