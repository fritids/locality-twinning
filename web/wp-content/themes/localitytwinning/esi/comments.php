<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';
global $withcomments, $rating, $redirectURL;
$withcomments = true;
$redirectURL = $_GET['redirect'];

if ( !defined('COMMENTS_TEMPLATE') || !COMMENTS_TEMPLATE)
    define('COMMENTS_TEMPLATE', true);
$postId = (int)$_GET['post_id'];
$rating = (isset($_GET['rating'])) ? true : false;


$postType = get_post_type($postId);
list($empty, $vehicle_post) = explode('/', $_SERVER['REQUEST_URI']);

if (in_array($postType, array('news', 'feature', 'reviews')) && $vehicle_post != 'vehicles'):
?>
<div class="row" style="clear:both"></div>
<div id='taboola-div'></div>
<script type="text/javascript">
if(!isMobile || ( isMobile && getCookie('isMobile') == 'no')){
    window._taboola = window._taboola || [];
    _taboola.push({article:'auto'});
    _taboola.push({mode:'horizontalx4', container:'taboola-div', link_target:'lightbox'});
}
</script>
<script type="text/javascript" src="http://cdn.taboolasyndication.com/libtrc/torontostar-wheels/loader.js"></script>
<div class="row" style="clear:both"></div>
<?php endif  ?>

<div class="row section"><!-- begin .comments-->
    <div id="submit"></div>
    <div id="comments">
        <a name="comment-container"></a>
        <div class="main-content">
            <div id="comment-loader">
                <?php //echo wheels_esi_include(get_template_directory_uri().'/comment-ajax.php?post_id='.get_the_ID()) ?>
            </div>
            <?php
            wheel_comment_form(array(), $postId);
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