<?php
error_reporting(0);
require_once 'wp-content/bootstrap.php';
require_once WP_CONTENT_DIR . '/plugins/solr-for-wordpress/SolrPhpClient/Apache/Solr/Service.php';

$solr = new Apache_Solr_Service(SOLR_HOST, SOLR_PORT, SOLR_PATH);

$autodata = new \Emicro\Model\Autodata($solr);
global $wpdb;
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);

$option = array(
    //'primary' => true,
    'make' => $_POST['make'],
    'total_special_offers' => array('start' => '1', 'end' => '*')
);
/*
$option = array(
    'total_special_offers' => array('start' => '1', 'end' => '*')
);
*/
$moreLink = false;
$queryLimit = 5;
if(isset($_POST['limit']))
{
    $moreLink = true;
    $queryLimit = $wpdb->escape((int)$_POST['limit']);
}
$search = $autodata->searchVehicle($option, 0, $queryLimit, 'year desc', true);

//var_dump($search['total']);

$result = array();

foreach($search['result'] as $index => $vehicle)
{

    if(isset($vehicle->special_offer_1)) $result[] = $vehicleModel->prepareSpecialOfferItem($vehicle, $vehicle->special_offer_1);
    if(isset($vehicle->special_offer_2)) $result[] = $vehicleModel->prepareSpecialOfferItem($vehicle, $vehicle->special_offer_2);
    if(isset($vehicle->special_offer_3)) $result[] = $vehicleModel->prepareSpecialOfferItem($vehicle, $vehicle->special_offer_3);
    if(isset($vehicle->special_offer_4)) $result[] = $vehicleModel->prepareSpecialOfferItem($vehicle, $vehicle->special_offer_4);
    if(isset($vehicle->special_offer_5)) $result[] = $vehicleModel->prepareSpecialOfferItem($vehicle, $vehicle->special_offer_5);
    if(isset($vehicle->special_offer_6)) $result[] = $vehicleModel->prepareSpecialOfferItem($vehicle, $vehicle->special_offer_6);
    if(isset($vehicle->special_offer_7)) $result[] = $vehicleModel->prepareSpecialOfferItem($vehicle, $vehicle->special_offer_7);
    if(isset($vehicle->special_offer_8)) $result[] = $vehicleModel->prepareSpecialOfferItem($vehicle, $vehicle->special_offer_8);

}
$resutCount = count($result);
if($resutCount)
{
    if($moreLink == false)
    {
        $queryLimit = 4;
    }
    $result = array_slice($result, 0, $queryLimit);
}
$result = implode('', $result);

if(empty($result))
{
    $result = 'No special offer found<br><br>';
}
echo json_encode( array('total' => $resutCount, 'data' => $result) );