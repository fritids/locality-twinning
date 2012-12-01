<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js ie6 oldie" lang="en">
<![endif]--><!--[if IE 7]><html class="no-js ie7 oldie" lang="en">
<![endif]--><!--[if IE 8]><html class="no-js ie8 oldie" lang="en">
<![endif]--><!--[if gt IE 8]><!-->
<html lang="en" itemscope itemtype="http://schema.org/Review" class="no-js"><!--<![endif]-->
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?php bloginfo('name'); ?><?php global $pageTitle; echo (isset($pageTitle)) ? ' - ' . $pageTitle : wp_title('-'); ?></title>

    <meta name="description" content="<?php bloginfo('description')?>">
    <meta name="author" content="Star Media Group">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">

    <!-- google plus meta tags-->
    <meta itemprop="name" content="<?php bloginfo('name'); ?>">
    <meta itemprop="description" content="<?php bloginfo('description')?>">
    <link rel="shortcut icon" href="/favicon.ico" />
    <meta name="google-site-verification" content="5CBTxz3qkUjsZVjG6zIFxWXABkrEpAEDDNT916EZZAw" />
    <!--facebook open graph - for use when live/responding to facebook's linter, not considered HTML5 valid mark up
    <meta property="og:title" content="<?php bloginfo('name'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://www.wheels.ca">
    <meta property="og:image" content="http://google.com">
    <meta property="og:site_name" content="Wheels.ca">
    <meta property="fb:admins" content="565075440">-->

    <script type="text/javascript" src="<?php echo get_template_directory_uri() . "/js/helper.js" ?>"></script>

    <?php
    \Emicro\Plugin\Assets::css(get_template_directory_uri() . "/css/ui-darkness/jquery-ui-1.8.16.custom.css");
    \Emicro\Plugin\Assets::css(get_template_directory_uri() . "/css/style.css");
    \Emicro\Plugin\Assets::includeStyles();
    ?>

    <link id="mediaCSS" rel="stylesheet" href="/wp-content/themes/wheels/css/media.css?v=<?php echo PRODUCTION_ASSETS_VERSION ?>">

    <?php
    \Emicro\Plugin\Assets::css(get_template_directory_uri() . "/css/wheels.css");
    \Emicro\Plugin\Assets::css(plugins_url() . "/wheels-my-wheels/my-profile.css");
    \Emicro\Plugin\Assets::includeStyles();

    if (wheels_is_development_server()) {
        echo '<script type="text/javascript" src="' . get_template_directory_uri() . '/js/libs/modernizr-2.0.6.min.js"></script>';
    } else {
        echo '<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.5.3/modernizr.min.js"></script>';
    }

    wp_head();

    ?>
</head>
