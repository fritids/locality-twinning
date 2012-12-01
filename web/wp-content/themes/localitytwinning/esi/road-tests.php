<?php
require '../../../../wp-load.php';
global $wpdb, $post;

$theme_option = get_option("wheels_theme_options");

$term = get_term($theme_option['road_test_term'], 'feature-category');

$categoryName =  '---';
$categorySlug = '---';

if( !is_wp_error($term) )
{
    $categoryName =  $term->name;
    $categorySlug = $term->slug;
}

$agrs = array(
    'post_type' => 'feature',
    'taxonomy' => 'feature-category',
    'term' => $categorySlug,
    'limit' => 5
);
$postModel = new \Emicro\Model\Post($wpdb);
$features = $postModel->getAll($agrs);
?>


    <?php if($features) : $post = $features[0]; setup_postdata($post)?>

    <div class="road-tests">
        <h3><?php echo $categoryName?></h3>

        <div class="feature-container">
            <?php the_post_thumbnail(array(276,155))?>
            <div class="copy">
                <div class="pos">
                    <h4>
                        <a href="<?php the_permalink()?>"><?php the_title()?> &raquo;</a>
                    </h4>
                </div>
            </div>
        </div>

        <?php
        unset($features[0]);
        if(!empty($features)):
        ?>

        <div class="more-tests">
            <h4>More <?php echo $categoryName ?></h4>
            <ul>

            <?php foreach($features as $post): setup_postdata($post)?>
                <li><a href="<?php the_permalink()?>"><?php the_title()?></a></li>
            <?php endforeach;?>

            </ul>
        </div>
        <?php endif;?>

    </div>
    <!-- end .road-tests-->

    <?php endif;?>

