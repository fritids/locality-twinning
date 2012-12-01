<?php
global $wpdb, $adModel;
$args['type'] = (isset($_GET['type']) && $_GET['type']) ? 'popular' : '';
$args['post_type'] = 'reviews';
$postModel = new \Emicro\Model\Post($wpdb);
?>

<?php get_header('meta') ?>

<body class="page reviews"><!-- begin #container-->

    <div id="container" data-role="page">
    <?php get_header()?>
    <div id="reviews" class="section-container clearfix">
    <?php wheels_breadcrumb() ?>

    <div class="row">
        <h2 class="title">Reviews</h2>

        <ul class="order-by-list">

            <li<?php if ($_GET['type'] != 'popular') echo ' class="on"'?>><a
                href="<?php echo site_url('reviews')?>">Latest</a></li>
            <li<?php if ($_GET['type'] == 'popular') echo ' class="on"'?>><a
                href="<?php echo site_url('reviews/?type=popular')?>">Popular</a></li>

        </ul>

        <!-- begin .tip-->
        <div class="tip">
            <strong>Tip:&nbsp;</strong>
            Click <img src="<?php echo get_template_directory_uri() ?>/img/compare-icon-tip.png" alt="compare icon"/>to compare
        </div>
        <!-- end .tip-->
    </div>


    <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/reviews/archive-reviews-carousel.php' )?>

    <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/reviews/landing-readers-though.php')?>

    <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/reviews/archive_reviews_review_video.php')?>

</div>
<?php get_footer()?>

</body>
</html>