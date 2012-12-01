<?php

$contentDir = __DIR__ . '/../web/wp-content/' ;
$yuiCompressor = 'java -jar yuicompressor-2.4.7.jar ';

$files = array();
$files[] = $contentDir . "/themes/wheels/js/libs/jquery.easing.1.3.js";
$files[] = $contentDir . "/themes/wheels/js/vehicle-finder.js";
$files[] = $contentDir . "/themes/wheels/js/script.js";
$files[] = $contentDir . "/plugins/wheels-my-wheels/rajax.js";
$files[] = $contentDir . "/themes/wheels/js/reviews.js";
$files[] = $contentDir . "/themes/wheels/js/home.js";
$files[] = $contentDir . "/themes/wheels/js/wheels.js";
$files[] = $contentDir . "/themes/wheels/js/vehicles.js";


$output = '';
foreach ($files as $file) {
    $output .= shell_exec($yuiCompressor . $file) . PHP_EOL;
}

file_put_contents($contentDir . '/themes/wheels/js/wheels.min.js', $output);