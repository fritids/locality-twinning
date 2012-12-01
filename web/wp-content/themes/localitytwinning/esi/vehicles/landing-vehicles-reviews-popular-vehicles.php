<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';

global $wpdb;

$vehicleModel = new \Emicro\Model\Vehicle($wpdb);

$args = array('year' => array('start' => date('Y') - 1, 'end' => date('Y') + 1), 'primary' => true);

$vehicles = $vehicleModel->getVehicles($args, 0, 5, 'popularity desc', false);

$acodes = array();
$reviewsIds =  array();
?>

<div class="row"><!-- begin .popular-vehicles-->
    <div data-controller="ReaderOpinionController" class="popular-vehicles">
        <h3>Popular Vehicles<a href="<?php echo site_url('vehicles') ?>" class="view-all">All Vehicles</a></h3>
        <!-- begin .vehicles-->
        <div class="vehicles">
            <ul class="vehicles-list">

                <?php foreach($vehicles['result'] AS $key => $vehicle): $reviewsIds[$vehicle->acode] = $vehicle->review_id;?>

                <li<?php if($key == 4) echo ' class="last"' ?>>
                    <a class="vehicle" href="<?php echo getVehicleProfileLink($vehicle) ?>">

                        <?php if(empty($vehicle->images[0])){ ?>

                        <img src="<?php echo get_template_directory_uri() ?>/img/spacer.gif" width="163" height="91" alt="<?php echo getVehicleProfileTitle($vehicle) ?>"/>

                        <?php }else{ ?>

                        <img src="<?php echo getVehicleImageLink($vehicle->images[0], 163, 91) ?>" alt="<?php echo getVehicleProfileTitle($vehicle) ?>"/>

                        <?php } ?>

                        <h4><?php echo getVehicleProfileTitle($vehicle) ?></h4>
                    </a>
                    <a data-id="" href="#" class="compare callout" rel="<?php echo $vehicle->acode ?>">
                        Compare <img alt="Compare this vehicle" src="<?php echo get_template_directory_uri() ?>/img/compare-callout.png"/>
                    </a>
                </li>

                <?php endforeach; ?>
            </ul>
        </div>
        <!-- end .vehicles-->

        <!-- begin .opinions-->
        <div class="opinions">

            <h4>Reader Opinion</h4>
            <div class="nub">&nbsp;</div>
            <ul class="opinions-list">

            <?php $loop = 1;
            foreach($reviewsIds as $key => $reviewsId):
            ?>

                <li<?php if($loop == 1) echo ' class="first"' ?>>

                    <?php
                    if(!empty($reviewsId)):
                        $comments = get_comments( array('post_id' => $reviewsId, 'number' => 1, 'status' => 'approve') );
                        if(empty($comments)):
                            ?>

                            <!-- if comment found -->
                            <div class="quote">No comment available at the moment.</div>
                            <div class="author">
                                <strong class="username">&nbsp;</strong>
                            </div>
                            <a href="/vehicles/<?php echo $key ?>/#comment-container" class="read-more">Be the first to comment.</a>

                            <?php
                        else:

                            foreach($comments as $comment):
                                ?>

                                <div class="quote">&ldquo; <?php echo character_limiter($comment->comment_content, 100 , '&hellip;') ?> &rdquo;</div>
                                <div class="author">
                                    <strong class="username"><?php echo $comment->comment_author ?></strong>
                                    <!-- <em class="reputation gear-1">1st gear</em> -->
                                </div>
                                <a href="<?php echo get_permalink($comment->comment_post_ID)?>#comment-container" class="read-more">Read more</a>

                                <?php
                            endforeach;
                        endif;
                    else:
                        ?>
                        <!-- if vehicle id  or empty -->
                        <div class="quote">&nbsp;</a></div>
                        <div class="author">
                            <strong class="username"></strong>
                        </div>
                        <a href="" class="read-more"></a>
                        <?php
                    endif;
                    ?>

                </li>

            <?php $loop++; endforeach; ?>

            </ul>
        </div>
        <!-- end .opinions-->

    </div>
    <!-- end .popular-vehicles-->
</div>