<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;
?>
<div class="vehicle-finder">

    <div class="pos">

        <div class="header">
            <h3>সমাধান খুঁজুন</h3>
        </div>

        <!-- begin .toolbar-->
        <div data-controller="HomepageSidebarController" class="toolbar">

            <!-- begin .tab-section-->
            <div data-controller="TabsController" class="tab-section">

                <!-- begin .tabs-->
                <div class="tabs">

                    <div id="new" class="tab">

                        <!-- begin .new-vehicles-->
                        <div class="new-vehicles">

                            <form method="post" action="/vehicle-finder/">

                                <!-- begin .class-container-->
                                <div class="class-container">
                                    <select id="home-filter-class" data-role="none" name="class" data-controller="ComboboxController" data-readonly="true" class="filter-selector ui-menu-class ui-light">
                                        <option value="none">জিলা</option>
                                        <option value="x">ঢাকা</option>
                                        <option value="x">খুলনা</option>
                                    </select>
                                </div>
                                <!-- end .class-container-->

                                <!-- begin .make-container-->
                                <div class="make-container">
                                    <select id="home-filter-make" data-role="none" name="make" data-controller="ComboboxController" class="filter-selector ui-menu-make ui-light">
                                        <option value="none">উপজেলা </option>
                                        <option value="none">উপজেলা ১</option>
                                        <option value="none">উপজেলা ২</option>
                                    </select>
                                </div>
                                <!-- end .make-container-->

                                <!-- begin .model-container-->
                                <div class="model-container">
                                    <select id="home-filter-model" data-role="none" name="model" data-controller="ComboboxController" class="filter-selector ui-menu-model ui-light">
                                        <option value="none">সমস্যা ধরন</option>
                                        <option value="none">স্যানিটেশন</option>
                                        <option value="none">অন্যান্য</option>
                                    </select>
                                </div>
                                <!-- end .model-container-->

                                <!-- begin .search-container-->
                                <div class="search-container">
                                    <input data-role="none" type="submit" value="Search" class="formbtn green"/>
                                </div>
                                <!-- end .search-vehicles-->
                            </form>

                        </div>
                        <!-- end .new-vehicles-->

                    </div>

                    <div id="used" class="tab">
                        <!-- begin .used-vehicles-->
                        <div class="used-vehicles" style="height: 225px;">

                        </div>
                        <!-- end .used-vehicles-->
                    </div>

                </div>
                <!-- end .tabs-->

            </div>
            <!-- end .tabs-section-->

        </div>
        <!-- end .toolbar-->

    </div>

</div>