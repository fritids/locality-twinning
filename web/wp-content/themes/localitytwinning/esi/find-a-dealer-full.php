<?php
require '../../../../wp-load.php';

$postModel = new \Emicro\Model\Post($wpdb);
$vehicleModel = new \Emicro\Model\Vehicle();
$makes = $vehicleModel->getMakes();
?>

<!-- begin .find-dealer-->
<div data-controller="FindADealerController" class="find-dealer" id="find-a-dealer-full"><h3>Locate A New Vehicle Dealer</h3>

    <div class="form-container">
        <form id="dealer-search-form" action="" method="post">
            <fieldset>
                <ol>
                    <li><label for="dealer-make" id="lbl-dealer-make">Make</label><!-- begin .make-container-->
                        <div class="make-container">

                            <select data-role="none" name="dealer-make" id="dealer-make"
                                                            data-controller="ComboboxController" data-readonly="true"
                                                            class="compare-selector find-dealer-selector ui-dark">
                            <option value="0">Make</option>

                            <?php if(!empty($makes)): ?>
                                <?php echo $dealer_make = urldecode((isset($_REQUEST['dealer-make']))?$_REQUEST['dealer-make']:''); ?>
                                <?php foreach($makes as $make): ?>
                                    <option value="<?php echo $make->makeCode; ?>" <?php echo ($dealer_make==$make->makeName)?'selected="selected"':''; ?>><?php echo $make->makeName; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select></div>
                        <!-- end .make-container-->
                    </li>
                    <?php $dealer_zip = urldecode((isset($_REQUEST['dealer-zip']))?$_REQUEST['dealer-zip']:''); ?>
                    <li><label for="search-location" id="lbl-search-location">City, Postal Code</label><input data-role="none" type="text"
                                                                                     id="search-location"
                                                                                     name="dealer-location"
                                                                                     class="global-inner-shadow full"
                                                                                     value="<?php echo $dealer_zip; ?>"/>
                        <input type="hidden" value="0" name="hdn_lat" id="hdn_lat" />
                        <input type="hidden" value="0" name="hdn_lng" id="hdn_lng" />
                    </li>
                </ol>
            </fieldset>
        </form>
    </div>
    <div id="map_canvas" style="width: 680px;height: 360px"><img src="/wp-content/themes/wheels/img/ajax-loader.gif" class="ajax-loader ajax-loader1" alt="Loading" /></div>
<div id="listing"><table id="resultsTable"><tbody id="results"></tbody></table></div>
</div><!-- end .find-dealer-->