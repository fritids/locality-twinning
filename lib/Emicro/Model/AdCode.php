<?php
namespace Emicro\Model;
include_once WP_CONTENT_DIR . '/bootstrap.php';
class AdCode extends Base
{
    public function getAd($dimension)
    {

        $prefix = $this->prefix;
        $sql = $this->db->prepare("SELECT * FROM {$prefix}wheels_ad_tag_mapping ");
        $post = $this->db->get_results($sql);


        basename($_SERVER['REQUEST_URI']);

        global $wpdb;

        $postModel = new \Emicro\Model\Post($wpdb);

        //var_dump($postModel->getById(get_the_ID())); die;



        //var_dump($post ,basename( $_SERVER['REQUEST_URI']));die;

        If (is_single()){
        $make = wp_get_post_terms(get_the_ID(), 'make');

        $taxonomyData['make'] = $make[0]->name;

        $vehicleYear = wp_get_post_terms(get_the_ID(), 'vehicle-year');

        $taxonomyData['vehicle-year'] = $vehicleYear[0]->name;

        $model = wp_get_post_terms(get_the_ID(), 'model');

        $taxonomyData['model'] = $model[0]->name;

        $class = wp_get_post_terms(get_the_ID(), 'class');

        $taxonomyData['class'] = $class[0]->name;

        var_dump($taxonomyData); die;

    }



    }

public function getAdCode($dimension){

        $prefix = $this->prefix;
        $sql = $this->db->prepare("SELECT * FROM {$prefix}wheels_ad_tag_mapping ");
        $post = $this->db->get_results($sql);

        if (empty($dimension)) {
            return false;
        }

        if (is_single()){
           $singleAdData = $this->setSingleAdCode();
        }else{
            $page = basename($_SERVER['REQUEST_URI']);
            $singleAdData = $this->setLandingPageAdCode($page , $post);
        }


        switch($dimension)
        {
            case '728x80':
               $adCode =  str_replace(array('%make%', '%model%' , '%year%', '%class%'),
                            array($singleAdData['make'],$singleAdData['model'], $singleAdData['vehicle-year'] , $singleAdData['class']),
                            $post[0]->ad_728_80);
                $data = $adCode;
                break;

            case '300x250':
                 $adCode =  str_replace(array('%make%', '%model%' , '%year%', '%class%'),
                            array($singleAdData['make'],$singleAdData['model'], $singleAdData['vehicle-year'] , $singleAdData['class']),
                            $post[0]->ad_728_80);
                $data = $adCode;
                break;

        }

        return $data;
    }

    private function  setSingleAdCode(){

        $make = wp_get_post_terms(get_the_ID(), 'make');

        $taxonomyData['make'] = $make[0]->name;

        $vehicleYear = wp_get_post_terms(get_the_ID(), 'vehicle-year');

        $taxonomyData['vehicle-year'] = $vehicleYear[0]->name;

        $model = wp_get_post_terms(get_the_ID(), 'model');

        $taxonomyData['model'] = $model[0]->name;

        $class = wp_get_post_terms(get_the_ID(), 'class');

        $taxonomyData['class'] = $class[0]->name;

        return $taxonomyData;

    }

    private function  setLandingPageAdCode($page , $adCodeData){


        $adCodeData = $adCodeData[0];
        switch($page)
        {
            case 'news':

                $data = array(
                    'make' => $adCodeData->news_make,
                    'model' => $adCodeData->news_model,
                    'vehicle-year' => $adCodeData->news_year,
                    'class' => $adCodeData->news_class,
                );
                break;

            case 'feature':
                $data = array(
                    'make' => $adCodeData->feature_make,
                    'model' => $adCodeData->feature_model,
                    'vehicle-year' => $adCodeData->feature_year,
                    'class' => $adCodeData->feature_class,
                );
                break;

            case 'guides':
                $data = array(
                    'make' => $adCodeData->guides_make,
                    'model' => $adCodeData->guides_model,
                    'vehicle-year' => $adCodeData->guides_year,
                    'class' => $adCodeData->guides_class,
                );
                break;
            case 'reviews':
               $data = array(
                    'make' => $adCodeData->review_make,
                    'model' => $adCodeData->review_model,
                    'vehicle-year' => $adCodeData->review_year,
                    'class' => $adCodeData->review_class,
                );
                break;

            default:
                $data = array(
                    'make' => $adCodeData->other_make,
                    'model' => $adCodeData->other_model,
                    'vehicle-year' => $adCodeData->other_year,
                    'class' => $adCodeData->other_class,
                );
                break;

        }

        return $data;

    }

}