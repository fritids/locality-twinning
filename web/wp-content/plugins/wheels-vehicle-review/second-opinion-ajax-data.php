<?php
//include '../../../wp-content/bootstrap.php';
require_once '../../../wp-load.php';
global $wpdb;
$PostModel = new \Emicro\Model\Post($wpdb);

//$template_url = get_template_directory_uri();

$page = (!empty($_GET['page'])) ? (int)$_GET['page'] : 1;
$limit = (!empty($_GET['limit'])) ? (int)$_GET['limit'] : 6;
$start = ($page - 1) * $limit;

$term = ($_GET['term']) ? $_GET['term'] : '';
$taxonomy = ($_GET['taxonomy']) ? $_GET['taxonomy'] : '';
$term2 = ($_GET['term2'] && $_GET['term2'] != 'all') ? $_GET['term2'] : '';

if($taxonomy == 'category') $taxonomy = 'vehicle-category';

$agrs = array(
    'limit' => $limit,
    'start' => $start,
    'post_type' => 'reviews',
    'term' => strtolower($term),
    'taxonomy' => $taxonomy,
    'term2' => strtolower($term2),
    'taxonomy2' => 'make'
);



// Set parameter count true to return total number of rows
$agrs['count'] = true;
$vehiclesCount = $PostModel->secondOpinionReviewData($agrs);

if($vehiclesCount[0]->num_rows == 0){
    echo json_encode (array('pagination' => '', 'data' => 'No vehicles found'));
    exit;
}
// Reset count parameter false to to show dataset
$agrs['count'] = false;

// Generate pagination link
$totalPageNumber = ceil( $vehiclesCount[0]->num_rows / $limit );
$paging = wheels_pagination($totalPageNumber, $page);


$agrs['custom_field'] = true;
// Get guides post
$vehicles = $PostModel->secondOpinionReviewData($agrs);


$data = '';

$loop = 0;
// Build vehicle data
global $post;
foreach($vehicles as $post)
{


    setup_postdata($post);


    $class = (($loop % 2) == 0 ) ? 'odd' : 'even';
    $data .= '<li class="' .$class. '">';
    $data .= '<div class="wrap">';
    $data .= '<a href="'.get_permalink( get_the_ID() ).'">';
    $data .= get_the_post_thumbnail(get_the_ID(), 'driving-guide');

    $data .= '<p>' .$post->second_opinion_title.' <strong class="small primary">Read the review</strong></p>';

    $data .= '</a>';
    //$data .= ($post->sponsor) ? '<span class="sponsor">Sponsored</span>' : '';
    $data .= '</div>';
    $data .= '<a data-id="" href="#" class="compare callout" rel="' .$post->vehicle_id_1. '">Compare <img alt="Compare this vehicle" src="'.get_template_directory_uri().'/img/compare-callout.png"/></a>';
    $data .= '</li>';
    $loop++;
}

// Return pagination link and guide data in json array
echo json_encode (array('pagination' => $paging, 'data' => $data, 'found' => $vehiclesCount[0]->num_rows));
?>
