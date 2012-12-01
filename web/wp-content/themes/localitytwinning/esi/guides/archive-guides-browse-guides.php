<?php require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php'; ?>
<?php
global $wpdb;
$taxonomyModel = new \Emicro\Model\Taxonomy($wpdb);
?>
<!-- begin .browse-guides-->
<div class="browse-guides">
    <!-- begin .header-->
    <div class="header">
        <h3>Browse guides</h3>
        <!-- begin .pagination-->
        <div class="pagination"></div>
        <!-- end .pagination-->
    </div>
    <!-- end .header-->
    <!-- begin .navigation-->
    <?php
    $taxos = $taxonomyModel->getTerms(array('taxonomy' => 'guides-category'));
    if(count($taxos))
    {
        ?>
        <div class="navigation">
            <ul class="container guides-taxonomy-list">
                <?php
                $c = 1;
                foreach($taxos as $tx)
                {
                    ?>
                    <li class="<?php ($c==1)?'first':'' ?>">
                        <div class="pos"><a href="#" class="title" rel="<?php echo $tx->slug ?>"><?php echo $tx->name; ?></a></div>
                    </li>
                    <?php
                    $c++;
                }
                ?>
            </ul>
        </div>
        <?php
    }
    ?>
    <!-- end .navigation-->
    <!-- begin .listing-->
    <div id="guides-sub"></div>
    <!-- end .listing-->
</div>
<!-- end .browse-guides-->