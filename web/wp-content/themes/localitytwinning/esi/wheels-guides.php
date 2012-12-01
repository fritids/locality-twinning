<?php
/*
TODO: Make excute this file without load wp-load
*/
require '../../../../wp-load.php';

$postModel = new \Emicro\Model\Post($wpdb);

$latestGuides = $postModel->getAll(array('limit'=>4, 'custom_field' => true, 'post_type' => 'guides'));
$popularGuides = $postModel->getAll(array('type'=>'popular', 'limit'=>4, 'custom_field' => true, 'post_type' => 'guides'));
?>
<!-- begin .wheels-guide-->
<div class="module wheels-guide clearfix">
    <div data-controller="TabsController" class="clearfix related"><h3>Wheels Guides</h3>

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

                        <?php foreach($latestGuides as $post): setup_postdata($post);?>
                        <li class="article-info slide<?php if(!empty($post->sponsor_id)) echo ' sponsored';?>">
                            <div class="wrap">
                                <a href="<?php the_permalink()?>">
                                    <?php the_post_thumbnail(array(132,74))?>
                                    <p>
                                        <strong><?php echo strip_tags(get_the_term_list( $post->ID, 'guides-category', '', ', ', '' )) ;?></strong>
                                        <?php the_title()?>
                                    </p>
                                </a>
                                <?php if(!empty($post->sponsor_id)){?><span class="sponsor">Sponsored</span><?php }?>
                            </div>
                        </li>
                        <?php endforeach;?>

                    </ul>
                </div>
            </div>
            <div data-controller="SlidesController" data-mobileonly="true" class="tab trending" style="display: none;">
                <div class="viewport">
                    <ul class="container">

                        <?php foreach($popularGuides as $post): setup_postdata($post);?>
                        <li class="article-info slide<?php if(!empty($post->sponsor_id)) echo ' sponsored';?>">
                            <div class="wrap">
                                <a href="<?php the_permalink()?>">
                                    <?php the_post_thumbnail(array(132,74))?>
                                    <p>
                                        <strong><?php echo strip_tags(get_the_term_list( $post->ID, 'guides-category', '', ', ', '' )) ;?></strong>
                                        <?php the_title()?>
                                    </p>
                                </a>
                                <?php if(!empty($post->sponsor_id)){?><span class="sponsor">Sponsored</span><?php }?>
                            </div>
                        </li>
                        <?php endforeach;?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <a href="<?php echo site_url('guides')?>" class="primary">More</a>
</div>
<!-- end .wheels-guide-->