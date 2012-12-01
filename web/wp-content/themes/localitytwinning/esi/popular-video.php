<?php
exit;
require '../../../../wp-load.php';
global $wpdb;
?>
<div class="row section-row"><h3>Popular Videos</h3><!-- begin .popular-videos-->
    <div class="popular-videos">
        <div class="video-container">&nbsp;</div>
    </div>
    <!-- end .popular-videos-->
    <div class="mrec-ad">
        <?php
        $adModel = new \Emicro\Model\Ad($wpdb); $ad = $adModel->getAd('300x250');
        echo $ad;
        ?>
    </div>
</div>