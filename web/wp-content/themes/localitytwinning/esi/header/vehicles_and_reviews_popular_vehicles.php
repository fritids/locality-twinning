<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
global $wpdb;

$vehicleModel = new \Emicro\Model\Vehicle($wpdb);
$args = array('year' => array('start' => date('Y') - 1, 'end' => date('Y') + 1), 'primary' => true);
$popularVehicle = $vehicleModel->getVehicles($args, 0, 6, "popularity desc");

?>

<ul>
    <?php if ($popularVehicle['result']): foreach ($popularVehicle['result'] AS $vehicle): ?>
    <li<?php if (is_sponsored($vehicle->acode)) echo ' class="sponsored"'?>>
        <a href="<?php echo getVehicleProfileLink($vehicle) ?>">
            <?php echo getVehicleProfileTitle($vehicle) ?>
        </a>
    </li>
    <?php endforeach; endif; ?>
</ul>