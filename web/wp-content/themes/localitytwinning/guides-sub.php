<?php
require_once '/../../../wp-load.php';
$cp = new \Emicro\Plugin\Custom_post();
$rows = $cp->wheels_guides_query();
if(count($rows))
{
?>
<!-- begin .listing-->
<div class="listing">
    <ul>
        <?php
        $i = 0;
            foreach($rows as $r)
            {
        ?>
        <li class="<?php echo ($i%2)?'even':'odd'; ?>">
            <div class="wrap"><a href="#"><img alt="Vehicle listing" src="/img/cars/vehicle-listing.jpg"/><p><?php echo $r->post_title; ?></p></a><span class="sponsor">Sponsored</span></div>
        </li>
        <?php
            }
        ?>
    </ul>
</div>
<!-- end .listing-->
<?php
}
?>