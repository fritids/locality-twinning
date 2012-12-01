<?php
require_once 'wp-load.php';
global $wpdb;

error_reporting(0);
header("Content-type: text/xml");

$postModel = new \Emicro\Model\Post($wpdb);
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);

$root = new SimpleXMLElement("<?xml version=\"1.0\"?><menus></menus>");

// Home menu
$menu = $root->addChild('menu');
$menu->addChild('title', 'Home');
$menu->addChild('link', 'http://www.wheels.ca/');
$menu->addChild('image', '');

// ## News & Feature Menu ##
// Top level menu
$menu = $root->addChild('menu');
$menu->addChild('title', 'News &amp; Features');
$menu->addChild('link', 'http://www.wheels.ca/news/');
$menu->addChild('image', '');

// Left side items
$side_menu = $menu->addChild('side_menu');
foreach(get_terms('news-category', array('hide_empty'=>false)) as $term):
    $item = $side_menu->addChild('menu');
    $item->addChild('title', $term->name);
    $item->addChild('link', get_term_link($term));
    $item->addChild('image', '');
endforeach;
foreach(get_terms('feature-category', array('hide_empty'=>false)) as $term):
    $item->addChild('title', $term->name);
    $item->addChild('link', get_term_link($term));
    $item->addChild('image', '');
endforeach;

// Latest news items
$latestNews = $postModel->getAll(array('limit' => 5, 'post_type' => 'news'));
$side_menu = $menu->addChild('news');
foreach ($latestNews AS $news):
    $item = $side_menu->addChild('menu');
    $item->addChild('title', $news->post_title);
    $item->addChild('link', get_permalink($news->ID));
    $item->addChild('image', '');
endforeach;

// Latest features item
$latestfeature = $postModel->getAll(array('limit' => 3, 'custom_field' => true, 'post_type' => 'feature'));
$side_menu = $menu->addChild('features');
foreach ($latestfeature AS $key => $feature) :
    $item = $side_menu->addChild('menu');
    $item->addChild('title', $feature->post_title);
    $item->addChild('link', get_permalink($feature->ID));
    $item->addChild('image', get_the_post_thumbnail($feature->ID, 'driving-guide'));
endforeach;

// ## Vehicles & Reviews Menu ##
// Top level menu
$menu = $root->addChild('menu');
$menu->addChild('title', 'Vehicles &amp; Reviews');
$menu->addChild('link', 'http://www.wheels.ca/vehicles-reviews/');
$menu->addChild('image', '');

// Left side items
$side_menu = $menu->addChild('side_menu');
$item = $side_menu->addChild('menu');
$item->addChild('title', 'Vehicles');
$item->addChild('link', 'http://www.wheels.ca/vehicles/');
$item->addChild('image', '');

$item = $side_menu->addChild('menu');
$item->addChild('title', 'Reviews');
$item->addChild('link', 'http://www.wheels.ca/reviews/');
$item->addChild('image', '');

// Popular vehicles items
$args = array('year' => array('start' => date('Y') - 1, 'end' => date('Y') + 1), 'primary' => true);
$popularVehicle = $vehicleModel->getVehicles($args, 0, 6, "popularity desc");

$side_menu = $menu->addChild('vehicles');
foreach ($popularVehicle['result'] AS $vehicle):
    $item = $side_menu->addChild('menu');
    $item->addChild('title', site_url().getVehicleProfileTitle($vehicle));
    $item->addChild('link', site_url().getVehicleProfileLink($vehicle));
    $item->addChild('image', '');
endforeach;

// Latest reviews items
$reviews = $postModel->getAll(array('limit' => 3, 'post_type' => 'reviews'));
$side_menu = $menu->addChild('reviews');
foreach ($reviews AS $key => $review) :
    $item = $side_menu->addChild('menu');
    $item->addChild('title', $review->post_title);
    $item->addChild('link', get_permalink($review->ID));
    $item->addChild('image', get_the_post_thumbnail($review->ID, 'driving-guide'));
endforeach;

// ## Guides menu ##
// Top level items
$menu = $root->addChild('menu');
$menu->addChild('title', 'Guides');
$menu->addChild('link', 'http://www.wheels.ca/news/');
$menu->addChild('image', '');

// Left side items
$side_menu = $menu->addChild('side_menu');
foreach(get_terms('guides-category', array('hide_empty'=>false)) as $term):
    $item = $side_menu->addChild('menu');
    $item->addChild('title', $term->name);
    $item->addChild('link', get_term_link($term));
    $item->addChild('image', '');
endforeach;

// Latest guides item
$latestGuides = $postModel->getAll(array('limit' => 5, 'post_type' => 'guides'));
$side_menu = $menu->addChild('guides');
foreach ($latestGuides AS $guide):
    $item = $side_menu->addChild('menu');
    $item->addChild('title', $guide->post_title);
    $item->addChild('link', get_permalink($guide->ID));
    $item->addChild('image', '');
endforeach;

// Experts items
$experts = $postModel->getExpertsPost(4);
$side_menu = $menu->addChild('experts');
foreach ($experts AS $key => $post) : setup_postdata($post);
    $item = $side_menu->addChild('menu');
    $item->addChild('title', $post->post_title);
    $item->addChild('link', get_permalink($post->ID));
    $item->addChild('image', get_the_post_thumbnail($post->ID, 'driving-guide'));
    $item->addChild('author', get_the_author());
    $item->addChild('author_image', get_avatar(get_the_author_meta('ID'), 50));
endforeach;

// ## Used car menu ##
// Top level item
$menu = $root->addChild('menu');
$menu->addChild('title', 'Used Cars');
$menu->addChild('link', 'http://vehicles.wheels.ca/used-cars/');
$menu->addChild('image', '');

// Left side items
$side_menu = $menu->addChild('side_menu');

$item = $side_menu->addChild('menu');
$item->addChild('title', 'Search Used Cars');
$item->addChild('link', 'http://vehicles.wheels.ca/used-cars/');
$item->addChild('image', '');

$item = $side_menu->addChild('menu');
$item->addChild('title', 'Dealers');
$item->addChild('link', 'http://www.wheels.ca/dealers/');
$item->addChild('image', '');

$item = $side_menu->addChild('menu');
$item->addChild('title', 'Sell Your Vehicle');
$item->addChild('link', 'http://vehicles.wheels.ca/sell-your-vehicle/');
$item->addChild('image', '');

// Redner XML Output
echo $root->asXML();