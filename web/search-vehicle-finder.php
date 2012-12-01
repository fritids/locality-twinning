<?php
error_reporting(0);
require_once 'wp-content/bootstrap.php';
require_once WP_CONTENT_DIR . '/plugins/solr-for-wordpress/SolrPhpClient/Apache/Solr/Service.php';

$solr = new Apache_Solr_Service(SOLR_HOST, SOLR_PORT, SOLR_PATH);

$autodata = new \Emicro\Model\Autodata($solr);

$option = array();
foreach($_POST as $key => $value){
    if(is_array($key)){
        $subOption = array();
        foreach($key as $subKey => $subValue)
        {
            $subOption[$subKey] = $subValue;
        }
        $option[$key] = $subOption;
    }else{
        $option[$key] = $value;
    }
}

unset($option['orderby']);
unset($option['start']);
unset($option['limit']);
//unset($option['fuel_economy_highway']);


if(empty($option['make']) || $option['make'] == 'All' || $option['make'] == 'none') unset($option['make']);
if(empty($option['model']) || $option['model'] == 'All' || $option['model'] == 'none') unset($option['model']);
if(empty($option['trim']) || $option['trim'] == 'All' || $option['trim'] == 'none') unset($option['trim']);
if(empty($option['drive_type']) || $option['drive_type'] == 'All') unset($option['drive_type']);
if(empty($option['category']) || $option['category'] == 'All') unset($option['category']);

$option['primary'] = true;
$autodata->addSearchField('model_id');
$search = $autodata->searchVehicle($option, $_REQUEST['start'], $_REQUEST['limit'], $_REQUEST['orderby']);

$result = '';

if( (int)$_REQUEST['start'] == 0 ){

    //if( in_array($_POST['make'], array(DEFAULT_SPONSORED_MAKE, 'none', 'make')) )
    //{
        // Sponsored
        // If found user class by class (Header Vehcle Finder), alter class parameter
        $spondoredVehicle['result'] = '';
        if(!empty($_POST['class'][0]))
        {
            $spondoredVehicleSearchParam = array( 'make' => DEFAULT_SPONSORED_MAKE, 'class' => $_POST['class'][0] );
            $spondoredVehicle = $autodata->searchVehicle($spondoredVehicleSearchParam, 0, 1, 'year desc');
        }

        // If no sponsored vehicel found,
        if(empty($spondoredVehicle['result']))
        {
            $spondoredVehicleSearchParam = array( 'make' => DEFAULT_SPONSORED_MAKE, 'model' => DEFAULT_SPONSORED_MODEL, 'year' => array('start' => date("Y") - 1, 'end' => date("Y") + 1) );
            $spondoredVehicle = $autodata->searchVehicle($spondoredVehicleSearchParam, 0, 1 , 'year desc');
        }
        if(!empty($spondoredVehicle['result'])){
            $spondoredVehicle['result'][0]->sponsor = true;

            $search['result'] = array_merge($spondoredVehicle['result'], $search['result']);
        }

    //}
}

foreach($search['result'] as $vehicle)
{
    $data = array();
    $new = ( (int)$vehicle->year >= 2011 ) ? true : false;
    $sponsor = (isset($vehicle->sponsor)) ? true : false; //$sponsor = true;
    $title = $vehicle->year . ' ' . $vehicle->make . ' '. $vehicle->model . ' '. $vehicle->trim;
    $rating = $vehicle->popularity;
    $image = 'http://imageonthefly.autodatadirect.com/images/?IMG=' .$vehicle->images[0]. '&WIDTH=216';
    $price = $vehicle->price;
    $link = '/vehicles/'.$vehicle->acode;
    $fuel = $vehicle->fuel_economy_highway;
    $rating = $vehicle->start_rating;

    $new_class = ($new) ? ' new' : '';
    $new_markup = ($new) ? '<span class="new">New</span>' : '';

    $sponsor_class = ($sponsor) ? ' sponsored' : '';
    $sponsor_markup = ($sponsor) ? '<span class="sponsored">Sponsored</span>' : '';

    $autodata->addSearchField('style');
    $relatedTrims = $autodata->searchVehicle( array('model_id' => $vehicle->model_id));

    $trims = '';
    if($relatedTrims['result']){
        $trims .= '
            <div class="row trims">
                <div class="result-trim-container">
                    <select data-role="none" name="trim-selector" data-controller="ComboboxController" data-readonly="true" class="trim-selector ui-menu-trim-result ui-minimal">
                        <option>Available Trims</option>';
        foreach($relatedTrims['result'] as $trim)
        {
            if($trim->acode != $vehicle->acode){
                 $trims .= '<option value="' . $trim->acode . '">' .$trim->trim. '</option>';
            }
        }
        $trims .= '</select>
                </div>
            </div>';
    }else{
        $trims .= $vehicle->model_id;
    }

    $data = '<div class="result-wrap' . $new_class . $sponsor_class . '">
                <div class="result-item">
                    <div class="img-col">
                        <a href="' .$link. '">
                            <img src="' .$image. '" alt="' .$title. '">
                        </a>
                        <a class="compare callout" href="#" data-id="" rel="' .$vehicle->acode. '">Compare
                            <img src="/wp-content/themes/wheels/img/compare-callout.png" alt="Compare this vehicle">
                        </a>
                        ' . $new_markup . $sponsor_markup . '
                    </div>
                    <div class="data-col">
                        <div class="pos">
                            <div class="row title"><h5>
                            <a href="' .$link. '">
                            ' .$title. '</a>
                            </h5>

                                <p class="price">$' . number_format($price). '
                                    <span>MSRP</span>
                                </p>
                            </div>
                            ' .$trims. '
                            <div class="row">
                                <div class="fuel">
                                    <div class="label">Fuel</div>
                                    <div class="value">' . $fuel. 'L
                                        <span>/100KM</span></div>
                                </div>
                                <!--
                                <div class="rating white">
                                    <div class="label">Our Rating</div>
                                    <div class="value rating-' .str_replace('.','-',$rating). '">' .$rating. '</div>
                                </div>
                                -->
                            </div>
                        </div>
                    </div>
                    <!--
                    <a class="add-to-my-wheels callout" href="#" rel="' .$vehicle->acode. '">
                        Add To My Wheels
                        <img src="/wp-content/themes/wheels/img/callout-add-to-wheels.png" alt="Add To My Wheels">
                    </a>
                    -->
                    </div>
                </div>';

    $result .= $data;
}

if(empty($result))
{
    echo json_encode(array('total'=> 0, 'result' => ''));
    exit;
}else{
    echo json_encode(array('total'=> $search['total'], 'result' => $result));
    exit;
}