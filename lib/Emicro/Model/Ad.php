<?php

namespace Emicro\Model;

include_once WP_CONTENT_DIR . '/bootstrap.php';

class Ad extends Base
{
    protected $ads = array();
    protected $originals = array();

    public function loadAll()
    {
        return '';
        $ads = array();
        $replacers = array();

        foreach ($this->originals as $key => $originalAd) {
            $ads[] = "<div id='ad-{$key}' style='display: none;'>$originalAd</div>";
            $replacers[] = "'{$key}'";
        }

        $ads = implode(PHP_EOL, $ads);
        $replacers = implode(',', $replacers);

        $html = <<<HTML
<script type="text/javascript">(function(ids){ids.forEach(function(id){var children=document.getElementById('ad-'+id).childNodes;var ad;for(var i=2;i<children.length;i++){if(children[i].tagName=='A'||children[i].tagName=='DIV'||children[i].tagName=='OBJECT'){ad=children[i];document.getElementById('holder-'+id).appendChild(ad);return}}})})([{$replacers}]);</script>
HTML;

        if (OPTIMIZE_ADS_LOADING) {
            return $ads . $html;
        } else {
            return '';
        }
    }

    public function getAd($dimension, $pageId = false, $placementPosition = false)
    {
        if(DISABLE_ALL_AD)
        {
            return '';
        }
        if (empty($dimension)) {
            return false;
        }

        if (array_key_exists($dimension, $this->ads)) {
            $this->ads[$dimension][count($this->ads[$dimension]) + 1] = $dimension;
        } else {
            $this->ads[$dimension][1] = $dimension;
        }

        list($width, $height) = explode('x', $dimension);
        $placementPosition = ($placementPosition) ? $placementPosition : count($this->ads[$dimension]);

        $placementInfo = $this->getPaged($dimension, $placementPosition);

        $pageId = ($pageId) ? $pageId : $placementInfo['pageId'];
        $table = $this->prefix . WHEELS_AD_TAG_TABLE;
        $sql   = $this->db->prepare("SELECT * FROM {$table} WHERE page_id = '{$pageId}' AND placement_position = '{$placementPosition}' AND width = '{$width}' AND height = '{$height}'");
        $adTag = $this->db->get_row($sql);

        if (!$adTag) {
            return '<!-- No ad found -->';
        }

        $tagMap = $this->getAdMap();

        if(!empty($placementInfo['placementName']))
        {
            $adTag->placement_name = $placementInfo['placementName'];
        }

        if(!empty($_POST['make']))
        {
            $adTag->make = $_POST['make'];
        }

        if(!empty($tagMap))
        {
            $adTag->make = $tagMap['make'];
            $adTag->model = $tagMap['model'];
            $adTag->year = $tagMap['year'];
            $adTag->type = $tagMap['class'];
        }

        $js = $this->getAdRawJs();
        $holder = $this->getAdHolder($adTag->placement_id, $adTag->height, $adTag->width, $adTag->id);
        $hash = md5($adTag->placement_id . $adTag->height . $adTag->width . $adTag->id);

        $adCode = str_replace(
            array(
                '%placement_name%',
                '%placement_id%',
                '%width%',
                '%height%',
                '%make%',
                '%model%',
                '%year%',
                '%type%'
            ),
            array(
                $adTag->placement_name,
                $adTag->placement_id,
                $adTag->width,
                $adTag->height,
                $adTag->make,
                $adTag->model,
                $adTag->year,
                $adTag->type
            ),
            $js
        );

        $this->originals[$hash] = stripslashes($adCode);

        if (OPTIMIZE_ADS_LOADING) {

            $param = http_build_query($adTag);
            $hash = md5($adTag->id);
            $iframe = '<iframe id='.$hash.' onLoad="autoResize(\''.$hash.'\')" width="'.$adTag->width.'" height="'.$adTag->height.'" src="/ad/ad.php?'.$param.'" scrolling="no" frameborder="0"></iframe>';

            return $iframe;
        } else {

            $url = explode('/', $_SERVER['REQUEST_URI']);
            if( isset($url[1]) && $url[1] == 'vehicle-finder')
            {
                $param = http_build_query($adTag);
                $hash = md5($adTag->id);
                $iframe = '<iframe id='.$hash.' onLoad="autoResize(\''.$hash.'\')" width="'.$adTag->width.'" height="'.$adTag->height.'" src="/ad/vehicle-finder.php?'.$param.'" scrolling="no" frameborder="0"></iframe>';

                return $iframe;
            }else{
                return stripslashes($adCode);
            }

        }

    }

    private function getAdHolder($placementId, $height, $width, $adtagId)
    {
        $hash = md5($placementId . $height . $width . $adtagId);
        $out = <<<HTML
<div id="holder-{$hash}" style="height:{$height}px; width: {$width}px; overflow: hidden;"></div>
HTML;

        return $out;
    }

    private function getAdRawJs()
    {
        $data = <<<HTML
<script type="text/javascript">var curDateTime=new Date();var offset=-(curDateTime.getTimezoneOffset());if(offset>0)offset="+"+offset;if(window.adgroupid==undefined){window.adgroupid=Math.round(Math.random()*1000)}document.write('<scr\'+\'ipt language=\"javascript1.1\" src=\"http://adserver.adtechus.com/addyn/3.0/5214.1/%placement_id%/0/-1/ADTECH;loc=100;alias=%placement_name%;size=%width%x%height%;target=_blank;kvmake=%make%;kvmodel=%model%;kvyear=%year%;kvtype=%type%;key=%make%_%model%;cookie=info;grp='+window.adgroupid+';misc='+new Date().getTime()+';aduho='+offset+';rdclick=\"></scri'+'pt>');</script>
<noscript><a href="http://adserver.adtechus.com/adlink/3.0/5214.1/%placement_id%/0/-1/ADTECH;alias=%placement_name%;size=%width%x%height%;kvmake=%make%;kvmodel=%model%;kvyear=%year%;kvtype=%type%;key=%make%_%model%;loc=300;rdclick=" target="_blank"></a></noscript>
HTML;

        return $data;
    }

    private function getPaged($dimension = '', $placementPosition = 1)
    {
        if( !function_exists('is_single') ) return '537605';

        $post_type = get_query_var('post_type');
        $taxonomy  = get_query_var('taxonomy');
        $term      = get_query_var('term');

        $placenameName = '';

        list($empty, $page_name, $vehicle_id) = explode('/', $_SERVER['REQUEST_URI']);

        if (is_home()) {
            $pageId = '194157';

        } elseif (is_single()) {

            global $post;

            switch ($post_type) {
                case 'news':
                    $pageId = '198742';
                    $placenameName = $this->getPlacementName('news', '', $post->ID, '', $dimension, $placementPosition);
                    break;
                case 'feature':
                    $pageId = '198742';
                    $placenameName = $this->getPlacementName('feature', '', $post->ID, '', $dimension, $placementPosition);
                    break;
                case 'guides':
                    $pageId = '66667';
                    $placenameName = $this->getPlacementName('guides', '', $post->ID, '', $dimension, $placementPosition);
                    break;
                case 'reviews':
                    $pageId = '198034';
                    break;
                case 'events': // Set random page id
                    $pageId = '198740';
                    $placenameName = $this->getPlacementName('events', '', $post->ID, '', $dimension, $placementPosition);
                    break;
            }
        } elseif (is_post_type_archive()) {
            switch ($post_type) {
                case 'news':
                    $pageId = '198035';
                    $placenameName = $this->getPlacementName('news', 'hub', 0, '', $dimension, $placementPosition);
                    break;
                case 'feature':
                    $pageId = '537606';
                    $placenameName = $this->getPlacementName('feature', 'hub', 0, '', $dimension, $placementPosition);
                    break;
                case 'guides':
                    $pageId = '66666';
                    $placenameName = $this->getPlacementName('guides', 'hub', 0, '', $dimension, $placementPosition);
                    break;
                case 'reviews':
                    $pageId = '198034';
                    break;
                case 'vehicles-reviews':
                    $pageId = '198037';
                    break;
                case 'compare':
                    $pageId = '66668';
                    break;
                case 'mywheels':
                    $pageId = '66670';
                    break;
                case 'events': // Set random page id
                    $pageId = '198747';
                    $placenameName = $this->getPlacementName('events', 'hub', 0, '', $dimension, $placementPosition);
                    break;
            }
        } elseif ($page_name == 'vehicles') {
            if (empty($vehicle_id)) // landing
            {
                $pageId = '198137';
            } else {
                $pageId = '198237';
            }
        } elseif ($taxonomy == 'news-category') {

            switch ($term) {
                case 'industry-news':
                    $pageId = '198649';// Missiing actual page ID
                    break;
                default:
                    $pageId = '198641';//
            }

            $placenameName = $this->getPlacementName('news', 'hub', 0, $term, $dimension, $placementPosition);

        } elseif ($taxonomy == 'feature-category') {
            switch ($term) {
                case 'columns-advice':
                    $pageId = '198641';
                    break;
                case 'green-wheels':
                    $pageId = '198439';
                    break;
                case 'motorsports':
                    $pageId = '198338';
                    break;
                case 'wheels-smackdown':
                    $pageId = '66672';
                    break;
                case 'news-information':
                    $pageId = '198136';
                    break;
                case 'cool-cars-tech':
                    $pageId = '198133';
                    break;
                default:
                    $pageId = '198641';
            }

            $placenameName = $this->getPlacementName('feature', 'hub', 0, $term, $dimension, $placementPosition);

        } elseif ($taxonomy == 'guides-category') {

            $placenameName = $this->getPlacementName('guides', 'hub', 0, $term, $dimension, $placementPosition);

        } elseif ($page_name == 'vehicle-finder') {
            $pageId = '66669';
        } elseif ($page_name == 'mywheels') {
            $pageId = '66670';
        } elseif ($page_name == 'search') {
            $pageId = '66671';
        } else {
            $pageId = '194157';
        }

        // if does not match anything, set home page id
        if(empty($pageId)) $pageId = '194157';
        return array('pageId' => $pageId, 'placementName' => $placenameName);
    }

    private function getAdMap()
    {
        global $adTagMap;
        if(!empty($adTagMap))
        {
            return $adTagMap;
        }
        if (is_single()){
            $make = wp_get_post_terms(get_the_ID(), 'make');
            $taxonomyData['make'] = $make[0]->name;

            $vehicleYear = wp_get_post_terms(get_the_ID(), 'vehicle-year');
            $taxonomyData['year'] = $vehicleYear[0]->name;

            $model = wp_get_post_terms(get_the_ID(), 'model');
            $taxonomyData['model'] = $model[0]->name;

            $class = wp_get_post_terms(get_the_ID(), 'class');
            $taxonomyData['class'] = $class[0]->name;

            return $taxonomyData;
        }
        return false;
    }

    private function setLandingPageAdCode($page, $adCodeData)
    {
        $adCodeData = $adCodeData[0];

        switch ($page) {

            case 'news':

                $data = array(
                    'make'         => $adCodeData->news_make,
                    'model'        => $adCodeData->news_model,
                    'vehicle-year' => $adCodeData->news_year,
                    'class'        => $adCodeData->news_class,
                );
                break;

            case 'feature':
                $data = array(
                    'make'         => $adCodeData->feature_make,
                    'model'        => $adCodeData->feature_model,
                    'vehicle-year' => $adCodeData->feature_year,
                    'class'        => $adCodeData->feature_class,
                );
                break;

            case 'guides':
                $data = array(
                    'make'         => $adCodeData->guides_make,
                    'model'        => $adCodeData->guides_model,
                    'vehicle-year' => $adCodeData->guides_year,
                    'class'        => $adCodeData->guides_class,
                );
                break;
            case 'reviews':
                $data = array(
                    'make'         => $adCodeData->review_make,
                    'model'        => $adCodeData->review_model,
                    'vehicle-year' => $adCodeData->review_year,
                    'class'        => $adCodeData->review_class,
                );
                break;

            default:
                $data = array(
                    'make'         => $adCodeData->other_make,
                    'model'        => $adCodeData->other_model,
                    'vehicle-year' => $adCodeData->other_year,
                    'class'        => $adCodeData->other_class,
                );
                break;

        }

        return $data;

    }

    public function getPlacementName($postType = 'news', $placementType = 'hub', $postId = 0, $term = '', $dimension = '', $placementPosition = 1){

        $prefix = 'wheels';
        $pageName = '';
        if(!empty($term)) $term = '_' . $this->text_cleanup($term);
        $hub = ($placementType == 'hub') ? "_hub" : '';

        if($postType == 'news')
        {
            $pageName = 'newsandfeatures';
            $taxonomy = 'news-category';

        }elseif($postType == 'feature')
        {
            $pageName = 'newsandfeatures';
            $taxonomy = 'feature-category';

        }elseif($postType == 'guides')
        {
            $pageName = 'guides';
            $taxonomy = 'guides-category';

        }elseif($postType == 'events')
        {
            $pageName = 'events';
            $taxonomy = 'events-category';
        }

        if($postId)
        {
            $terms = wp_get_post_terms($postId, $taxonomy);
            if( !is_wp_error($terms) && !empty($terms) )
            {
                $term = '_' . $this->text_cleanup($terms[0]->slug);
            }
        }


        return $prefix . '_' . $pageName . $term . $hub . '_' . $dimension . '_' . $placementPosition;
    }

    public function text_cleanup($str)
    {
        if(empty($str)) return '';
        return preg_replace("/[^a-zA-Z0-9]/", "", str_replace(' ', '', $str));
    }
}