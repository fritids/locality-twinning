<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;
$postModel = new \Emicro\Model\Post($wpdb);

$vehicleReviewMeta = $postModel->getVehicleReviewData($_GET['post_id']);
$userRating = explode('.', $vehicleReviewMeta->user_rating);

?>
<div
    class="value rating-<?php echo $userRating[0] ?>-<?php echo $userRating[1] ?>"><?php echo $vehicleReviewMeta->user_rating ?></div>
<h4>Consumer Rating</h4>