<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/wp-content/bootstrap.php");
global $wpdb;
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);
$makes = $vehicleModel->getMakes();

?>
<div class="special-offers">
    <h3>Special Offers</h3>
    <!-- begin .make-container-->
    <div class="make-container">
        <select data-role="none" id="special-offer-make" name="make-selector" data-controller="ComboboxController" data-readonly="true" class="compare-selector ui-dark">
            <?php foreach($makes as $make): ?>
            <option<?php if($make->makeName == DEFAULT_SPONSORED_MAKE) echo ' selected="selected"' ?>><?php echo $make->makeName ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <!-- end .make-container-->
    <div class="row">
        <div class="offers">
            <ul>

            </ul>
        </div>
        <a href="#" id="more-special-offer">More Models</a>
        </br></br>
    </div>
</div>

<!-- begin #alert.modal-->
<div id="modal-screens3" data-controller="ModalController">
    <div id="special-offer-lightbox" style="display: none;" class="modal" data-controller="ModalController">
        <div class="content">
            <h3 id="special-offer-lightbox-title"></h3>
            <p id="special-offer-lightbox-body">

            </p>
            <a href="#" class="close">X</a>
        </div>
        <div class="mask"></div>
    </div>
</div>
<!-- end #alert.modal-->