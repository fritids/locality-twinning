<?php

global $wpdb, $adModel;

$userModel = new \Emicro\Model\User($wpdb);
$postModel = new \Emicro\Model\Post($wpdb);
$vehicleModel = new \Emicro\Model\Vehicle($wpdb);

$popularVehicle = $vehicleModel->getVehicles(array('year' => 2011), 0, 6, "popularity desc");
$reviews = $postModel->getAll(array('limit' => 3, 'post_type' => 'reviews'));

?>

<!-- begin #modal-screens-->
<div id="modal-screens" data-controller="ModalController">

    <?php echo wheels_esi_include(get_template_directory_uri() . '/modals/login.php'); ?>

    <!-- begin #registration.modal-->
    <div id="registration" style="display: none;" class="modal" data-controller="ModalController">

        <div class="content">

            <h3>Registration 2 of 2</h3>

            <div class="avatar-selection">

                <form id="form_upload" name="form_upload" method="post" >

                    <?php

                    //fetching default avatars
                    $apath = "/wp-content/plugins/wheels-my-wheels/default-avatars/";
                    $dcr = $_SERVER["DOCUMENT_ROOT"] .$apath;
                    $srl = site_url() .$apath;

                    $avatar_defaults = array();
                    $handler = opendir($dcr);

                    while ($file = readdir($handler)) {
                        if ($file != "." && $file != "..") {
                            $avatar_defaults[] = $file;
                        }
                    }

                    closedir($handler);
                    ?>

                    <img src="<?php echo $srl.$avatar_defaults[0]; ?>" alt="current avatar" class="current-avatar"/>
                    <a href="#" id="upload_link" class="upload">Upload a new image</a>
                    <div class="generic-options">

                        <span>Or, choose one below</span>

                        <?php

                        $avatar_list = '<br/>';
                        $by_default = '';
                        $i = 1;

                        foreach ( $avatar_defaults as $default_name ) {

                            if($default_name!='..') {

                                if($i==1){
                                    $by_default = $default_name;
                                }

                                $avatar_list .= "<span><a id='avatar_{$default_name}' class='avt-lnk' rel='" . esc_attr($default_name)  . "' href='javascript:void(0)' > ";
                                $avatar_list .= '<img src="'.$srl.$default_name.'" class="option" alt="'.$default_name.'">';
                                $avatar_list .= '</a></span>';
                            }

                            $i++;
                        }

                        echo apply_filters('default_avatar_select', $avatar_list);

                        ?>
                    </div>
                </form>
            </div>

            <div class="registration-form">

                <form id="register-form" method="post">

                    <label for="reg-email">Email</label>
                    <input type="email" name="email" id="reg-email" value="" placeholder="name@email.com" />
                    <span class="status email-status error"></span>

                    <label for="username">Select a user name</label>
                    <input type="text" name="username" id="username" value="" placeholder="captaincrunch"/>
                    <span class="status username-status available"></span>
                    <div id="step2-clear"></div>

                    <fieldset class="password">
                        <label for="password" class="pass1">Password</label>
                        <label for="password2">Confirm password</label>
                        <input type="password" name="password" id="password" class="pass1"/>
                        <input type="password" name="password2" id="password2"/>
                        <span class="status pass-status error"></span>
                        <span class="status pass2-status error pass2"></span>
                    </fieldset>

                    <input type="checkbox" name="subscribe-newsletter" id="subscribe-newsletter" class="subscribe-newsletter"/>
                    <label for="subscribe-newsletter" class="checkbox subscribe-newsletter">
                        Subscribe me to the Wheels.ca Weekly Newsletter
                    </label>

                    <input type="checkbox" name="subscribe-deals" id="subscribe-deals" class="subscribe-deals"/>
                    <label for="subscribe-deals" class="checkbox subscribe-deals">
                        Keep me informed of deals and specials relevant to me
                    </label>

                    <input type="checkbox" name="agree-terms" id="agree-terms" class="agree-terms"/>
                    <label for="agree-terms" class="checkbox agree-terms">
                        I have read and agree to the&nbsp;<a href="#" id="term-service-link" class="terms">Terms of Service &amp; Privacy Policy</a>
                    </label>

                    <input type="submit" id="finish" value="Finished" class="formbtn green"/>
                    <div id="reg-submit-loading"></div>
                    <input type="hidden" name="hdn-registration-form" value="1"/>
                    <input type="hidden" id="hdn-fb-action" value=""/>
                    <input type="hidden" name="hdn_facebook_user_id" id="hdn-fb-id" value="<?php if(isset($_SESSION['signup_facebook_user_id'])){echo $_SESSION['signup_facebook_user_id']; unset($_SESSION['signup_facebook_user_id']);}else{echo '0';} ?>"/>
                    <input type="hidden" name="hdn_twitter_user_id" id="hdn-tw-id" value="<?php if(isset($_SESSION['signup_twitter_user_id'])){echo $_SESSION['signup_twitter_user_id']; unset($_SESSION['signup_twitter_user_id']);}else{echo '0';} ?>"/>
                    <input type="hidden" name="hdn_avatar" id="hdn_avatar" value="<?php echo $by_default; ?>" />
                    <input type="hidden" name="hdn_avatar_path" id="hdn_avatar_path" value="" />

                </form>

            </div>
            <a href="#" class="close">X</a></div>

        <div class="mask"></div>

    </div>
    <!-- end #registration.modal-->

    <!-- begin #confirmation.modal-->
    <div id="confirmation" style="display: none;" class="modal" data-controller="ModalController">
        <div class="content">
            <h3>Confirmation</h3>
            <p>Welcome to Wheels!<br/><br/>As a final step we've sent a confirmation to your email address as a security measure. Please click the link in the email to complete your registration.</p><!--<a href="#" class="primary">Complete your profile</a>-->
            <a href="#" id="close-confirmation" class="close">X</a></div>
        <div class="mask"></div>
    </div>
    <!-- end #confirmation.modal-->

    <!-- begin #termsview.modal-->
    <div id="termsview" style="display: none;" class="modal" data-controller="ModalController">
        <div class="content">
            <h3>Terms of services</h3>
            <p>
                DISCLAIMER OF WARRANTIES AND LIMITATION OF LIABILITY
            <p/>
            <p>
                TO THE FULLEST EXTENT PERMITTED BY LAW, TORONTO STAR IS PROVIDING THE TORONTO STAR WEBSITES ON AN "AS IS" AND â€œAS AVAILABLEâ€ BASIS AND MAKES NO WARRANTIES OR REPRESENTATIONS, EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE, IN ANY CONNECTION WITH THE TORONTO STAR WEBSITES, THEIR CONTENTS, OR ANY WEB SITE OR CONTENTS WITH WHICH IT IS LINKED. TORONTO STAR DOES NOT WARRANT THAT THE FUNCTION OF THE TORONTO STAR WEBSITES OR THEIR CONTENTS WILL BE UNINTERRUPTED OR ERROR FREE, THAT DEFECTS WILL BE CORRECTED, OR THAT THE TORONTO STAR WEBSITES OR THE SERVERS THAT MAKE IT AVAILABLE ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS.
            <p/>
            <p>
                TO THE FULLEST EXTENT PERMITTED BY LAW, UNDER NO CIRCUMSTANCES, INCLUDING, BUT NOT LIMITED TO, NEGLIGENCE, SHALL TORONTO STAR BE LIABLE FOR ANY LOSS OF USE, LOSS OF DATA, LOSS OF INCOME OR PROFIT, LOSS OF OR DAMAGE TO PROPERTY, OR FOR ANY DAMAGES OF ANY KIND OR CHARACTER (INCLUDING WITHOUT LIMITATION ANY COMPENSATORY, INCIDENTAL, DIRECT, INDIRECT, SPECIAL, PUNITIVE, OR CONSEQUENTIAL DAMAGES), EVEN IF TORONTO STAR HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES OR LOSSES, ARISING OUT OF OR IN CONNECTION WITH THE USE OF THE TORONTO STAR WEBSITES, THEIR CONTENTS, OR ANY WEBSITE OR CONTENTS WITH WHICH IT IS LINKED. IN NO EVENT SHALL TORONTO STARâ€™S TOTAL LIABILITY FOR ALL DAMAGES, LOSSES, AND CAUSES OF ACTION, WHETHER IN CONTRACT, TORT (INCLUDING, BUT NOT LIMITED TO, NEGLIGENCE), OR OTHERWISE, EXCEED THE AMOUNT PAID BY YOU FOR ACCESSING THIS SITE.
            <p/>
            <a href="#" class="close" id="close-term">X</a></div>
        <div class="mask"></div>
    </div>
    <!-- end #termsview.modal-->

    <!-- begin #galert.modal-->
    <div id="galert" style="display: none;" class="modal" data-controller="ModalController">
        <div class="content">
            <h3 id="galert-title"></h3>
            <p id="galert-body"></p>
            <a href="#" class="close">X</a></div>
        <div class="mask"></div>
    </div>
    <!-- end #galert.modal-->

    <!-- begin #alert.modal-->
    <div id="commentModerationConfirmation" style="display: none; text-align: center" class="modal" data-controller="ModalController">
        <div class="content">
            <h3 id="ac-message-title">Your comment is awaiting moderation.</h3>
            <p id="ac-message-body"></p>
            <a href="#" class="close">X</a>
        </div>
        <div class="mask"></div>
    </div>
    <!-- end #alert.modal-->

</div>
<!-- end ##modal-screens-->

<?php get_footer('navigation') ?>

</div>
<!-- end #container-->

<!-- #fb-root-->
<div id="fb-root"></div>

<!-- libraries-->

<script>window.jQuery || document.write('<script src="<?php echo get_template_directory_uri();?>/js/libs/jquery-1.7.1.min.js"><\/script>')</script>
<script>document.write('<script src="<?php echo get_template_directory_uri();?>/js/libs/jquery-ui-1.8.16.custom.min.js"><\/script>')</script>

<?php

wp_reset_postdata();

if(isset($post) && $post->ID) {
    $post_id = $post->ID;
    $popularityPlugin = new \Emicro\Plugin\Popularity($wpdb);
    $popularity = $popularityPlugin->update($post_id);
}

\Emicro\Plugin\Assets::js(WP_CONTENT_URL . "/themes/localitytwinning/js/libs/jquery.easing.1.3.js");
\Emicro\Plugin\Assets::js(WP_CONTENT_URL . "/themes/localitytwinning/js/vehicle-finder.js");
\Emicro\Plugin\Assets::js(WP_CONTENT_URL . "/themes/localitytwinning/js/script.js");
\Emicro\Plugin\Assets::js(WP_CONTENT_URL . "/plugins/wheels-my-wheels/rajax.js");
\Emicro\Plugin\Assets::js(WP_CONTENT_URL . "/themes/localitytwinning/js/reviews.js");
\Emicro\Plugin\Assets::js(WP_CONTENT_URL . "/themes/localitytwinning/js/home.js");
\Emicro\Plugin\Assets::js(WP_CONTENT_URL . "/themes/localitytwinning/js/wheels.js");
\Emicro\Plugin\Assets::js(WP_CONTENT_URL . "/themes/localitytwinning/js/vehicles.js");
\Emicro\Plugin\Assets::js(WP_CONTENT_URL . "/themes/localitytwinning/js/event-tracking.js");

\Emicro\Plugin\Assets::includeScripts();

?>

<!-- facebook-->
<script type="text/javascript">

    (function(d){
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
    }(document));

    // Init the SDK upon load
    window.fbAsyncInit = function () {
        FB.init({
            appId:<?php echo FACEBOOK_API_ID ?>, // App ID
            channelUrl:'//' + window.location.hostname + '/channel', // Path to your Channel File
            status:true, // check login status
            cookie:true, // enable cookies to allow the server to access the session
            xfbml:true  // parse XFBML
        });

        // listen for and handle auth.statusChange events
        FB.Event.subscribe('auth.statusChange', function (response) {
            if (response.authResponse) {
                // user has auth'd your app and is logged into Facebook
                if (typeof(LOGGED_IN) != 'undefined' && LOGGED_IN == 'false') {
                    FB.api('/me', function (me) {
                        fbuser = me;
                        $.ajax({
                            type: 'post',
                            url: '/wp-content/plugins/wheels-misc/check_fb_user.php',
                            data: {'fbid': me.id},
                            success: function(data) {
                                if (data == 'NOT FOUND') {
                                    $('#reg-email').val(me.email);
                                    $('#hdn-fb-id').val(me.id);
                                    $("#via-continue").click();
                                } else {
                                    $('#fbid').val(me.id);
                                    $('#fbhash').val(data);
                                    $('#fblogin').submit();
                                }
                            }
                        });
                    })
                }
            }
        });

        // respond to clicks on the login and logout links
        $('.facebook').click(function(){
            FB.login(function(response) {
                // handle the response
            }, {scope: 'email'});
        });

        twttr.anywhere.config({ callbackURL: "<?php echo site_url() ?>/wp-content/plugins/wheels-misc/tw_callback.php" });

        twttr.anywhere(function (T) {

            $('.twitter').click(function(){
                T.signIn();
            });

            T.bind("authComplete", function (e, user) {
                if (typeof(LOGGED_IN) != 'undefined' && LOGGED_IN == 'false') {
                    $.ajax({
                        type: 'post',
                        url: '/wp-content/plugins/wheels-misc/check_tw_user.php',
                        data: {'twid': user.id},
                        success: function(data) {
                            if (data == 'NOT FOUND') {
                                $('#hdn-tw-id').val(user.id);
                                $("#via-continue").click();
                            } else {
                                $('#twid').val(user.id);
                                $('#twhash').val(data);
                                $('#twlogin').submit();
                            }
                        }
                    });
                }
            });

        });
    }

</script>

<!--<script src="http://platform.twitter.com/anywhere.js?id=<?php /*echo TWITTER_CONSUMER_KEY */?>&v=1" type="text/javascript"></script>-->
<script src="http://platform.twitter.com/anywhere.js?id=YQ56nRDeNvT07hamRvPEQ&v=1" type="text/javascript"></script>

<form id="fblogin" action="<?php site_url() ?>/wp-content/plugins/wheels-misc/login_redirect.php" method="post">
    <input id="fbid" type="hidden" name="fbid" value="" />
    <input id="fbhash" type="hidden" name="hash" value="" />
</form>

<form id="twlogin" action="<?php site_url() ?>/wp-content/plugins/wheels-misc/login_redirect.php" method="post">
    <input id="twid" type="hidden" name="twid" value="" />
    <input id="twhash" type="hidden" name="hash" value="" />
</form>

<?php if (!wheels_is_development_server()): ?>

<!-- twitter-->
<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>

<?php endif; ?>

<?php wp_footer(); ?>