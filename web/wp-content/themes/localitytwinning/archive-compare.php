<?php global $pageTitle; $pageTitle = 'Compare' ?>
<?php get_header('meta'); ?>
<body class="page compare"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
<?php get_header()?>
<!-- begin article.content-->
<article class="content"><!-- begin header-->
<header>
    <h1>Compare Vehicles</h1>
    <p>Create your Comparison then add it to My Wheels or share it to get other's feedback.</p>
    <!--being .compare-utility-->
    <ul class="compare-utility">
    </ul>
    <!--end .compare-utility-->
</header>
<!-- end header-->

<!-- begin .wrap-->
<div class="wrap"><!-- begin .compare-container-->
    <div data-controller="CompareController" class="compare-container compare1 clearfix">
        <!-- begin .compare-filters-->
        <div class="compare-filters">
            <label>Compare up to three vehicles</label>
            <!-- begin .form-container-->
            <div class="form-container">
                <form>
                    <!-- begin .make-container-->
                    <div class="make-container">
                        <select id="compare-filter-make" name="compare-make-selector" data-controller="ComboboxController" class="compare-selector ui-light">
                            <?php
                            global $wpdb;
                            $makes = $wpdb->get_results("SELECT DivCode AS makeCode, DivDesc AS makeName FROM wp_make");
                            ?>
                            <option selected="selected">Make</option>
                            <?php
                            foreach($makes as $row):
                            ?>
                                <option class="<?php echo $row->makeCode?>" value="<?php echo $row->makeName?>"><?php echo $row->makeName?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <!-- end .make-container-->
                    <!-- begin .model-container-->
                    <div class="model-container">
                        <select id="#compare-filter-model" name="model-selector" data-controller="ComboboxController" class="compare-selector ui-light">
                        <option>Model</option>
                        </select>
                    </div>
                    <!-- end .model-container-->
                    <!-- begin .year-container-->
                    <div class="year-container">
                        <select id="compare-year" name="year-selector" data-controller="ComboboxController" class="compare-selector ui-light">
                            <?php
                            global $wpdb;
                            $years = $wpdb->get_results("SELECT YearDesc AS YearDesc, YearCode AS YearCode FROM wp_year");
                            ?>
                            <option>Year</option>
                            <?php foreach($years as $row): ?>
                                <option value="<?php echo $row->YearDesc; ?>"><?php echo $row->YearDesc; ?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <!-- end .year-container-->
                    <!-- begin .action-container-->
                    <div class="action-container"><input type="submit" value="Add" id="add_to_compare" class="formbtn green"/></div>
                    <!-- end .action-container-->
                </form>
            </div>
            <!-- end .form-container-->
            <!-- begin .suggestions-container-->
            <div class="suggestions-container">
                <div id="loading-div"></div>
                <h4 id="title-suggestion"></h4>
                <div id="suggestion-list-panel">
                <!-- begin #suggestion-list-->

                <!-- end #suggestion-list-->
                </div>
            </div>
            <!-- end .suggestions-container-->
        </div>
        <!-- end .compare-filters-->

        <div id="archive-compare-sub"></div>

    </div>
    <!-- end .compare-container-->
    <!-- begin .share-->
    <div class="share"><!-- Begin AddThis Button-->
        <div class="addthis_toolbox addthis_default_style"><a class="addthis_counter"></a></div>
        <script type="text/javascript"
                src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=<?php echo ADDTHIS_PUBID ?>"></script>
        <!-- End AddThis Button-->
    </div>
    <!-- end .share-->
</div>
<!-- end .wrap-->
<?php get_footer()?>
<script type="text/javascript" src="/wp-content/themes/wheels/js/wheels-compare.js"></script>
</body>
</html>