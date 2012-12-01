<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

$class = urldecode($_GET["class"]);

global $wpdb;
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);
$vehicles = $vehicleModel->getVehicles(array('class' => $class), 0, 32, 'popularity desc');

$makes = array();
$uniqueVehicles = array();

foreach ($vehicles['result'] as $vehicle) {
    if (!in_array($vehicle->make, $makes)) {
        $uniqueVehicles[] = $vehicle;
        $makes[] = $vehicle->make;
    }
    if (count($makes) == 4) {
        break;
    }
}

$acode = array();

?>

<div class="row" id="alternative-container">
    <div class="popular-cars">
        <h3>Popular <?php echo $_GET['class']; ?> </h3><!-- begin .compare-table top-->

        <table class="compare-table">

                <tr class="image-row">
             <?php if(count($uniqueVehicles) > 0): foreach($uniqueVehicles as $vehicle):?>
                <?php $acode[] = $vehicle->acode;  ?>
                <td data-column-id="799281">
                    <div class="pos">
                        <div class="compare-image"><a href="<?php echo getVehicleProfileLink($vehicle)?>"><img src="<?php echo getVehicleImageLink($vehicle->images[0], 204, 115) ?>" alt="<?php echo getVehicleProfileTitle($vehicle) ?>"/></a></div>
                    </div>
                </td>
             <?php endforeach; endif; ?>
                </tr>

            <!-- end .image-row--><!-- begin .title-row-->
            <tr class="top-title-row">
            <?php if(count($uniqueVehicles) > 0): foreach($uniqueVehicles as $vehicle):?>
                <td data-column-id="799281">
                    <div class="pos">
                        <div class="compare-title"><h3><a href="<?php echo getVehicleProfileLink($vehicle)?>"><?php echo getVehicleProfileTitle($vehicle) ?></a></h3></div>
                    </div>
                </td>
            <?php endforeach; endif; ?>
            </tr>

            </table>

    </div>
</div>

<!--Compare Start-->

<!--Compare Start-->

<?php

$arrVehicle = array();

        foreach( $acode as $row )
        {
            $arrTemp = array();
            $args = array('acode' => array($row));
            $orderby = '';
            $vehicle = $vehicleModel->getVehicles($args, 0, 7, $orderby, true);
            if($vehicle['result'][0]->acode!=null)
            {
                //fetching styles start
                $vehicle1 = $vehicleModel->getVehicles(array('model_id' => $vehicle['result'][0]->model_id), 0, 10, '', true);
                $arr_style = array();
                foreach( $vehicle1['result'] as $row1 ){
                    $arr_style[$row1->acode] = $row1->style;
                }
                //end
                $arrVehicle[] = array(
                    'acode' => $vehicle['result'][0]->acode,
                    'profile_title' => getVehicleProfileTitle($vehicle['result'][0]),
                    'image_link' => getVehicleImageLink($vehicle['result'][0]->images[0],212,120),
                    //rating
                    'star_rating' => $vehicle['result'][0]->star_rating,
                    'user_rating' => $vehicle['result'][0]->user_rating,
                    //MSRP
                    'price' => number_format($vehicle['result'][0]->price),
                    //Performance
                    'engine' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->engine),
                    'horsepower' => $vehicleModel->getHorsePowerFormattedValue($vehicle),
                    'transmission' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->transmission),
                    'drive_type' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->drive_type),
                    'cylinder' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->cylinder),
                    'transmission_speed' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->transmission_speed),
                    //Fuel Economy
                    'fuel_economy_city' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->fuel_economy_city),
                    'fuel_economy_highway' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->fuel_economy_highway),
                    'fuel_type' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->fuel_type),
                    'fuel_tank_low_high' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->fuel_tank_low.'/'.$vehicle['result'][0]->fuel_tank_high),
                    //Safety
                    'airbags' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->safety_airbags),
                    'abs_brakes' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->safety_abs_brakes),
                    'traction_control' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->safety_traction_control),
                    'stability_control' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->safety_stability_control),
                    'safety_rating' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->safety_safety_rating),
                    'child_sensor' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->safety_airbags),
                    'park_distance' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->safety_airbags),
                    //Comfort & Convenience
                    'comfort_sunroof' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_sunroof),
                    'comfort_air_conditioning' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_air_conditioning),
                    'comfort_power_windows' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_power_windows),
                    'comfort_power_door_locks' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_power_door_locks),
                    'comfort_leather_seats' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_leather_seats),
                    'comfort_power_seats' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_power_seats),
                    'comfort_music_cd_in_dash' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_music_cd_in_dash),
                    'comfort_navigation_system' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_navigation_system),
                    'comfort_cruise' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_cruise),
                    'comfort_keyless_entry' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_keyless_entry),
                    'comfort_rain_sensing_wipers' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_rain_sensing_wipers),
                    'comfort_heated_seats' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_heated_seats),
                    'comfort_climate_control' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_climate_control),
                    'comfort_steering_wheel_control' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_steering_wheel_control),
                    'comfort_power_mirrors' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_power_mirrors),
                    //Interior
                    'max_seating' => $vehicleModel->getSeatingFormattedValue($vehicle['result'][0]),
                    'number_of_doors' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->number_of_doors),
                    'seats' => $vehicleModel->getSeatingFormattedValue($vehicle['result'][0]),
                    'comfort_power_adjustable_seats' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->comfort_power_adjustable_seats),
                    'specialty_seats' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->safety_airbags),
                    'interior' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->safety_airbags),
                    'leather_seats' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->safety_airbags),
                    'power_seats' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->safety_airbags),
                    'heated_seats' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->safety_airbags),
                    //awards
                    'awards' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->awards),

                    'model_id' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->model_id),
                    'styles' => $arr_style,
                );

            }
        }


?>

<!-- Compare Start -->

<div class="row">
    <!-- begin ul.acc-menu-->
    <ul data-controller="ClosedAccordionController" class="acc-menu">
    <li class="clearfix"><!-- begin .section-head-->
    <div class="section-head"><a href="#" class="heading">View Comparison</a></div>
    <!-- end .section-head--><!-- begin .collapsible-->
    <div class="collapsible clearfix"><!-- begin .compare-table bottom-->
     <?php if(!empty($arrVehicle)): ?>
    <table class="compare-table data-table">

    <!-- begin .data-row-->
    <tr class="data-row">
        <?php $i9 = 0; foreach($arrVehicle as $thisvehicle):
        $cid = 1;
        ?>
        <!-- begin compare column-->
        <td <?php echo ($thisvehicle['acode']== $arrAcode[count($arrAcode) - 1])?'class="sponsored"':'data-column-id="79928'.$cid.'"'?>>
            <div class="pos">
                <ul>
                    <li><strong>Price</strong>: $<?php echo ($thisvehicle['price'])?$thisvehicle['price']:'None'; ?></li>
                </ul>
                <?php if(!$i9): ?><h4>MSRP</h4><?php endif; ?>
            </div>
        </td>
        <!-- end compare column-->
        <?php $i9++; $cid++;
    endforeach;
        ?>
    </tr>
    <!-- end .data-row-->

     <tr class="data-row">
            <?php $i3 = 0; foreach($arrVehicle as $thisvehicle):
            $cid = 1;
            ?>
            <!-- begin compare column-->
            <td <?php echo ($thisvehicle['acode']== $arrAcode[count($arrAcode) - 1])?'class="sponsored"':'data-column-id="79928'.$cid.'"'?>>
                <div class="pos">
                    <ul>
                        <li><strong>Engine</strong>: <?php echo ($thisvehicle['engine'])?$thisvehicle['engine']:'No'; ?></li>
                        <li><strong>Horsepower</strong>: <?php echo ($thisvehicle['horsepower'])?$thisvehicle['horsepower']:'No'; ?></li>
                        <li><strong>Transmission</strong>: <?php echo ($thisvehicle['transmission'])?$thisvehicle['transmission']:'No'; ?></li>
                        <li><strong>Drive Type</strong>: <?php echo ($thisvehicle['drive_type'])?$thisvehicle['drive_type']:'No'; ?></li>
                        <li><strong>Cylinder</strong>: <?php echo ($thisvehicle['cylinder'])?$thisvehicle['cylinder']:'No'; ?></li>
                        <li><strong>Transmission Speed</strong>: <?php echo ($thisvehicle['transmission_speed'])?$thisvehicle['transmission_speed']:'No'; ?></li>
                    </ul>
                    <?php if(!$i3): ?><h4>Performance</h4><?php endif; ?>
                </div>
            </td>
            <!-- end compare column-->
            <?php $i3++; $cid++;
            endforeach;
            ?>
            </tr>
        <!-- end .data-row-->

        <!-- begin .data-row-->
        <tr class="data-row">
            <?php $i4 = 0; foreach($arrVehicle as $thisvehicle):
            $cid = 1;
            ?>
            <!-- begin compare column-->
            <td <?php echo ($thisvehicle['acode']== $arrAcode[count($arrAcode) - 1])?'class="sponsored"':'data-column-id="79928'.$cid.'"'?>>
                <div class="pos">
                    <ul>
                        <li><strong>City</strong>: <?php echo ($thisvehicle['fuel_economy_city'])?$thisvehicle['fuel_economy_city']:'None'; ?></li>
                        <li><strong>Highway</strong>: <?php echo ($thisvehicle['fuel_economy_highway'])?$thisvehicle['fuel_economy_highway']:'None'; ?></li>
                        <li><strong>Fuel Type</strong>: <?php echo ($thisvehicle['fuel_type'])?$thisvehicle['fuel_type']:'None'; ?></li>
                        <li><strong>Fuel Tank Low/High</strong>: <?php echo ($thisvehicle['fuel_tank_low_high'])?$thisvehicle['fuel_tank_low_high']:'None'; ?></li>
                    </ul>
                    <?php if(!$i4): ?><h4>Fuel Economy</h4><?php endif; ?>
                </div>
            </td>
            <!-- end compare column-->
            <?php $i4++; $cid++;
            endforeach;
            ?>
        </tr>
        <!-- end .data-row-->

        <!-- begin .data-row-->
        <tr class="data-row">
            <?php $i5 = 0; foreach($arrVehicle as $thisvehicle):
            $cid = 1;
            ?>
            <!-- begin compare column-->
            <td <?php echo ($thisvehicle['acode']== $arrAcode[count($arrAcode) - 1])?'class="sponsored"':'data-column-id="79928'.$cid.'"'?>>
                <div class="pos">
                    <ul>
                        <li><strong>Airbags</strong>: <?php echo ($thisvehicle['airbags'])?$thisvehicle['airbags']:'None'; ?></li>
                        <li><strong>ABS Brakes</strong>: <?php echo ($thisvehicle['abs_brakes'])?$thisvehicle['abs_brakes']:'None'; ?></li>
                        <li><strong>Traction Control</strong>: <?php echo ($thisvehicle['traction_control'])?$thisvehicle['traction_control']:'None'; ?></li>
                        <li><strong>Stability Control</strong>: <?php echo ($thisvehicle['stability_control'])?$thisvehicle['stability_control']:'None'; ?></li>
                        <li><strong>Safety Rating</strong>: <?php echo ($thisvehicle['safety_rating'])?$thisvehicle['safety_rating']:'None'; ?></li>
                        <li><strong>Child Sensor</strong>: <?php echo ($thisvehicle['child_sensor'])?$thisvehicle['child_sensor']:'None'; ?></li>
                        <li><strong>Park Distance Control</strong>: <?php echo ($thisvehicle['park_distance'])?$thisvehicle['park_distance']:'None'; ?></li>
                    </ul>
                    <?php if(!$i5): ?><h4>Safety</h4><?php endif; ?>
                </div>
            </td>
            <!-- end compare column-->
            <?php $i5++; $cid++;
            endforeach;
            ?>
        </tr>
        <!-- end .data-row-->

        <!-- begin .data-row-->
        <tr class="data-row">
            <?php $i6 = 0; foreach($arrVehicle as $thisvehicle):
            $cid = 1;
            ?>
            <!-- begin compare column-->
            <td <?php echo ($thisvehicle['acode']== $arrAcode[count($arrAcode) - 1])?'class="sponsored"':'data-column-id="79928'.$cid.'"'?>>
                <div class="pos">
                    <ul>
                        <li><strong>Sunroof</strong>: <?php echo ($thisvehicle['comfort_sunroof'])?$thisvehicle['comfort_sunroof']:'None'; ?></li>
                        <li><strong>Air Conditioning</strong>: <?php echo ($thisvehicle['comfort_air_conditioning'])?$thisvehicle['comfort_air_conditioning']:'None'; ?></li>
                        <li><strong>Power Windows</strong>: <?php echo ($thisvehicle['comfort_power_windows'])?$thisvehicle['comfort_power_windows']:'None'; ?></li>
                        <li><strong>Power Door Locks</strong>: <?php echo ($thisvehicle['comfort_power_door_locks'])?$thisvehicle['comfort_power_door_locks']:'None'; ?></li>
                        <li><strong>Leather Seats</strong>: <?php echo ($thisvehicle['comfort_leather_seats'])?$thisvehicle['comfort_leather_seats']:'None'; ?></li>
                        <li><strong>Power Seats</strong>: <?php echo ($thisvehicle['comfort_power_seats'])?$thisvehicle['comfort_power_seats']:'None'; ?></li>
                        <li><strong>Music System</strong>: <?php echo ($thisvehicle['comfort_music_cd_in_dash'])?$thisvehicle['comfort_music_cd_in_dash']:'None'; ?></li>
                        <li><strong>Navigation System</strong>: <?php echo ($thisvehicle['comfort_navigation_system'])?$thisvehicle['comfort_navigation_system']:'None'; ?></li>
                        <li><strong>Cruise</strong>: <?php echo ($thisvehicle['comfort_cruise'])?$thisvehicle['comfort_cruise']:'None'; ?></li>
                        <li><strong>Keyless Entry</strong>: <?php echo ($thisvehicle['comfort_keyless_entry'])?$thisvehicle['comfort_keyless_entry']:'None'; ?></li>
                        <li><strong>Rain Sensing Vipers</strong>: <?php echo ($thisvehicle['comfort_rain_sensing_wipers'])?$thisvehicle['comfort_rain_sensing_wipers']:'None'; ?></li>
                        <li><strong>Heated Seats</strong>: <?php echo ($thisvehicle['comfort_heated_seats'])?$thisvehicle['comfort_heated_seats']:'None'; ?></li>
                        <li><strong>Climate Control</strong>: <?php echo ($thisvehicle['comfort_climate_control'])?$thisvehicle['comfort_climate_control']:'None'; ?></li>
                        <li><strong>Steering Wheel Controls</strong>: <?php echo ($thisvehicle['comfort_steering_wheel_control'])?$thisvehicle['comfort_steering_wheel_control']:'None'; ?></li>
                        <li><strong>Power Mirrors</strong>: <?php echo ($thisvehicle['comfort_power_mirrors'])?$thisvehicle['comfort_power_mirrors']:'None'; ?></li>
                    </ul>
                    <?php if(!$i6): ?><h4>Comfort & Convenience</h4><?php endif; ?>
                </div>
            </td>
            <!-- end compare column-->
            <?php $i6++; $cid++;
            endforeach;
            ?>
        </tr>
        <!-- end .data-row-->

        <!-- begin .data-row-->
        <tr class="data-row">
            <?php $i7 = 0; foreach($arrVehicle as $thisvehicle):
            $cid = 1;
            ?>
            <!-- begin compare column-->
            <td <?php echo ($thisvehicle['acode']== $arrAcode[count($arrAcode) - 1])?'class="sponsored"':'data-column-id="79928'.$cid.'"'?>>
                <div class="pos">
                    <ul>
                        <li><strong>Max. Seating</strong>: <?php echo ($thisvehicle['max_seating'])?$thisvehicle['max_seating']:'None'; ?></li>
                        <li><strong>Number of Doors</strong>: <?php echo ($thisvehicle['number_of_doors'])?$thisvehicle['number_of_doors']:'None'; ?></li>
                        <li><strong>Seats</strong>: <?php echo ($thisvehicle['seats'])?$thisvehicle['seats']:'None'; ?></li>
                        <li><strong>Power Adjustable Seats</strong>: <?php echo ($thisvehicle['comfort_power_adjustable_seats'])?$thisvehicle['comfort_power_adjustable_seats']:'None'; ?></li>
                        <li><strong>Specialty Seats</strong>: <?php echo ($thisvehicle['specialty_seats'])?$thisvehicle['specialty_seats']:'None'; ?></li>
                        <li><strong>Interior</strong>: <?php echo ($thisvehicle['interior'])?$thisvehicle['interior']:'None'; ?></li>
                        <li><strong>Leather Seats</strong>: <?php echo ($thisvehicle['leather_seats'])?$thisvehicle['leather_seats']:'None'; ?></li>
                        <li><strong>Power Seats</strong>: <?php echo ($thisvehicle['power_seats'])?$thisvehicle['power_seats']:'None'; ?></li>
                        <li><strong>Heated Seats</strong>: <?php echo ($thisvehicle['heated_seats'])?$thisvehicle['heated_seats']:'None'; ?></li>
                    </ul>
                    <?php if(!$i7): ?><h4>Interior</h4><?php endif; ?>
                </div>
            </td>
            <!-- end compare column-->
            <?php $i7++; $cid++;
            endforeach;
            ?>
        </tr>
        <!-- end .data-row-->

        <!-- begin .data-row-->
        <tr class="data-row">
            <?php $i8 = 0; foreach($arrVehicle as $thisvehicle):
            $cid = 1;
            ?>
            <!-- begin compare column-->
            <td <?php echo ($thisvehicle['acode']== $arrAcode[count($arrAcode) - 1])?'class="sponsored"':'data-column-id="79928'.$cid.'"'?>>
                <div class="pos">
                    <ul>
                        <li><strong>Awards</strong>: <?php echo ($thisvehicle['awards'])?$thisvehicle['awards']:'None'; ?></li>
                    </ul>
                    <?php if(!$i8): ?><h4>Awards</h4><?php endif; ?>
                </div>
            </td>
            <!-- end compare column-->
            <?php $i8++; $cid++;
            endforeach;
            ?>
        </tr>

    <!-- end .data-row--><!-- begin .data-row-->


    <!-- end .data-row--></table>
    <?php endif; ?>
    </div>
    <!-- end .collapsible--></li>
    </ul>
<!-- end ul.acc-menu-->
</div>
