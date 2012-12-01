<?php require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php'; ?>

<div class="browse-vehicles secondary"><!-- begin .header-->
        <div class="header"><h3>Second Opinions</h3><!-- begin .model-container-->
            <div class="model-container"><select data-role="none" name="model-selector"
                                                 data-controller="ComboboxController" data-readonly="true"
                                                 class="compare-selector ui-dark">
                <option value="all">All</option>
                <?php
                global $wpdb;
                $vehicleModel = new Emicro\Model\Vehicle($wpdb);
                $makes = $vehicleModel->getMakes();
                foreach ($makes as $make):
                    ?>
                    <option value="<?php echo $make->makeName ?>"><?php echo $make->makeName?></option>
                    <?php endforeach;?>
            </select></div>
            <!-- end .model-container--><!-- begin .pagination-->
            <div class="pagination">
                <ul>

                </ul>
            </div>
            <!-- end .pagination--></div>
        <!-- end .header--><!-- begin .vehicle-navigation-->
        <div class="vehicle-navigation">
            <div data-controller="TabsController" class="clearfix">
                <div class="tab-nav">
                    <ul class="clearfix">
                        <li><a href="#">Class</a></li>
                        <li class="last"><a href="#" class="last">Category</a></li>
                    </ul>
                </div>
                <div class="tabs">
                    <div class="tab">
                        <div class="viewport">
                            <ul class="container class-list">
                                <?php
                                $classes = $vehicleModel->getClasses();
                                foreach ($classes as $class):
                                    ?>
                                    <li class="slide">
                                        <div class="wrap">
                                            <a href="#" class="title"
                                               rel="<?php echo url_title($class->name)?>"><?php echo $class->name?></a>
                                        </div>
                                    </li>
                                    <?php endforeach;?>
                            </ul>
                        </div>
                    </div>
                    <div class="tab">
                        <div class="viewport">
                            <ul class="container category-list">
                                <?php
                                $categories = $vehicleModel->getCategories();
                                foreach ($categories as $category):
                                    ?>
                                    <li class="slide">
                                        <div class="wrap">
                                            <a href="#" class="title"
                                               rel="<?php echo url_title($category)?>"><?php echo $category?></a>
                                        </div>
                                    </li>
                                    <?php endforeach;?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- end .vehicle-navigation--><!-- begin .vehicle-listing-->
        <div class="vehicle-listing">
            <ul class="listing">

            </ul>
        </div>
        <!-- end .vehicle-listing-->
    </div>