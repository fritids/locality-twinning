<div class="row section"><!-- begin .comments-->
    <div id="submit"></div>
    <div id="comments">
        <a name="comment-container"></a>
        <div class="main-content">
            <div id="comment-loader">
            <?php //echo wheels_esi_include(get_template_directory_uri().'/comment-ajax.php?post_id='.get_the_ID()) ?>
            </div>
            <?php
            wheel_comment_form();
            ?>
            <!-- end .comments-->
        </div>

        <div class="right-column">
            <div class="mrec-ad">
                <?php
                // Hide ad, it might require later, so keep code
                /* global $adModel;
                echo $adModel->getAd('300x250'); */
                ?>
            </div>
        </div>

    </div>
    <!-- end .comments-->
</div>