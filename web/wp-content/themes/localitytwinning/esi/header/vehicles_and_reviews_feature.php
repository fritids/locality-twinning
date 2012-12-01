<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
global $wpdb;

$postModel = new \Emicro\Model\Post($wpdb);
$reviews = $postModel->getAll(array('limit' => 3, 'post_type' => 'reviews'));

?>


<ul>
    <?php foreach ($reviews AS $key => $review): ?>

    <li <?php if ($key == 2) {
        echo 'class = "last" ';
    }  ?>>
        <a href="<?php echo get_permalink($review->ID) ?>">

            <?php echo get_the_post_thumbnail($review->ID, 'driving-guide') ?>

<!--            <div class="rating white">-->
<!--                 --><?php
//                    //$vehicleReviewMeta = $postModel->getVehicleReviewData($review->ID);
//                    //if ($vehicleReviewMeta->star_rating != '0.0' ) { ?>
                <?php  //$starRating = explode('.', $vehicleReviewMeta->star_rating); ?>
<!--                <div class="value rating---><?php ////echo $starRating[0] ?><!-----><?php ////echo $starRating[1] ?><!--">-->
                    <?php //echo $vehicleReviewMeta->star_rating ?>
<!--                </div>-->
<!--                --><?php ////} ?>
<!--            </div>-->

            <p><?php echo $review->post_title ?></p>
        </a>
    </li>

    <?php endforeach; ?>

</ul>