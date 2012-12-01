<?php
global $wpdb;
$articleID = (int)end( explode('/', $_SERVER['REQUEST_URI']) );
// If URL end with slash
if(empty($articleID)) $articleID = (int)end( explode('/', $_SERVER['REQUEST_URI'], -1) );

$results = $wpdb->get_var("
                    SELECT wp_posts.ID
                    FROM wp_posts
                    INNER JOIN  wp_postmeta ON wp_posts.ID = wp_postmeta.post_id
                    WHERE meta_key = 'uid' AND post_status = 'publish' AND meta_value = '$articleID' LIMIT 1");

if($results && get_permalink($results)) wp_redirect(get_permalink($results));
?>
<?php get_header('meta')?>
<body class="page article news mobile-page"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
    <?php get_header()?>
    <!-- begin news article-->
    <div id="news-article" class="section-container">

        <div class="row">
            <h1>No page found</h1>
        </div>

        <div class="row"><!-- begin .used-listings-->
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/used-vehicle-listing.php') ?>
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/more-news.php') ?>
            <?php echo wheels_esi_include(get_template_directory_uri().'/esi/wheels-guides.php') ?>
        </div>
    </div>
    <?php get_footer()?>
</body>
</html>