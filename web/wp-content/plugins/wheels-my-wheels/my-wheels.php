<?php
/*
Plugin Name: My-wheels
Plugin URI:
Description: Manage user account
Author:
Version: 1.0.0
Author URI:
*/

class MyWheels {
	var $pluginPath;
	var $pluginUrl;
	
	public function __construct()
	{
		// Set Plugin Path
		$this->pluginPath = dirname(__FILE__);
	
		// Set Plugin URL
		$this->pluginUrl = WP_PLUGIN_URL . '/wheels-my-wheels';

        //load related js file
        //wp_enqueue_script('myscript', $this->pluginPath.'my-wheels.js' );

	}
}

$mywheels = new MyWheels;





