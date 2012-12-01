<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';
global $wpdb;
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);
?>
<div class="row"><!-- begin .carousel-->

    <?php
    $args = array('year' => array('start' => date('Y') - 1, 'end' => date('Y') + 1), 'primary' => true);
    $orderby = 'popularity desc';
    $vehicles = $vehicleModel->getVehicles($args, 0, 12, $orderby, true);

    if($vehicles['result']):
        ?>

        <div id="vehicleCarousel2" data-controller="CarouselController" class="carousel slide">

            <div class="carousel-inner">

                <div class="item active">
                    <div class="pos">

                        <?php

                        $loop = 1;
                        $break_div = array(4,8);

                        foreach($vehicles['result'] as $vehicle):
                            switch($loop){
                                case 1: case 5: case 9: $elm_class = 'prime tl'; break;
                                case 2: case 6: case 10: $elm_class = 'tm'; break;
                                case 3: case 7: case 11: $elm_class = 'bm'; break;
                                case 4: case 8: case 12: $elm_class = 'secondary tr'; break;
                            }
                            switch($loop){
                                case 1: case 5: case 9: $imageSrc = getVehicleImageLink($vehicle->images[0], 432, 240); break;
                                case 2: case 3: case 6: case 7: case 10: case 11: $imageSrc = getVehicleImageLink($vehicle->images[0], 208, 119); break;
                                case 4: case 8: case 12: $imageSrc = getVehicleImageLink($vehicle->images[0], 208, 240, 'height'); break;
                                default: $imageSrc = getVehicleImageLink($vehicle->images[0], 208, 119); break;
                            }
                            ?>

                            <div class="feature-container <?php echo $elm_class?>">

                                <img src="<?php echo $imageSrc;?>" alt="" />

                                <div class="copy">
                                    <div class="pos">
                                        <h4>
                                            <a href="<?php echo getVehicleProfileLink($vehicle)?>">
                                                <?php echo getVehicleProfileTitle($vehicle) ?>&nbsp;&raquo;
                                            </a>
                                        </h4>
                                    </div>
                                </div>

                                <div class="overlay" title="<?php echo $vehicle->popularity?>">&nbsp;</div>

                                <a data-id="" href="#" class="compare callout" rel="<?php echo $vehicle->acode ?>">Compare <img alt="Compare this vehicle" src="<?php echo get_template_directory_uri();?>/img/compare-callout.png"/></a>

                            </div>

                            <?php
                            if(in_array($loop, $break_div)) echo '</div></div><div class="item"><div class="pos">';
                            $loop++;
                        endforeach;?>

                    </div>
                </div>

            </div>

            <a href="#vehicleCarousel2" data-slide="prev" class="carousel-control left">&lsaquo;</a>
            <a href="#vehicleCarousel2" data-slide="next" class="carousel-control right">&rsaquo;</a>

        </div>
        <!-- end .carousel-->
        <?php endif;?>

    <?php
    $args = array('year' => array('start' => 0, 'end' => date('Y') + 1), 'primary' => true);
    $orderby = 'year desc';
    $vehicles = $vehicleModel->getVehicles($args, 0, 12, $orderby, true);

    if($vehicles['result']):
        ?>

        <div id="vehicleCarousel" data-controller="CarouselController" class="carousel slide"  style="display: none;">

            <div class="carousel-inner">

                <div class="item active">
                    <div class="pos">

                        <?php

                        $loop = 1;
                        $break_div = array(4,8);

                        foreach($vehicles['result'] as $vehicle):
                            switch($loop){
                                case 1: case 5: case 9: $elm_class = 'prime tl'; break;
                                case 2: case 6: case 10: $elm_class = 'tm'; break;
                                case 3: case 7: case 11: $elm_class = 'bm'; break;
                                case 4: case 8: case 12: $elm_class = 'secondary tr'; break;
                            }
                            switch($loop){
                                case 1: case 5: case 9: $imageSrc = getVehicleImageLink($vehicle->images[0], 432, 240); break;
                                case 2: case 3: case 6: case 7: case 10: case 11: $imageSrc = getVehicleImageLink($vehicle->images[0], 208, 119); break;
                                case 4: case 8: case 12: $imageSrc = getVehicleImageLink($vehicle->images[0], 208, 240, 'height'); break;
                                default: $imageSrc = getVehicleImageLink($vehicle->images[0], 208, 119); break;
                            }
                            ?>

                            <div class="feature-container <?php echo $elm_class?>">

                                <img src="<?php echo $imageSrc;?>" alt="" />

                                <div class="copy">
                                    <div class="pos">
                                        <h4>
                                            <a href="<?php echo getVehicleProfileLink($vehicle)?>">
                                                <?php echo getVehicleProfileTitle($vehicle)?>&nbsp;&raquo;
                                            </a>
                                        </h4>
                                    </div>
                                </div>

                                <div class="overlay" title="<?php echo $vehicle->popularity?>">&nbsp;</div>

                                <a data-id="" href="#" class="compare callout" rel="<?php echo $vehicle->acode ?>">Compare <img alt="Compare this vehicle" src="<?php echo get_template_directory_uri();?>/img/compare-callout.png"/></a>

                            </div>

                            <?php
                            if(in_array($loop, $break_div)) echo '</div></div><div class="item"><div class="pos">';
                            $loop++;
                        endforeach;?>

                    </div>
                </div>

            </div>

            <a href="#vehicleCarousel" data-slide="prev" class="carousel-control left">&lsaquo;</a>
            <a href="#vehicleCarousel" data-slide="next" class="carousel-control right">&rsaquo;</a>

        </div>
        <!-- end .carousel-->
        <?php endif;?>

</div>