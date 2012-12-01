<?php
require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;

$vehicleModel = new \Emicro\Model\Vehicle($wpdb);

$vehicles    = $vehicleModel->getVehicles(array('year' => array('start' => 0, 'end' => date('Y') + 1) ), 0, 150, 'year desc');

$makes = array();
$uniqueVehicles = array();

foreach ($vehicles['result'] as $vehicle) {
    if (!in_array($vehicle->make, $makes)) {
        $uniqueVehicles[] = $vehicle;
        $makes[] = $vehicle->make;
    }
    if (count($makes) == 12) {
        break;
    }
}

?>
<div class="row">

    <!-- begin .vehicle-profile-->
    <div data-controller="SlidesController" data-nthchild="4" class="vehicle-profile home-module">

        <div class="header">
            <h3>Vehicle Profiles</h3>
            <a href="/vehicles">All Vehicles</a>
        </div>

        <div class="viewport" style="overflow: hidden;">

            <div class="container" style="width: 3000px;">

                <ul>
                    <?php if(count($uniqueVehicles) > 0): foreach($uniqueVehicles as $vehicle):?>
                    <li class="slide">
                        <div class="pos">
                            <a href="<?php echo getVehicleProfileLink($vehicle)?>">
                                <img src="<?php echo getVehicleImageLink($vehicle->images[0], 204, 115) ?>" alt="<?php echo getVehicleProfileTitle($vehicle) ?>"/>
                                <p><?php echo getVehicleProfileTitle($vehicle) ?></p>
                            </a>
                        </div>
                    </li>
                    <?php endforeach; endif; ?>

                </ul>
            </div>
        </div>

        <div class="navigation">
            <a href="#" class="nav left">Left</a>
            <a href="#" class="nav right">Right</a>
        </div>

    </div>
    <!-- end .vehicle-profile-->
</div>