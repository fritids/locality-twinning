<?php

$contentDir = __DIR__ . '/../web/wp-content/' ;
$yuiCompressor = 'java -jar yuicompressor-2.4.7.jar ';

$files = array();
$files[] = $contentDir . "/themes/wheels/css/ui-darkness/jquery-ui-1.8.16.custom.css";
$files[] = $contentDir . "/themes/wheels/css/style.css";
$files[] = $contentDir . "/themes/wheels/css/wheels.css";
$files[] = $contentDir . "/plugins/wheels-my-wheels/my-profile.css";

$output = '';
foreach ($files as $file) {
    $output .= shell_exec($yuiCompressor . $file) . PHP_EOL;
}

file_put_contents($contentDir . '/themes/wheels/css/wheels.min.css', $output);