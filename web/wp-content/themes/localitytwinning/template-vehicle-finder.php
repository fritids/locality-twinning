<?php

global $wpdb, $adModel;

if($_GET['ad'] === 'true')
{
    echo 'sdfsdf sdfas dfsaf safasfas';
    exit;
}

$postModel = new \Emicro\Model\Post($wpdb);
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);

if( $_POST['find-trim'] == 'true' )
{
    if( empty($_POST['make']) && empty($_POST['model']) )
    {
        echo json_encode(array()); exit;
    }
    $vehicles = $vehicleModel->getVehicles(array( 'make' => $_POST['make'], 'model' => $_POST['model'] ));
    $trims = array();
    if($vehicles['result'])
    {
        foreach($vehicles['result'] as $vehicle)
        {
            $trims[] = $vehicle->trim;
        }
    }
    echo json_encode($trims);
    exit;
}
?>
<?php get_header('meta')?>
<body class="page vehiclefinder"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
<?php get_header()?>

    <div id="vehicle-finder" class="section-container clearfix">
        <h2 class="title">Vehicle Finder</h2>
        Find your perfect car with this research tool. Adjust the filters below to zero in on your next ride.
        <div class="row"><!-- begin .toolbar-->
            <div class="toolbar"><!-- begin .saved-searches-->

                <div class="saved-searches"><!-- begin .saved-searches-container-->
                    <!--
                    <div class="saved-searches-container">
                        <select data-role="none" name="saved-searches" data-controller="ComboboxController" data-readonly="true" class="saved-searches-selector ui-menu-saved-searches ui-dark">
                            <option>Saved Searches</option>
                            <option>2010 Chevy Trucks</option>
                            <option>Fast Coupes</option>
                            <option>2000-2004 Jettas</option>
                        </select>
                    </div>
                    -->
                    <!-- end .saved-searches-container-->
                </div>
                <!-- end .saved-searches-->

                <!-- begin .save-search-->
                <!--
                <div class="save-search">
                    <a href="#">
                        <span>&nbsp;</span>Save Seacrh
                    </a>
                </div>
                -->
                <!-- end .save-search-->

                <!-- begin .tip-->
                <div class="tip">
                    <strong>Tip:&nbsp;</strong>Click
                    <img src="<?php echo get_template_directory_uri() ?>/img/compare-icon-tip.png" alt="compare icon"/>to compare
                </div>
                <!-- end .tip-->

            </div>
            <!-- end .toolbar-->
        </div>

        <div class="row"><!-- begin .result-bar-->
            <div class="results-bar">

                <!-- begin .results-count-->
                <div class="results-count"> <span class="count">Loading...</span><span class="summary"></span> </div>
                <!-- end .results-count-->

                <!-- begin .sort-->
                <div class="sort"><!-- begin .sort-filter-container-->
                    <label for="sort-filter">Sort</label>
                    <div class="sort-filter-container">
                        <select id="sort-filter" data-role="none" name="sort-filter" data-controller="ComboboxController" data-readonly="true" class="sort-filter-selector ui-menu-sort-filter ui-dark">
                            <option value="year desc">Year - High to Low</option>
                            <option value="year asc">Year - Low to High</option>
                            <option value="price asc">Price - Low to High</option>
                            <option value="price desc">Price - High to Low</option>
<!--                            <option value="fuel_economy_highway asc">Kilometers - Low to High</option>-->
<!--                            <option value="fuel_economy_highway desc">Kilometers - High to Low</option>-->
                        </select>
                    </div>
                    <!-- end .sort-filter-container-->
                </div>
                <!-- end .sort-->

            </div>
            <!-- end .results-bar-->
        </div>


        <div class="row">
            <div data-controller="VehicleFinderFiltersController" class="filters">
                <form id="vehicle-filters" action="/" method="post"><!-- begin ul.acc-menu-->

                <ul data-controller="AccordionController" class="acc-menu">

                <!-- begin .make-->
                <li class="make clearfix"><!-- begin .section-head-->
                    <div class="section-head">
                        <h5><a href="#" class="heading">Make, Model &amp Trim</a></h5>
                    </div>
                    <!-- end .section-head-->

                    <!-- begin .collapsible-->
                    <div style="display:block;" class="collapsible clearfix">
                        <div class="pos">
                            <fieldset><!-- begin .make-container-->
                                <div class="make-container">
                                    <select id="finder-filter-make" data-role="none"
                                            name="make"
                                            data-controller="ComboboxController"
                                            class="filter-selector ui-menu-make ui-light">
                                        <option class="All" value="none">All Makes</option>
                                        <?php $make_select = (!empty($_POST['make']))?$_POST['make'] : DEFAULT_SPONSORED_MAKE; ?>
                                        <?php $makes = $vehicleModel->getMakes();
                                        foreach($makes as $row): ?>
                                        <option class="<?php echo $row->makeCode?>" <?php if(urldecode($make_select) == $row->makeName) echo ' selected="selected"'; ?> value="<?php echo $row->makeName?>"><?php echo $row->makeName?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <!-- end .make-container--><!-- begin .model-container-->
                                <div class="model-container">
                                    <select id="finder-filter-model" data-role="none"
                                            name="model"
                                            data-controller="ComboboxController"
                                            class="filter-selector ui-menu-model ui-light">
                                    <option value="none">All Models</option>
                                    </select>
                                </div>
                                <!-- end .model-container--><!-- begin .trim-container-->
                                <div class="trim-container">
                                    <select id="finder-filter-trim" data-role="none"
                                            name="trim"
                                            data-controller="ComboboxController"
                                            class="filter-selector ui-menu-trim ui-light">
                                    <option value="none">All Trims</option>
                                    </select>
                                </div>
                                <!-- end .trim-container-->
                            </fieldset>
                            <!-- end fieldset-->
                        </div>
                    </div>
                    <!-- end .collapsible-->
                </li>
                <!-- end .make-->

                <!-- begin .class-->
                <li class="class clearfix"><!-- begin .section-head-->
                    <div class="section-head">
                        <h5><a href="#" class="heading">Class</a></h5>
                    </div>
                    <!-- end .section-head-->

                    <!-- begin .collapsible-->
                    <div class="collapsible clearfix">
                        <div class="pos">
                            <fieldset>
                                <ol>
                                <?php $classes = $vehicleModel->getClasses(); ?>
                                <?php foreach($classes as $key => $class): ?>
                                    <li>
                                        <input
                                            data-role="none"
                                            type="checkbox"
                                            id="owned-875<?php echo $key ?>"
                                            name="class[]" value="<?php echo $class->name ?>"
                                            <?php if( urldecode($_POST['class']) == $class->name ) echo ' checked="checked"'?>/>
                                        <label for="owned-875<?php echo $key ?>"><?php echo $class->name ?></label>
                                    </li>
                                <?php endforeach;?>
                                </ol>
                            </fieldset>
                        </div>
                    </div>
                    <!-- end .collapsible-->
                </li>
                <!-- end .class-->


                <li class="make clearfix"><!-- begin .section-head-->
                    <div class="section-head">
                        <h5><a href="#" class="heading">Category</a></h5>
                    </div>
                    <!-- end .section-head-->

                    <!-- begin .collapsible-->
                    <div style="display:block;" class="collapsible clearfix">
                        <div class="pos">
                            <fieldset><!-- begin .make-container-->
                                <div class="make-container">
                                    <select id="filter-category" data-role="none"
                                            name="category"
                                            data-controller="ComboboxController"
                                            class="filter-selector ui-menu-make ui-light">

                                        <option>All</option>
                                        <?php $categories = $vehicleModel->getCategories(); ?>
                                        <?php foreach($categories as $category): ?>
                                        <option<?php if(urldecode($_POST['category'] == $category)) echo ' selected="selected"' ?>><?php echo $category?></option>
                                        <?php endforeach;?>

                                    </select>
                                </div>
                                <!-- end .make-container--><!-- begin .model-container-->
                            </fieldset>
                            <!-- end fieldset-->
                        </div>
                    </div>
                    <!-- end .collapsible-->
                </li>
                <!-- end .make-->


                <!-- begin .price-->
                <li class="price clearfix">

                    <!-- begin .section-head-->
                    <div class="section-head">
                        <h5><a href="#" class="heading">Price Range</a></h5>
                    </div>
                    <!-- end .section-head-->

                    <!-- begin .collapsible-->
                    <div class="collapsible clearfix">
                        <div class="pos">
                            <div class="min-value label">0</div>
                            <div class="max-value label">1000000</div>
                            <div id="price-slider" class="slider"></div>
                            <input type="hidden" name="price[start]" class="priceRange" value="20000">
                            <input type="hidden" name="price[end]" class="priceRange" value="400000">
                        </div>
                    </div>
                    <!-- end .collapsible-->
                </li>
                <!-- end .price-->


                <!-- begin .year-->
                <!-- end .year-->

                <!-- begin .fuel-->
                <li class="fuel clearfix">

                    <!-- begin .section-head-->
                    <div class="section-head">
                        <h5><a href="#" class="heading">Fuel Type</a></h5>
                    </div>
                    <!-- end .section-head-->

                    <!-- begin .collapsible-->
                    <div class="collapsible clearfix">
                        <div class="pos">
                            <fieldset>
                                <ol>
                                    <li>
                                        <input data-role="none" type="checkbox" id="fuel-721"  name="fuel_type[]" value="Gasoline" />
                                        <label for="fuel-721">Gas</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="fuel-997" name="fuel_type[]" value="Hybrid" />
                                        <label for="fuel-997">Hybrid</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="fuel-182" name="fuel_type[]" value="Ethanol" />
                                        <label for="fuel-182">Ethanol</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="fuel-196" name="fuel_type[]" value="Ethanol" />
                                        <label for="fuel-196">Diesel</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="fuel-23" name="fuel_type[]" value="Electric" />
                                        <label for="fuel-23">Electric</label>
                                    </li>
                                </ol>
                            </fieldset>
                        </div>
                    </div>
                    <!-- end .collapsible-->
                </li>
                <!-- end .fuel-->

                <!-- begin .efficiency-->
                <li class="efficiency clearfix">

                    <!-- begin .section-head-->
                    <div class="section-head">
                        <h5><a href="#" class="heading">Fuel Economy</a></h5>
                    </div>
                    <!-- end .section-head-->

                    <!-- begin .collapsible-->
                    <div class="collapsible clearfix">
                        <div class="pos">
                            <div class="min-value label">0</div>
                            <div id="efficiency-slider" class="slider"></div>
                            <div class="min-label">Any</div>
                            <div class="max-label">Max</div>
                            <input type="hidden" name="fuel_economy_highway[start]" class="efficiencyRange" value="5.0">
                            <input type="hidden" name="fuel_economy_highway[end]" class="efficiencyRange" value="50">
                        </div>
                    </div>
                    <!-- end .collapsible-->
                </li>
                <!-- end .efficiency-->

                <!-- begin .performance-->
                <li class="performance clearfix"><!-- begin .section-head-->

                    <div class="section-head">
                        <h5><a href="#" class="heading">Drive</a></h5>
                    </div>
                    <!-- end .section-head-->

                    <!-- begin .collapsible-->
                    <div class="collapsible clearfix">
                        <div class="pos">

                            <div class="row"><!-- begin .transmission-->

                                <div class="drive-train"><p>Drive Train</p>
                                    <fieldset><!-- begin .drive-train-container-->
                                        <div class="drive-train-container">
                                            <select id="filter-drive-train"
                                                    data-role="none"
                                                    name="drive_type"
                                                    data-controller="ComboboxController"
                                                    data-readonly="true"
                                                    class="filter-selector ui-menu-drive-train ui-dark">
                                                <option>All</option>
                                                <option>AWD</option>
                                                <option>4WD</option>
                                                <option>2WD</option>
                                            </select>
                                        </div>
                                        <!-- end .drive-train-container-->
                                    </fieldset>
                                </div>

                                <!-- begin .drive-train-->

                                <!-- end .drive-train-->
                            </div>

                        </div>
                    </div>
                    <!-- end .collapsible-->
                </li>
                <!-- end .performance-->

                <!-- begin .8)	Passenger Seating-->
                <li class="tech clearfix">

                    <!-- begin .section-head-->
                    <div class="section-head">
                        <h5>
                            <a href="#" class="heading">Passenger Seating</a>
                        </h5>
                    </div>
                    <!-- end .section-head-->

                    <!-- begin .collapsible-->
                    <div class="collapsible clearfix">
                        <div class="pos">
                            <fieldset>
                                <ol>
                                    <li>
                                        <input data-role="none" type="checkbox" id="tech-883" name="seating_2" value="true" />
                                        <label for="tech-883">2</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="tech-37" name="seating_4" value="true" />
                                        <label for="tech-37">4</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="tech-38" name="seating_5" value="true" />
                                        <label for="tech-38">5</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="tech-477" name="seating_6" value="true" />
                                        <label for="tech-477">6</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="tech-39" name="seating_78" value="true" />
                                        <label for="tech-39">7-8</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="tech-855" name="seating_9" value="true" />
                                        <label for="tech-855">9+</label>
                                    </li>
                                </ol>
                            </fieldset>
                        </div>
                    </div>
                    <!-- end .collapsible-->
                </li>
                <!-- end .technology-->

                <!-- begin .comfort-->
                <li class="comfort clearfix">

                    <!-- begin .section-head-->
                    <div class="section-head">
                        <h5><a href="#" class="heading">Comfort</a></h5>
                    </div>
                    <!-- end .section-head-->

                    <!-- begin .collapsible-->
                    <div class="collapsible clearfix">
                        <div class="pos">
                            <fieldset>
                                <ol>
                                    <li>
                                        <input data-role="none" type="checkbox" id="comfort-869" name="comfort_sunroof" value="true" />
                                        <label for="comfort-869">Sunroof</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="comfort-97" name="comfort_leather_seats" value="true" />
                                        <label for="comfort-97">Leather Seats</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="comfort-996" name="comfort_heated_seats" value="true" />
                                        <label for="comfort-996">Heated Seats</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="comfort-542" name="comfort_power_seats" value="true" />
                                        <label for="comfort-542">Power Seats</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="comfort-543" name="comfort_keyless_entry" value="true" />
                                        <label for="comfort-543">Keyless Entry</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="comfort-544" name="comfort_cruise" value="true" />
                                        <label for="comfort-544">Cruise</label>
                                    </li>
                                    <li>
                                        <input data-role="none" type="checkbox" id="comfort-545" name="comfort_power_adjustable_seats" value="true" />
                                        <label for="comfort-545">Power Adjustable Seats</label>
                                    </li>

                                </ol>
                            </fieldset>
                        </div>
                    </div>
                    <!-- end .collapsible--></li>
                <!-- end .comfort-->

                <!-- begin .safety-->
                <li class="safety clearfix">

                    <!-- begin .section-head-->
                    <div class="section-head">
                        <h5><a href="#" class="heading">Safety</a>
                        </h5>
                    </div>
                    <!-- end .section-head-->

                    <!-- begin .collapsible-->
                    <div class="collapsible clearfix">
                        <div class="pos">
                            <fieldset>
                                <ol>
                                    <li>
                                        <input data-role="none" type="checkbox" id="safety-8350" name="true" value="safety_anti_lock_brakes" />
                                        <label for="safety-8350">Ant-Lock Brakes</label>
                                    </li>

                                    <li>
                                        <input data-role="none" type="checkbox" id="safety-3580" name="safety_stability_control" value="true" />
                                        <label for="safety-3580">Stability Control</label>
                                    </li>

                                    <li>
                                        <input data-role="none" type="checkbox" id="safety-50" name="safety_traction_control" value="true" />
                                        <label for="safety-50">Traction Control</label>
                                    </li>

                                    <li>
                                        <input data-role="none" type="checkbox" id="safety-4029" name="comfort_rain_sensing_wipers" value="true" />
                                        <label for="safety-4029">Rain Sensing Wipers</label>
                                    </li>


                                    <li>
                                        <input data-role="none" type="checkbox" id="safety-7941" name="safety_child_sensor" value="true" />
                                        <label for="safety-7941">Child Sensor</label></li>

                                    <li>
                                        <input data-role="none" type="checkbox" id="safety-4406" name="safety_park_distance_control" value="fog lights" />
                                        <label for="safety-4406">Park Distance Control</label>
                                    </li>

                                </ol>
                            </fieldset>
                        </div>
                    </div>
                    <!-- end .collapsible-->
                </li>
                <!-- end .safety-->
                </ul>
                <input type="hidden" name="year[start]" class="yearRange" value="1999">
                <input type="hidden" name="year[end]" class="yearRange" value="<?php echo date('Y') + 1 ?>">
                <input type="hidden" name="orderby" id="orderby" value="price asc" />
                <input type="hidden" name="start" id="start" value="0" />
                <input type="hidden" name="limit" id="limit" value="10" />

                <!-- end ul.acc-menu-->
                </form>
                <!-- end form#vehicle-filters-->

                <div class="mrec-ad">
                    <?php echo $adModel->getAd('300x250'); ?>
                </div>

                <div></div>

                <div class="mrec-ad">
                    <?php echo $adModel->getAd('300x250'); ?>
                </div>

            </div>

            <!-- begin .results - SPONSORED VEHICLE-->
            <div data-controller="VehicleFinderResultsController" class="results">

                <div class="search-result">
                </div>

            </div>
        <!-- end .results-->
        </div>


        <div class="row"><!-- begin .ads-->
            <div class="ads">

                <!--
                <div class="mrec-ad">
                    <?php /*echo $adModel->getAd('300x250');*/?>
                </div>
                -->

            </div>
            <!-- end .ads--><!-- begin .results-->
            <div data-controller="VehicleFinderResultsController" class="results"><!-- begin .result-wrap.new-->

            </div>
            <!-- end .results-->
        </div>
    </div>
    <!-- begin .leaderboard-->

<script type="text/javascript">
    VEHICLE_FINDER_LOADED = true;
    <?php if(!empty($_POST['make'])) { ?>

    var MAKE_SELECTED = '<?php echo $_POST['make'] ?>';

    <?php } ?>

    <?php if(!empty($_POST['model'])) { ?>

    var MODEL_SELECTED = '<?php echo $_POST['model'] ?>';

    <?php } ?>

    <?php if(!empty($_POST['price']['end'])) { ?>

    var PRICE_END = '<?php echo $_POST['price']['end'] ?>';

    <?php } ?>

</script>

    <?php get_footer()?>
</body>
</html>