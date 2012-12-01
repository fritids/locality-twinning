<?php

/**
 * Plugin Name: Varnish
 * Plugin URI: http://www.emicrograph.com
 * Description: Handles the varnish cache management.
 * Author: Emran Hasan
 * Version: 1.0
 * Author URI: http://www.emicrograph.com
 */

use \Emicro\Plugin\Varnish;

if (defined('VARNISH_SERVERS')) {
    $varnishServers = explode(',', VARNISH_SERVERS);
    foreach ($varnishServers as $server) {
        $parts = explode(':', $server);
        //Varnish::addServer($parts[0], $parts[1]);
    }
}