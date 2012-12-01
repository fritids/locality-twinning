<?php
global $wpdb;
require_once '../../../wp-load.php';
$postModel = new \Emicro\Model\Post($wpdb);
$template_url = get_template_directory_uri();
$page = ($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = ($_POST['limit']) ? (int)$_POST['limit'] : 6;
$start = ($page - 1) * $limit;
$agrs = array(
    'post_type'=> 'guides',
    'taxonomy'=> 'Guides-category',
    'term'=> esc_attr($_POST['term']),
    'limit' => $limit,
    'start' => $start
);

// Set parameter count true to return total number of rows
$agrs['count'] = true;
$guidesCount = $postModel->getAll($agrs);

if($guidesCount[0]->num_rows == 0){
    echo json_encode (array('pagination' => '', 'data' => 'No guide found'));
    exit;
}
// Reset count parameter false to to show dataset
$agrs['count'] = false;

// Generate pagination link
$totalPageNumber = ceil( $guidesCount[0]->num_rows / $limit );
$paging = wheels_pagination($totalPageNumber, $page);

// Get guides post
$guides = $postModel->getAll($agrs);

$data = '<div class="listing"><ul>';

$loop = 0;
// Build guide data
foreach($guides as $post)
{
    setup_postdata($post);
    $class = (($loop % 2) == 0 ) ? 'odd' : 'even';
    $data .= '<li class="' .$class. '">';
    $data .= '<div class="wrap">';
    $data .= '<a href="' .get_permalink($post->ID). '">';
    $data .= get_the_post_thumbnail($post->ID, 'driving-guide');
    $data .= '<p>' .get_the_title() . '</p>';
    $data .= '</a>';
    $data .= ($post->sponsor) ? '<span class="sponsor">Sponsored</span>' : '';
    $data .= '</div>';
    $data .= '</li>';
    $loop++;
}
$data .= '</ul></div>';

// Return pagination link and guide data in json array
echo json_encode (array('pagination' => $paging, 'data' => $data, 'found' => $guidesCount[0]->num_rows));

?>