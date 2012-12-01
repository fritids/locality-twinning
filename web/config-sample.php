<?php

/** Master Database */
define('DB_NAME', 'wheels');
define('DB_USER', 'root');
define('DB_PASSWORD', 'commonrbs');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

/** Solr */
define('SOLR_HOST', '127.0.0.1');
define('SOLR_PORT', '8080');
define('SOLR_PATH', '/solr/');

/** Memcached */
define('MEMCACHED_SERVER', '127.0.1.1');
define('MEMCACHED_PORT', '11211');


/** Social Media Plugins */
define('FACEBOOK_API_ID', '282856381794910');
define('FACEBOOK_SECRET', 'f93719c94e1c461edb983ee4c287c93f');
define('TWITTER_CONSUMER_KEY', 'YQ56nRDeNvT07hamRvPEQ');
define('TWITTER_CONSUMER_SECRET', 'UpsTs6qEYfpjOwDY6dtLB1zlQgTKPg1QlZpJFxFQ');
define('ADDTHIS_PUBID', 'ra-4f47debb668d5070');

/** Environment */
define('VARNISH_ENABLED', true);
define('VARNISH_SERVERS', '192.168.1.9:80');

/** Assets */
define('PRODUCTION_ASSETS_ENABLED', false);
define('PRODUCTION_ASSETS_VERSION', 1);
define('PRODUCTION_JS_URL', '/wp-content/themes/js/wheels.min.js');
define('PRODUCTION_CSS_URL', '/wp-content/themes/css/wheels.min.css');

define('DEFAULT_SPONSORED_MAKE', 'Ford');
define('DEFAULT_SPONSORED_MODEL', 'Fusion');
define('DEFAULT_SPONSORED_YEAR', '2012');
define('DEFAULT_SPONSORED_CLASS', 'Sedan');

define('OPTIMIZE_ADS_LOADING', true);
define('DISABLE_ALL_AD', false);