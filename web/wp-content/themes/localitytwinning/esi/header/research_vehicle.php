<?php
//require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
require $_SERVER['DOCUMENT_ROOT'] . '/wp-content/bootstrap.php';

$vehicleModel = new \Emicro\Model\Vehicle();

$classes = $vehicleModel->getClasses();
$makes = $vehicleModel->getMakes();
$models = $vehicleModel->getModels();
?>

<div class="research-vehicles">

    <form method="post" action="/vehicle-finder">

        <!-- begin .price-container-->
        <div class="price-container">
            <div class="min-value label">< $400K</div>
            <div id="price-slider" class="slider"></div>
            <input type="hidden" name="price[start]" class="priceRangeTop" value="1000"/>
            <input type="hidden" name="price[end]" class="priceRangeTop" value="50000"/>
        </div>
        <!-- end .price-container-->

        <!-- begin .class-container-->
        <div class="class-container">
            <select id="filter-class" data-role="none" name="class" data-controller="ComboboxController"
                    data-readonly="true" class="filter-selector ui-menu-class ui-light">
                <option value="none">All Classes</option>
                <?php $class_select = (!empty($_POST['class'])) ? $_POST['class'] : 'none';
                foreach ($classes as $class): ?>
                    <option<?php if (urldecode($class_select) == $class->name) echo ' selected="selected"' ?>><?php echo $class->name ?></option>
                    <?php endforeach; ?>
            </select>
        </div>
        <!-- end .class-container-->

        <!-- begin .make-container-->
        <div class="make-container">
            <select id="filter-make" data-role="none" name="make" data-controller="ComboboxController"
                    class="filter-selector ui-menu-make ui-light">
                <option value="none">All Makes</option>
                <?php $make_select = (!empty($_POST['make'])) ? $_POST['make'] : DEFAULT_SPONSORED_MAKE;
                foreach ($makes as $row): ?>
                    <option
                        class="<?php echo $row->makeCode ?>" <?php if (urldecode($make_select) == $row->makeName) echo ' selected="selected"' ?>
                        value="<?php echo $row->makeName ?>"><?php echo $row->makeName ?></option>
                    <?php endforeach; ?>
            </select>
        </div>
        <!-- end .make-container-->

        <!-- begin .model-container-->
        <div class="model-container">
            <select id="filter-model" data-role="none" name="model" data-controller="ComboboxController"
                    class="filter-selector ui-menu-model ui-light">
                <option value="none">All Models</option>
            </select>
        </div>
        <!-- end .model-container-->

        <!-- begin .search-container-->
        <div class="search-container">
            <input data-role="none" type="submit" value="Search" class="formbtn green">
        </div>
        <!-- end .search-vehicles-->

    </form>

</div>