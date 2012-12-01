<?php

/** Root of our full application */
define('APP_ROOT', realpath($_SERVER['DOCUMENT_ROOT'] . '/../'));

/** Include our library and 3rd party library paths */
set_include_path(implode(PATH_SEPARATOR, array(
    APP_ROOT . '/lib',
    APP_ROOT . '/vendor',
    get_include_path()
)));

/** The PSR-0 autoloader */
require 'gwc.autoloader.php';

/** If its available, then include WP database */
global $wpdb;

/** If its false, then we're handling things ourselves (by not loading WP fully) */
if (!$wpdb) {

    /** The required constants */
    define('ABSPATH', realpath($_SERVER['DOCUMENT_ROOT']) . '/');
    define('WPINC', 'wp-includes');
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

    /** We need the DB config, global WP functions and HyperDB class */
    require_once ABSPATH .'/config.php';
    require_once ABSPATH . WPINC . '/functions.php';
    require_once WP_CONTENT_DIR . '/db.php';

    /** The HyperDB instance */
    $wpdb = new hyperdb();

}

