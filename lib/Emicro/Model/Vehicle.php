<?php

namespace Emicro\Model;

include_once WP_CONTENT_DIR . '/bootstrap.php';
require_once WP_CONTENT_DIR . '/plugins/solr-for-wordpress/SolrPhpClient/Apache/Solr/Service.php';

class Vehicle extends Base
{
    public $autodata;

    public function __construct()
    {
        $solr = new \Apache_Solr_Service(SOLR_HOST, SOLR_PORT, SOLR_PATH);
        $autodata = new \Emicro\Model\Autodata($solr);
        $this->autodata = $autodata;
    }

    public function getShortProfile($vehicleId)
    {
        if (empty($vehicleId)) {
            return false;
        }

        return array(
            'title' => '2012 BMW 3 Series',
            'image' => '/img/cars/vehicle-84x47.jpg'
        );
    }

    /**
     * @param $acode
     * @return array|bool
     */
    public function getVehicleByAcode($acode)
    {
        $args = array(
            'acode' => $acode
        );
        //$args = array_merge($defaults, $options);
        //extract($args);
        return $this->autodata->searchVehicle($args, 0, 1, '', true);
    }

    /**
     * @param array $criteria
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param bool $includeAllFields
     * @return array|bool
     */
    public function getVehicles($criteria = array(), $start = 0, $limit = 10, $sort = '', $includeAllFields = false)
    {
        return $this->autodata->searchVehicle($criteria, $start, $limit, $sort, $includeAllFields);
    }

    /**
     * @param $year
     * @param $make
     * @param $mode
     * @param $trim
     * @return bool
     */
    public  function getVehicleByParam($year, $make, $mode, $trim)
    {
        $args = array(
            'year' => urldecode($year),
            'make' => urldecode($make),
            'model' => urldecode($mode),
            'trim' => urldecode($trim)
        );
        $data = $this->autodata->searchVehicle($args, 0, 1);
        if(empty($data['result'])) return false;
        return $data['result'][0]->acode;
    }

    /**
     * @param $value
     * @return string
     */
    public function vehicleFormatValue($value){

        if (is_bool($value)) {
            return ($value) ? 'Yes' : 'No';
        } else {
            $value = trim($value);
            return !empty($value) ? $value : 'None';
        }

    }

    /**
     * @param $vehicle
     * @return string
     */
    public function getHorsePowerFormattedValue($vehicle)
    {
        if($vehicle->horsepower_100_or_less)
        {
            $data = '100 or less';
        }elseif($vehicle->horsepower_101_to_150)
        {
            $data = '100 - 150';
        }elseif($vehicle->horsepower_151_to_200)
        {
            $data = '150 - 200';
        }elseif($vehicle->horsepower_200_or_more)
        {
            $data = '200 or more';
        }else
        {
            $data = 'Not defined';
        }
        return $data;
    }

    /**
     * @param $vehicle
     * @return string
     */
    public function getSeatingFormattedValue($vehicle)
    {
        if($vehicle->seating_2)
        {
            $data = '2';
        }elseif($vehicle->seating_4)
        {
            $data = '4';
        }elseif($vehicle->seating_5)
        {
            $data = '5';
        }elseif($vehicle->seating_6)
        {
            $data = '6';
        }elseif($vehicle->seating_78)
        {
            $data = '7 or 8';
        }elseif($vehicle->seating_9)
        {
            $data = '9 or more';
        }else
        {
            $data = 'Not defined';
        }
        return $data;
    }

    /**
     * @param $year
     * @param $make
     * @param $class
     * @param $model
     * @return bool
     */
    public function vehicleList($year, $make, $class, $model)
    {
        $vehicle = $year .'-'. $make .'-'. $class .'-'. $model;

        $data = array(
            '2012-bmw-suv-x6' =>
                array(
                    'acode' => 'USC10TOV111A0',
                    'year' => '2012',
                    'make' => 'bmw',
                    'class' => 'suv',
                    'model' => 'x6',
                    'related_vehicle' => array('2011-bmw-suv-m3', '2011-bmw-suv-x6m'),
                    'msrp' => '3000',
                    'rating' => array('star' => '4.5', 'consumer' => '3.0'),
                    'overview' => array('4-door, 5-passenger family sedan, or sports sedan', '2.0T GLI Autobahn w/ Nav', 'Available in 13 trims'),
                    'gallery' => array(
                            0 => array('title'=>'Image - 1', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                            1 => array('title'=>'Image - 2', 'image' => '/img/gallery/gallery-image-large-2.jpg', 'thumb' => '/img/gallery/gallery-image-small-2.jpg'),
                            2 => array('title'=>'Image - 3', 'image' => '/img/gallery/gallery-image-large-3.jpg', 'thumb' => '/img/gallery/gallery-image-small-3.jpg'),
                            3 => array('title'=>'Image - 4', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                            4 => array('title'=>'Image - 5', 'image' => '/img/gallery/gallery-image-large-2.jpg', 'thumb' => '/img/gallery/gallery-image-small-2.jpg'),
                            5 => array('title'=>'Image - 6', 'image' => '/img/gallery/gallery-image-large-3.jpg', 'thumb' => '/img/gallery/gallery-image-small-3.jpg'),
                            6 => array('title'=>'Image - 7', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                        ),
                    'specs' => array(
                        'consumer' =>
                            array(
                                'vehicle' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'engine' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'transmission' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'mileage' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'electrical' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'cooling-system' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                            ),
                        'technical' =>
                            array(
                                'vehicle' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'engine' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'transmission' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'mileage' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'electrical' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'cooling-system' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                            ),
                        'standard-equipment' =>
                            array(
                                'vehicle' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'engine' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'transmission' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'mileage' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'electrical' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                                'cooling-system' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                            ),
                        )
                ),

            '2011-bmw-suv-m3' =>
            array(
                'acode' => 'USC10TOV111B0',
                'year' => '2011',
                'make' => 'bmw',
                'class' => 'suv',
                'model' => 'm3',
                'related_vehicle' => array('2011-make2-class2-model2', '2011-make3-class3-model3', '2011-make4-class4-model4'),
                'msrp' => '2500',
                'rating' => array('star' => 2.5, 'consumer' => 3.5),
                'overview' => 'Overview of 2011-bmw-suv-m3',
                'gallery' => array(
                    0 => array('title'=>'Image - 1', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                    1 => array('title'=>'Image - 2', 'image' => '/img/gallery/gallery-image-large-2.jpg', 'thumb' => '/img/gallery/gallery-image-small-2.jpg'),
                    2 => array('title'=>'Image - 3', 'image' => '/img/gallery/gallery-image-large-3.jpg', 'thumb' => '/img/gallery/gallery-image-small-3.jpg'),
                    3 => array('title'=>'Image - 4', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                    4 => array('title'=>'Image - 5', 'image' => '/img/gallery/gallery-image-large-2.jpg', 'thumb' => '/img/gallery/gallery-image-small-2.jpg'),
                    5 => array('title'=>'Image - 6', 'image' => '/img/gallery/gallery-image-large-3.jpg', 'thumb' => '/img/gallery/gallery-image-small-3.jpg'),
                    6 => array('title'=>'Image - 7', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                ),
                'specs' => array(
                    'consumer' =>
                    array(
                        'vehicle' => ' Vehicle info of consumer',
                        'engine' => 'Engine info of consumer',
                        'transmission' => 'Transmission info of consumer',
                        'mileage' => 'Mileage info of consumer',
                        'electrical' => 'Electrical info of consumer',
                        'cooling-system' => 'Cooling system info of consumer'
                    ),
                    'technical' =>
                    array(
                        'vehicle' => ' Vehicle info of technical',
                        'engine' => 'Engine info of technical',
                        'transmission' => 'Transmission info of technical',
                        'mileage' => 'Mileage info of technical',
                        'electrical' => 'Electrical info of technical',
                        'cooling-system' => 'Cooling system info of technical'
                    ),
                    'standard-equipment' =>
                    array(
                        'vehicle' => ' Vehicle info of standdard-equipment',
                        'engine' => 'Engine info of standdard-equipment',
                        'transmission' => 'Transmission info of standdard-equipment',
                        'mileage' => 'Mileage info of standdard-equipment',
                        'electrical' => 'Electrical info of standdard-equipment',
                        'cooling-system' => 'Cooling system info of standdard-equipment'
                    ),
                )
            ),

            '2011-bmw-suv-x6m' =>
            array(
                'acode' => 'USC10TOV111C0',
                'year' => '2011',
                'make' => 'bmw',
                'class' => 'suv',
                'model' => 'x6m',
                'related_vehicle' => array('2011-make2-class2-model2', '2011-make3-class3-model3', '2011-make4-class4-model4'),
                'msrp' => '3000',
                'rating' => array('star' => 4.5, 'consumer' => 3),
                'overview' => 'Overview of 2011-bmw-suv-x6m',
                'gallery' => array(
                    0 => array('title'=>'Image - 1', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                    1 => array('title'=>'Image - 2', 'image' => '/img/gallery/gallery-image-large-2.jpg', 'thumb' => '/img/gallery/gallery-image-small-2.jpg'),
                    2 => array('title'=>'Image - 3', 'image' => '/img/gallery/gallery-image-large-3.jpg', 'thumb' => '/img/gallery/gallery-image-small-3.jpg'),
                    3 => array('title'=>'Image - 4', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                    4 => array('title'=>'Image - 5', 'image' => '/img/gallery/gallery-image-large-2.jpg', 'thumb' => '/img/gallery/gallery-image-small-2.jpg'),
                    5 => array('title'=>'Image - 6', 'image' => '/img/gallery/gallery-image-large-3.jpg', 'thumb' => '/img/gallery/gallery-image-small-3.jpg'),
                    6 => array('title'=>'Image - 7', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                ),
                'specs' => array(
                    'consumer' =>
                        array(
                            'vehicle' => ' Vehicle info of consumer',
                            'engine' => 'Engine info of consumer',
                            'transmission' => 'Transmission info of consumer',
                            'mileage' => 'Mileage info of consumer',
                            'electrical' => 'Electrical info of consumer',
                            'cooling-system' => 'Cooling system info of consumer'
                        ),
                    'technical' =>
                        array(
                            'vehicle' => ' Vehicle info of technical',
                            'engine' => 'Engine info of technical',
                            'transmission' => 'Transmission info of technical',
                            'mileage' => 'Mileage info of technical',
                            'electrical' => 'Electrical info of technical',
                            'cooling-system' => 'Cooling system info of technical'
                        ),
                    'standard-equipment' =>
                        array(
                            'vehicle' => ' Vehicle info of standdard-equipment',
                            'engine' => 'Engine info of standdard-equipment',
                            'transmission' => 'Transmission info of standdard-equipment',
                            'mileage' => 'Mileage info of standdard-equipment',
                            'electrical' => 'Electrical info of standdard-equipment',
                            'cooling-system' => 'Cooling system info of standdard-equipment'
                        ),
                )
            ),
        );

        if(array_key_exists($vehicle, $data))
        {
            return $data[$vehicle];
        }
        return false;
    }

    /**
     * @param $vechicleId
     * @return bool
     */
    public function getVehicleById($vechicleId)
    {
        $data = array(
            'USC10TOV111A0' =>
            array(
                'acode' => 'USC10TOV111A0',
                'year' => '2012',
                'make' => 'bmw',
                'class' => 'suv',
                'model' => 'x6',
                'related_vehicle' => array('2011-bmw-suv-m3', '2011-bmw-suv-x6m'),
                'msrp' => '3000',
                'rating' => array('star' => '4.5', 'consumer' => '3.0'),
                'overview' => array('4-door, 5-passenger family sedan, or sports sedan', '2.0T GLI Autobahn w/ Nav', 'Available in 13 trims'),
                'gallery' => array(
                    0 => array('title'=>'Image - 1', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                    1 => array('title'=>'Image - 2', 'image' => '/img/gallery/gallery-image-large-2.jpg', 'thumb' => '/img/gallery/gallery-image-small-2.jpg'),
                    2 => array('title'=>'Image - 3', 'image' => '/img/gallery/gallery-image-large-3.jpg', 'thumb' => '/img/gallery/gallery-image-small-3.jpg'),
                    3 => array('title'=>'Image - 4', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                    4 => array('title'=>'Image - 5', 'image' => '/img/gallery/gallery-image-large-2.jpg', 'thumb' => '/img/gallery/gallery-image-small-2.jpg'),
                    5 => array('title'=>'Image - 6', 'image' => '/img/gallery/gallery-image-large-3.jpg', 'thumb' => '/img/gallery/gallery-image-small-3.jpg'),
                    6 => array('title'=>'Image - 7', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                ),
                'specs' => array(
                    'consumer' =>
                    array(
                        'vehicle' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'engine' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'transmission' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'mileage' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'electrical' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'cooling-system' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                    ),
                    'technical' =>
                    array(
                        'vehicle' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'engine' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'transmission' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'mileage' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'electrical' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'cooling-system' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                    ),
                    'standard-equipment' =>
                    array(
                        'vehicle' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'engine' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'transmission' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'mileage' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'electrical' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                        'cooling-system' => array('EPA Classiﬁcation'=>'Compact Cars', 'CO2 Emissions @ 20 km' => '3634 kg/yr', 'Lorem ipsum dolar'=>'Sit amen 20km/L'),
                    ),
                )
            ),

            'USC10TOV111B0' =>
            array(
                'acode' => 'USC10TOV111B0',
                'year' => '2011',
                'make' => 'bmw',
                'class' => 'suv',
                'model' => 'm3',
                'related_vehicle' => array('2011-make2-class2-model2', '2011-make3-class3-model3', '2011-make4-class4-model4'),
                'msrp' => '2500',
                'rating' => array('star' => 2.5, 'consumer' => 3.5),
                'overview' => 'Overview of 2011-bmw-suv-m3',
                'gallery' => array(
                    0 => array('title'=>'Image - 1', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                    1 => array('title'=>'Image - 2', 'image' => '/img/gallery/gallery-image-large-2.jpg', 'thumb' => '/img/gallery/gallery-image-small-2.jpg'),
                    2 => array('title'=>'Image - 3', 'image' => '/img/gallery/gallery-image-large-3.jpg', 'thumb' => '/img/gallery/gallery-image-small-3.jpg'),
                    3 => array('title'=>'Image - 4', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                    4 => array('title'=>'Image - 5', 'image' => '/img/gallery/gallery-image-large-2.jpg', 'thumb' => '/img/gallery/gallery-image-small-2.jpg'),
                    5 => array('title'=>'Image - 6', 'image' => '/img/gallery/gallery-image-large-3.jpg', 'thumb' => '/img/gallery/gallery-image-small-3.jpg'),
                    6 => array('title'=>'Image - 7', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                ),
                'specs' => array(
                    'consumer' =>
                    array(
                        'vehicle' => ' Vehicle info of consumer',
                        'engine' => 'Engine info of consumer',
                        'transmission' => 'Transmission info of consumer',
                        'mileage' => 'Mileage info of consumer',
                        'electrical' => 'Electrical info of consumer',
                        'cooling-system' => 'Cooling system info of consumer'
                    ),
                    'technical' =>
                    array(
                        'vehicle' => ' Vehicle info of technical',
                        'engine' => 'Engine info of technical',
                        'transmission' => 'Transmission info of technical',
                        'mileage' => 'Mileage info of technical',
                        'electrical' => 'Electrical info of technical',
                        'cooling-system' => 'Cooling system info of technical'
                    ),
                    'standard-equipment' =>
                    array(
                        'vehicle' => ' Vehicle info of standdard-equipment',
                        'engine' => 'Engine info of standdard-equipment',
                        'transmission' => 'Transmission info of standdard-equipment',
                        'mileage' => 'Mileage info of standdard-equipment',
                        'electrical' => 'Electrical info of standdard-equipment',
                        'cooling-system' => 'Cooling system info of standdard-equipment'
                    ),
                )
            ),

            'USC10TOV111C0' =>
            array(
                'acode' => 'USC10TOV111C0',
                'year' => '2011',
                'make' => 'bmw',
                'class' => 'suv',
                'model' => 'x6m',
                'related_vehicle' => array('2011-make2-class2-model2', '2011-make3-class3-model3', '2011-make4-class4-model4'),
                'msrp' => '3000',
                'rating' => array('star' => 4.5, 'consumer' => 3),
                'overview' => 'Overview of 2011-bmw-suv-x6m',
                'gallery' => array(
                    0 => array('title'=>'Image - 1', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                    1 => array('title'=>'Image - 2', 'image' => '/img/gallery/gallery-image-large-2.jpg', 'thumb' => '/img/gallery/gallery-image-small-2.jpg'),
                    2 => array('title'=>'Image - 3', 'image' => '/img/gallery/gallery-image-large-3.jpg', 'thumb' => '/img/gallery/gallery-image-small-3.jpg'),
                    3 => array('title'=>'Image - 4', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                    4 => array('title'=>'Image - 5', 'image' => '/img/gallery/gallery-image-large-2.jpg', 'thumb' => '/img/gallery/gallery-image-small-2.jpg'),
                    5 => array('title'=>'Image - 6', 'image' => '/img/gallery/gallery-image-large-3.jpg', 'thumb' => '/img/gallery/gallery-image-small-3.jpg'),
                    6 => array('title'=>'Image - 7', 'image' => '/img/cars/vehicle-profile-main.jpg', 'thumb' => '/img/gallery/gallery-image-small-1.jpg'),
                ),
                'specs' => array(
                    'consumer' =>
                    array(
                        'vehicle' => ' Vehicle info of consumer',
                        'engine' => 'Engine info of consumer',
                        'transmission' => 'Transmission info of consumer',
                        'mileage' => 'Mileage info of consumer',
                        'electrical' => 'Electrical info of consumer',
                        'cooling-system' => 'Cooling system info of consumer'
                    ),
                    'technical' =>
                    array(
                        'vehicle' => ' Vehicle info of technical',
                        'engine' => 'Engine info of technical',
                        'transmission' => 'Transmission info of technical',
                        'mileage' => 'Mileage info of technical',
                        'electrical' => 'Electrical info of technical',
                        'cooling-system' => 'Cooling system info of technical'
                    ),
                    'standard-equipment' =>
                    array(
                        'vehicle' => ' Vehicle info of standdard-equipment',
                        'engine' => 'Engine info of standdard-equipment',
                        'transmission' => 'Transmission info of standdard-equipment',
                        'mileage' => 'Mileage info of standdard-equipment',
                        'electrical' => 'Electrical info of standdard-equipment',
                        'cooling-system' => 'Cooling system info of standdard-equipment'
                    ),
                )
            ),
        );

        if(array_key_exists($vechicleId, $data))
        {
            return $data[$vechicleId];
        }
        return false;
    }

    /**
     * @param $options
     * @return mixed
     */
    public function getAll($options)
    {
        $defaults = array(
            'vehicleId' => '', // acode
            'year' => '',
            'make' => '',
            'model' => '',
            'start' => 0,
            'limit' => 10,
            'count' => false,
            'except' => '',
            'type' => ''
        );

        $args = array_merge($defaults, $options);
        $prefix = $this->prefix;
        $vehicelTable = 'vn02_fullsv';

        if($args['type'] == 'popular')
        {
            $vehicle_ids = $this->getPopularVihicleIds($args['limit'], $args['start']);
        }

        $query = "SELECT DISTINCT * FROM " . $vehicelTable;

        if ($args['count'] == true) $query = "SELECT COUNT(SVUID) as num_rows FROM " . $vehicelTable;

        $query .= " WHERE 1=1";

        if (!empty($args['year'])){
            $query .= " AND {$vehicelTable}.YEAR = '" . $args['year'] . "'";
        }

        if (!empty($args['make'])){
            $query .= " AND {$vehicelTable}.MAKE = '" . $args['make'] . "'";
        }

        if (!empty($args['model'])){
            $query .= " AND {$vehicelTable}.MODEL = '" . $args['model'] . "'";
        }

        if (!empty($args['except'])){
            $query .= " AND {$vehicelTable}.Acode NOT IN (" . $args['except'] . ")";
        }

        // If vehicle filter by popular, no QUERY ORDER needed
        if(!empty($vehicle_ids))
        {
            $query .= " AND {$vehicelTable}.Acode IN (" . $vehicle_ids . ")";
        }
        else
        {
            $query .= " ORDER BY {$vehicelTable}.DateChanged DESC";
        }

        if ($args['count'] == false){
            $query .= " LIMIT " . $args['start'] . ", " . $args['limit'] . "";
        }
        $results = $this->db->get_results($this->db->prepare($query));

        return $results;
    }

    /*
     * Get popular vehicle ids depend on vehicle reviews
     */
    /**
     * @param $limit
     * @param $start
     * @return array|string
     */
    public function getPopularVihicleIds($limit, $start)
    {
        $prefix = $this->prefix;
        $query = "
            SELECT
                {$prefix}wheels_custom_data.vehicle_id_1
            FROM
              wp_posts
            INNER JOIN {$prefix}postmeta ON {$prefix}posts.ID = {$prefix}postmeta.post_id
            INNER JOIN {$prefix}wheels_custom_data ON {$prefix}postmeta.post_id = {$prefix}wheels_custom_data.post_id
            WHERE
                {$prefix}postmeta.meta_key = 'wheels_post_popularity' AND
                {$prefix}posts.post_status = 'publish' AND
                {$prefix}posts.post_type = 'vehicle-review' AND
                {$prefix}wheels_custom_data.vehicle_id_1 <> ''
            ORDER BY
              {$prefix}postmeta.meta_value DESC
            LIMIT {$start}, {$limit}";

        $results = $this->db->get_results($this->db->prepare($query));

        $review_ids = array();
        foreach($results as $row)
        {
            $review_ids[] = $row->vehicle_id_1;
        }
        $review_ids = "'".implode("','", $review_ids)."'";
        return $review_ids;
    }

    /*
     * This function return year,make,model,class,category etc vehicle elements depend on paremeter
     */
    /**
     * @param string $type
     * @return mixed
     */
    public function getVehicleElements($type='model')
    {
        $type = strtoupper($type);
        global $wpdb;
        $query = "SELECT DISTINCT vn02_fullsv.{$type} as value FROM vn02_fullsv";
        if($type == 'model'&& !empty($_GET['make']))
        {
            $query .= " WHERE make = '" .mysql_real_escape_string($_GET['make']). "' AND model ='" .mysql_real_escape_string($type). "'";
        }
        $results = $wpdb->get_results($query);
        return $results;
    }

    /*
    * This function return vehicle year list
   * @return mixed
     */
    /**
     * @return mixed
     */
    public function getYears()
    {
        global $wpdb;
        return $data = $wpdb->get_results("SELECT DISTINCT YearDesc, YearCode FROM wp_year ORDER BY YearDesc DESC");
    }

    /*
     * This function return vehicle model list
     */
    /**
     * @return mixed
     */
    public function getModels()
    {
        return $this->getVehicleElements('model');
    }

    /*
    * This function return vehicle vehicle make list

     * @return mixed
     */
    public function getMakes()
    {
        global $wpdb;
        $data = $wpdb->get_results("SELECT DivCode AS makeCode, DivDesc AS makeName FROM wp_make ORDER BY DivDesc ASC");
        return $data;
    }

    /*
    * This function return vehicle class list
     * @static
     * @return mixed
     */
    static public function getClasses()
    {
        global $wpdb;
        $class = $wpdb->get_results("SELECT * FROM wp_wheels_class ORDER BY wieght ASC");
        return $class;
        //return $this->getVehicleElements('model');
    }

    /*
    * This function return vehicle category list
    * @return array
     */
    public function getCategories()
    {
        $data = array(
            'Luxury',
            'Fuel Efficient',
            'First Car',
            'Family Friendly',
            'City Driving'
        );
        return $data;
        //return $this->getVehicleElements('year');
    }


    /**
     * @param $acodes array acode
     * @return array review's post id
     */
    public function getVehiclesPostIds($acodes)
    {
        global $wpdb;
        $prefix = $this->prefix;
        $acodes_string = "'" .implode("','", $acodes). "'";
        $query = "SELECT
              wp_posts.ID,
              wp_wheels_custom_data.vehicle_id_1 as vehicle_id
            FROM
              wp_posts
            INNER
              JOIN wp_wheels_custom_data ON wp_posts.ID = wp_wheels_custom_data.post_id
            WHERE
                wp_posts.post_type = 'reviews' AND
                wp_posts.post_status = 'publish' AND
                wp_wheels_custom_data.vehicle_id_1 <> '' AND
                wp_wheels_custom_data.vehicle_id_1 IN ($acodes_string)
            ";
        $results = $wpdb->get_results($query);
        $ids = array();

        foreach($acodes as $acode)
        {
            foreach($results as $row)
            {
                if($acode == $row->vehicle_id)
                {
                    $ids[] = $row->ID;
                }
            }
        }
        return $ids;
    }


    /**
     * @param array $data
     * @param $acode
     */
    public function updateVehicle($data = array(), $acode)
    {

        $this->autodata->updateVehicle($data, $acode);
    }


    /**
     * @param $acode
     * @return array
     */
    public function getTrimsByAcode($acode)
    {
        $this->autodata->addSearchField('style');
        $vehicles = $this->getVehicles( array('acode' => $acode));
        $styles = array();
        if($vehicles['result']){
            foreach($vehicles['result'] as $vehicle)
            {
                $styles[$vehicle->acode] = $vehicle->style;
            }
        }
        return $styles;
    }

    public function getVehicleProfileLink($profile)
    {
        if(empty($profile)) return '';
        //return '/vehicles/'.$profile->year .'-'. $profile->make .'-'. $profile->model .'-'. $profile->trim;
        return '/vehicles/'.$profile->acode.'/';
    }

    public function getVehicleProfileTitle($profile)
    {
        if(empty($profile)) return '';
        return $profile->year .' '. $profile->make .' '. $profile->model .' '. $profile->trim;
    }

    public function getVehicleImageLink($imageName, $width=101, $height='', $dimention = 'width')
    {
        if (empty($imageName))
        {
            return '/wp-content/themes/wheels/img/no-car-image.jpg';
        }else
        {
            if($dimention == 'height'){
                return 'http://imageonthefly.autodatadirect.com/images/?IMG=' .$imageName. '&HEIGHT='.$height;
            }elseif($dimention == 'width'){
                return 'http://imageonthefly.autodatadirect.com/images/?IMG=' .$imageName. '&WIDTH='.$width;
            }else{
                return 'http://imageonthefly.autodatadirect.com/images/?IMG=' .$imageName. '&WIDTH='.$width.'&HEIGHT='.$height;
            }
        }
    }

    public function prepareSpecialOfferItem($vehicle, $offerData)
    {
        $randDivId = rand(111111, 999999);
        $result = '';
        list($offer['effectivedate'],
            $offer['expiredate'],
            $offer['categorydesc'],
            $offer['description'],
            $offer['cash'],
            $offer['val24'],
            $offer['val36'],
            $offer['val48'],
            $offer['val60'],
            $offer['val72'],
            $offer['val84'],
            $offer['isnational'])  = explode('|', $offerData);
        //$result[] = $offer;

        $moreText = '';

        $cash = ( (int)$offer['cash'] > 0 ) ? '<strong>Cash Value :</strong> $'. (float)$offer['cash'] .'</br>' : '';
        $isnational = ( $offer['isnational'] == 1 ) ? 'Yes' : 'No';

        $interestRate = '';
        $interestRate .= ($offer['val24'] != '-99') ? '<strong>24 Month:</strong> '. (float)$offer['val24'].'%</br>' : '';
        $interestRate .= ($offer['val36'] != '-99') ? '<strong>36 Month:</strong> '. (float)$offer['val36'].'%</br>' : '';
        $interestRate .= ($offer['val48'] != '-99') ? '<strong>48 Month:</strong> '. (float)$offer['val48'].'%</br>' : '';
        $interestRate .= ($offer['val60'] != '-99') ? '<strong>60 Month:</strong> '. (float)$offer['val60'].'%</br>' : '';
        $interestRate .= ($offer['val72'] != '-99') ? '<strong>72 Month:</strong> '. (float)$offer['val72'].'%</br>' : '';
        $interestRate .= ($offer['val84'] != '-99') ? '<strong>84 Month:</strong> '. (float)$offer['val84'].'%</br>' : '';

        if($interestRate)
        {
            $interestRate = 'Interest Rate: <br><div class="special-offer-interest-rate-container">'.$interestRate.'</div>';
        }

        $moreText = '<div style="clear: both;"></div>
                     <a href="#'. $randDivId. '" class="special-offer-more-link">Read more</a>
                     <div id="'. $randDivId. '" class="special-offer-details">
                     <a href="#" class="special-offer-hide-details">Hide details</a>
                        <strong>Effective Date:</strong> ' . date("Y-m-d", strtotime($offer['effectivedate']) ) . '</br>
                        <strong>Expire Date:</strong> ' . date("Y-m-d", strtotime($offer['expiredate']) ) . '</br>
                        <strong>Price:</strong> $'. number_format($vehicle->price) .'<br>
                        '. $cash .'
                        '. $interestRate .'
                        <strong>Valid across the country:</strong> '. $isnational .'</br>
                     </div>';


        $result .= '<li>
                    <div class="pos">
                            <img src="'. $this->getVehicleImageLink($vehicle->images[0], 72) .'" alt="Offer '. ($index + 1) .'"/>
                            <h4>'. $vehicle->model .'</h4>
                            <p>'. $offer['description'] .'</p>
                            '. $moreText .'
                    </div>
                </li>';
        return $result;
    }

}