<?php
require '../../../../wp-load.php';
//error_reporting(E_WARNING);
//require_once $_SERVER['DOCUMENT_ROOT'].'/wp-content/bootstrap.php';

$vehicleModel = new \Emicro\Model\Vehicle();
$class = $_GET['class'];
$year = $_GET['year'];
$make = $_GET['make'];

$vehicles = $vehicleModel->getVehicles(array('class' => $class, 'year' => $year ),0, 40 ,'popularity desc',true);

$makes = array();
$uniqueVehicles = array();

if (!empty($vehicles['result'])){
    foreach ($vehicles['result'] as $vehicle) {
        if (!in_array($vehicle->make, $makes)) {
            if ($make != $vehicle->make){
                $uniqueVehicles[] = $vehicle;
                $makes[] = $vehicle->make;
            }
        }
        if (count($makes) == 4) {
            break;
        }
    }
}
?>
<div data-controller="SlidesController" data-mobileonly="true" class="module comparable-options clearfix">
    <h3>Similar Options</h3>
    <div class="viewport">
        <ul class="listing clearfix container">
        <?php
        if (!empty($uniqueVehicles)):
            foreach($uniqueVehicles as $relatedVehicle):
                if ( !in_array($relatedVehicle->acode, $vehicles) ):
        ?>
            <li class="vehicle slide">
                <div class="wrap">
                    <a href="<?php echo getVehicleProfileLink($relatedVehicle)?>">
                        <img src="<?php echo getVehicleImageLink($relatedVehicle->images[0], 120, 68)?>" alt="<?php echo getVehicleProfileTitle($relatedVehicle) ?>"/>
                        <span class="title"><?php echo getVehicleProfileTitle($relatedVehicle) ?></span>
                    </a>
                    <!-- <span class="sponsor">Sponsored</span> -->
                    <a href="#" rel="<?php echo $relatedVehicle->acode?>" class="compare callout">Compare<img src="<?php echo get_template_directory_uri();?>/img/compare-callout.png" alt="Compare this vehicle"/></a>
                </div>
            </li>
        <?php
                endif;
            endforeach;
        endif;
        ?>
        </ul>
    </div>
    <a href="/vehicle-finder" class="primary">More</a>
</div>