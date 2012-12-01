<?php

    require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

    global $wpdb;
    $postModel = new \Emicro\Model\Post($wpdb);
    $vehicleReviewId = $postModel->getLatestReviewVideoId();

    if (!empty($vehicleReviewId)){

?>
        <h3>Review Videos</h3>

            <div style="display:none"></div>

            <script language="JavaScript" type="text/javascript" src="http://admin.brightcove.com/js/BrightcoveExperiences.js"></script>

            <object id="myExperience" class="BrightcoveExperience">
              <param name="bicolour" value="#FFFFFF" />
              <param name="width" value="492" />
              <param name="height" value="276" />
              <param name="playerID" value="<?php echo $vehicleReviewId->vehicle_review_video_id; ?>" />
              <param name="playerKey" value="AQ~~,AAAAuO4KaJE~,gatFNwSKdGAV2K-jIQWI4tqIPyv965cW" />
              <param name="isVid" value="true" />
              <param name="isUI" value="true" />
              <param name="dynamicStreaming" value="true" />
              <param name="includeAPI" value="true" />
              <param name="templateLoadHandler" value="myTemplateLoaded" />
              <param name="@videoList" value="1377100463001" />
              <param name="@playlistCombo" value="1377100463001" />
              <param name="@playlistTabs" value="1377100463001" />
              <param name="wmode" value="transparent" />
            </object>

<div style="display:none"></div>

<?php } ?>

