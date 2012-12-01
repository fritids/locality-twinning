<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/wp-content/bootstrap.php");
$x = $wpdb->get_row("SELECT option_value FROM wp_options WHERE option_name='sponsor_vehicles'");
$sponsoredAcodes = unserialize(base64_decode($x->option_value));


global $wpdb;
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);
$sponsoredlList = array();

foreach($sponsoredAcodes as $acode)
{
    $fresh_acode = str_replace("\r","",$acode);
    $spon = $vehicleModel->getVehicles(array('acode'=>trim($fresh_acode)), 0, 1, '', true);
    if(!empty($spon))
    {
        $class = is_array($spon['result'][0]->class) ? $spon['result'][0]->class[0] : $spon['result'][0]->class;
        if (!empty($class)) {
            $sponsoredlList[$class] = $spon['result'][0]->acode;
        }
    }
}
$x = json_encode($sponsoredlList);
echo "\n";
echo "var sponsoredlList = $x;";
echo "\n";