<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../lib/Emicro/Model/Autodata.php';
require_once __DIR__ . '/wp-content/plugins/solr-for-wordpress/SolrPhpClient/Apache/Solr/Service.php';

$solr = new Apache_Solr_Service(SOLR_HOST, SOLR_PORT, SOLR_PATH);
$autodata = new \Emicro\Model\Autodata($solr);

$criteria = array(
    'year' => 2011
);

// $autodata->updateVehicle(array('popularity' => rand(1, 100)), 'USC10FOT11CC0');
// 'Cargo Van' (length=9)
//          1 => string 'SUV' (len
//'Passenger Van' (length=13)
//          1 => string 'Sedan'
//
$result = $autodata->searchVehicle($criteria, 0, 10, 'last_modified desc', true);
foreach( $result['result'] as $row)
{
    var_dump($row);
}