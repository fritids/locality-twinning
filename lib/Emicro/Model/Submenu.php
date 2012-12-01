<?php

namespace Emicro\Model;

include_once WP_CONTENT_DIR . '/bootstrap.php';

class Submenu extends Base
{
    protected $ads = array();
    protected $originals = array();

    public function getSubmenu()
    {

        $postType = get_query_var('post_type');
        $taxonomy  = get_query_var('taxonomy');
        $term      = get_query_var('term');

        $items = array();

        list($empty, $page, $vehicle_id) = explode('/', $_SERVER['REQUEST_URI']);

        switch($postType)
        {
            case 'news';
            case 'feature';
            case 'events';
                $items = $this->getNewsFeatureSubItem();
                break;
            case 'guides':
                $items = $this->getGuideSubItem();
                break;
            case 'reviews';
            case 'vehicles-reviews';
                $items = $this->getVehicleReviewSubItem();
                break;
        }

        switch($taxonomy)
        {
            case 'news-category';
            case 'feature-category';
            case 'events-category';
                $items = $this->getNewsFeatureSubItem();
                break;
            case 'guides-category':
                $items = $this->getGuideSubItem();
                break;
        }

        switch($page){
            case 'vehicles':
                $items = $this->getVehicleReviewSubItem();
                break;
            case 'dealers':
                $items = $this->getUsedCarSubItem();
                break;
        }

        /* Debug
        var_dump($postType);
        var_dump($taxonomy);
        var_dump($page);
        var_dump($items);
        */

        $html = $this->generateHTML($items);

        return $html;
    }

    public function getNewsFeatureSubItem(){
        $items = array();
        foreach(get_terms('news-category', array('hide_empty'=>false)) as $term):
            $items[$term->name] = get_term_link($term);
        endforeach;

        foreach(get_terms('feature-category', array('hide_empty'=>false)) as $term):
            $items[$term->name] = get_term_link($term);
        endforeach;

        return $items;
    }

    public function getGuideSubItem(){
        $items = array();
        foreach(get_terms('guides-category', array('hide_empty'=>false)) as $term):
            $items[$term->name] = get_term_link($term);
        endforeach;

        return $items;
    }

    public function getVehicleReviewSubItem(){
        $items = array();
        $items['Vehicles'] = '/vehicles/';
        $items['Reviews'] = '/reviews/';

        return $items;
    }

    public function getUsedCarSubItem(){
        $items = array();
        $items['Search Used Cars'] = 'http://vehicles.wheels.ca/used-cars/';
        $items['Dealers'] = '/dealers/';
        $items['Sell Your Vehicle'] = 'http://vehicles.wheels.ca/sell-your-vehicle/';
        return $items;
    }

    public function generateHTML($items)
    {
        $loop = 1;
        $html = '';
        foreach($items as $title => $url)
        {
            $class = ($loop == 1) ? ' class="first"' : '';
            $html .= '<li' . $class . '><a href="' . $url . '">' . $title . '</a></li>';
            $loop++;
        }

        return $html;
    }

}