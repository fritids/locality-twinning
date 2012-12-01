<?php
require_once("../../../wp-load.php");
global $wpdb;
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);

//this works for archive-compare page function request
$arrVehicle = array();
if(isset($_REQUEST["compare_acode"]) && $_REQUEST["compare_acode"]!=''){
    $strAcode = $_REQUEST["compare_acode"];
    $arrAcode = explode("|",$strAcode);

    $sponsored = array_pop($arrAcode);
    $arrAcode = array_reverse($arrAcode);

    $arrAcode[] = $sponsored;


        foreach( $arrAcode as $row )
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
                    'child sensor' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->safety_airbags),
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
                    'max_seating' => '', $vehicleModel->getSeatingFormattedValue($vehicle),
                    'number_of_doors' => $vehicleModel->vehicleFormatValue($vehicle['result'][0]->number_of_doors),
                    'seats' => '', $vehicleModel->getSeatingFormattedValue($vehicle),
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

}
?>


<div class="row">
    <!-- begin ul.acc-menu-->
    <ul data-controller="ClosedAccordionController" class="acc-menu">
    <li class="clearfix"><!-- begin .section-head-->
    <div class="section-head"><a href="#" class="heading">View Comparison</a></div>
    <!-- end .section-head--><!-- begin .collapsible-->
    <div class="collapsible clearfix"><!-- begin .compare-table bottom-->
    <table class="compare-table data-table">

    <!-- begin .data-row-->
    <tr class="data-row"><!-- begin compare column-->
        <td data-column-id="799281">
            <div class="pos"><p>Quisque tempor varius cursus. Donec vel felis ac tortor
                sagittis ultrices nec nec turpis. Fusce eget ultricies libero. Nam volutpat
                massa sed mauris feugiat in gravida lacus iaculis.</p><h4>Lorem ipsum</h4>
            </div>
        </td>
        <!-- end compare column--><!-- begin compare column-->
        <td data-column-id="799282">
            <div class="pos"><p>Duis sodales pulvinar elit. Maecenas tincidunt metus non
                lectus egestas euismod aliquam odio elementum. Etiam rutrum rhoncus velit,
                eu dictum odio commodo tristique.</p></div>
        </td>
        <!-- end compare column--><!-- begin compare column-->
        <td data-column-id="799283">
            <div class="pos"><p>Quisque tempor varius cursus. Donec vel felis ac tortor
                sagittis ultrices nec nec turpis. Fusce eget ultricies libero. Nam volutpat
                massa sed mauris feugiat in gravida lacus iaculis.</p></div>
        </td>
        <!-- end compare column--><!-- begin sponsored column-->
        <td>
            <div class="pos"><p>Proin porta ultrices commodo. Vestibulum libero enim,
                ultricies in laoreet in, imperdiet in sem. Vivamus vel ante id nisi
                elementum tincidunt. Phasellus suscipit sagittis pretium. Fusce lacus elit,
                porta rhoncus vestibulum nec, facilisis et erat.</p></div>
        </td>
        <!-- end sponsored column--></tr>
    <!-- end .data-row--><!-- begin .data-row-->
    <tr class="data-row"><!-- begin compare column-->
        <td data-column-id="799281">
            <div class="pos">
                <ul>
                    <li>Aenean ac ante non enim feugiat ornare eu pulvinar dolor.</li>
                    <li>Integer venenatis est at nisl pulvinar eget vestibulum urna
                        tempus.
                    </li>
                    <li>Vestibulum sed nisl dui, congue rutrum tortor.</li>
                    <li>Vestibulum sed nisl dui, congue rutrum tortor.</li>
                    <li>Nulla nec ante ac ligula vehicula venenatis.</li>
                    <li>Suspendisse feugiat metus id augue egestas ornare.</li>
                    <li>Vestibulum sed nisl dui, congue rutrum tortor.</li>
                </ul>
                <h4>Lorem ipsum</h4></div>
        </td>
        <!-- end compare column--><!-- begin compare column-->
        <td data-column-id="799282">
            <div class="pos">
                <ul>
                    <li>Integer venenatis est at nisl pulvinar eget vestibulum urna
                        tempus.
                    </li>
                    <li>Aenean ac ante non enim feugiat ornare eu pulvinar dolor.</li>
                    <li>Vestibulum sed nisl dui, congue rutrum tortor.</li>
                    <li>Vestibulum sed nisl dui, congue rutrum tortor.</li>
                    <li>Vestibulum sed nisl dui, congue rutrum tortor.</li>
                    <li>Integer venenatis est at nisl pulvinar eget vestibulum urna
                        tempus.
                    </li>
                    <li>Aenean ac ante non enim feugiat ornare eu pulvinar dolor.</li>
                    <li>Integer venenatis est at nisl pulvinar eget vestibulum urna
                        tempus.
                    </li>
                    <li>Suspendisse feugiat metus id augue egestas ornare.</li>
                </ul>
            </div>
        </td>
        <!-- end compare column--><!-- begin compare column-->
        <td data-column-id="799283">
            <div class="pos">
                <ul>
                    <li>Integer venenatis est at nisl pulvinar eget vestibulum urna
                        tempus.
                    </li>
                    <li>Vestibulum sed nisl dui, congue rutrum tortor.</li>
                    <li>Nulla nec ante ac ligula vehicula venenatis.</li>
                    <li>Vestibulum sed nisl dui, congue rutrum tortor.</li>
                    <li>Integer venenatis est at nisl pulvinar eget vestibulum urna
                        tempus.
                    </li>
                    <li>Integer venenatis est at nisl pulvinar eget vestibulum urna
                        tempus.
                    </li>
                    <li>Aenean ac ante non enim feugiat ornare eu pulvinar dolor.</li>
                </ul>
            </div>
        </td>
        <!-- end compare column--><!-- begin sponsored column-->
        <td>
            <div class="pos">
                <ul>
                    <li>Suspendisse feugiat metus id augue egestas ornare.</li>
                    <li>Vestibulum sed nisl dui, congue rutrum tortor.</li>
                    <li>Vestibulum sed nisl dui, congue rutrum tortor.</li>
                    <li>Nulla nec ante ac ligula vehicula venenatis.</li>
                    <li>Nulla nec ante ac ligula vehicula venenatis.</li>
                    <li>Aenean ac ante non enim feugiat ornare eu pulvinar dolor.</li>
                    <li>Vestibulum sed nisl dui, congue rutrum tortor.</li>
                    <li>Aenean ac ante non enim feugiat ornare eu pulvinar dolor.</li>
                    <li>Vestibulum sed nisl dui, congue rutrum tortor.</li>
                </ul>
            </div>
        </td>
        <!-- end sponsored column--></tr>
    <!-- end .data-row--><!-- begin .data-row-->
    <tr class="data-row"><!-- begin compare column-->
        <td data-column-id="799281">
            <div class="pos">
                <ul>
                    <li><strong>SAE Net Horsepower @ RPM</strong>: 160 @ 6500</li>
                    <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                    <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                    <li><strong>EPA Classification</strong>: Compact</li>
                    <li><strong>Drivetrain</strong>: Front Wheel Drive</li>
                    <li><strong>Drivetrain</strong>: Front Wheel Drive</li>
                    <li><strong>SAE Net Horsepower @ RPM</strong>: 160 @ 6500</li>
                    <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                    <li><strong>Drivetrain</strong>: Front Wheel Drive</li>
                </ul>
                <h4>Lorem ipsum</h4></div>
        </td>
        <!-- end compare column--><!-- begin compare column-->
        <td data-column-id="799282">
            <div class="pos">
                <ul>
                    <li><strong>Trans Description Cont.</strong>: Automatic</li>
                    <li><strong>SAE Net Horsepower @ RPM</strong>: 160 @ 6500</li>
                    <li><strong>EPA Classification</strong>: Compact</li>
                    <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                    <li><strong>Trans Description Cont.</strong>: Automatic</li>
                    <li><strong>EPA Classification</strong>: Compact</li>
                    <li><strong>Drivetrain</strong>: Front Wheel Drive</li>
                    <li><strong>EPA Classification</strong>: Compact</li>
                    <li><strong>SAE Net Horsepower @ RPM</strong>: 160 @ 6500</li>
                    <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                </ul>
            </div>
        </td>
        <!-- end compare column--><!-- begin compare column-->
        <td data-column-id="799283">
            <div class="pos">
                <ul>
                    <li><strong>Drivetrain</strong>: Front Wheel Drive</li>
                    <li><strong>EPA Classification</strong>: Compact</li>
                    <li><strong>SAE Net Horsepower @ RPM</strong>: 160 @ 6500</li>
                    <li><strong>Drivetrain</strong>: Front Wheel Drive</li>
                    <li><strong>Drivetrain</strong>: Front Wheel Drive</li>
                    <li><strong>Drivetrain</strong>: Front Wheel Drive</li>
                    <li><strong>Trans Description Cont.</strong>: Automatic</li>
                    <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                </ul>
            </div>
        </td>
        <!-- end compare column--><!-- begin sponsored column-->
        <td>
            <div class="pos">
                <ul>
                    <li><strong>EPA Classification</strong>: Compact</li>
                    <li><strong>EPA Classification</strong>: Compact</li>
                    <li><strong>EPA Classification</strong>: Compact</li>
                    <li><strong>Drivetrain</strong>: Front Wheel Drive</li>
                    <li><strong>Trans Description Cont.</strong>: Automatic</li>
                    <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                    <li><strong>EPA Classification</strong>: Compact</li>
                    <li><strong>EPA Classification</strong>: Compact</li>
                    <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                    <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                    <li><strong>SAE Net Horsepower @ RPM</strong>: 160 @ 6500</li>
                    <li><strong>Trans Description Cont.</strong>: Automatic</li>
                </ul>
            </div>
        </td>
        <!-- end sponsored column--></tr>
    <!-- end .data-row--></table>
    </div>
    <!-- end .collapsible--></li>
    </ul>
<!-- end ul.acc-menu-->
</div>
