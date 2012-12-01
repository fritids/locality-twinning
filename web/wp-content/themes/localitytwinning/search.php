<?php
global $wpdb;
$articleID = (int)end( explode('/', $_SERVER['REQUEST_URI']) );
// If URL end with slash
if(empty($articleID)) $articleID = (int)end( explode('/', $_SERVER['REQUEST_URI'], -1) );

wp_redirect('/articles/'.$articleID);

?>

<?php get_header('meta') ?>

<body class="page article news mobile-page">

<!-- begin #container-->
<div id="container" data-role="page">

    <!-- begin #topads-->
    <?php get_header() ?>

    <!-- begin news article-->
    <div id="news-article" class="section-container">

        <?php wheels_breadcrumb() ?>

        <?php
        global $post;
        if (have_posts()): while (have_posts()): the_post();
            // TODO: is this include readlly needed?
            require_once $_SERVER['DOCUMENT_ROOT'].'/../lib/Emicro/Model/Post.php';

            $postModel = new \Emicro\Model\Post($wpdb);
            $customValues = $postModel->getCustomValues(get_the_ID());
            ?>


            <div class="row">

                <div class="main-content heading">
                    <div class="news-article-title">
                        <h2 class="title"><?php the_title()?></h2>
                        <p><?php echo $post->post_excerpt;?></p>
                        <span>Published <?php the_date()?></span>
                    </div>
                </div>

                <div class="right-column author-and-comments">
                    <!--author info-->
                    <div class="author-info">
                        <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.get_the_author_meta('ID').'&size=81') ?>
                        <a href="<?php echo get_the_author_meta('url')?>" class="primary"><?php the_author()?></a>
                        <span class="publication"><?php echo get_cimyFieldValue(get_the_author_meta('ID'), 'AUTHORBYLINE', false) ?></span>
                    </div>

                    <div class="ratings-reviews">
                        <div class="wrap">
                            <div class="reviews">
                                <a href="#comment-container" class="review-count"><?php comments_number('0', '1', '%') ?> </a>
                                <h4><?php comments_number('Comment', 'Comment', 'Comments') ?> </h4>
                            </div>
                        </div>
                    </div>

                </div>

            </div>


            <?php endwhile; endif; ?>

        <div class="row"><!-- begin .used-listings-->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/used-vehicle-listing.php?post_id='.get_the_ID()) ?>
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/more-news.php') ?>
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/wheels-guides.php') ?>
        </div>
    </div>
    <?php get_footer()?>
</body>
</html>