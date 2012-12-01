<?php

require __DIR__ . '/../../../../wp-load.php';

global $wpdb;
$userModel = new \Emicro\Model\User($wpdb);

?>
<!-- begin #login-signup.modal-->
<div id="login-signup" style="display: none;" class="modal">

    <div class="content">

        <div class="become-member">

            <h3>Become a member</h3>
            <p>Register now to access all features including:</p>
            <ul>
                <li>Save and ask friends to review vehicles</li>
                <li>Exclusive rebates &amp; offers from local dealers</li>
                <li>Premium content, reviews and tools</li>
            </ul>
            <p>All for free!</p>

            <div class="signup">

                <div class="social-signup clearfix"><strong>Sign-up using</strong>
                    <a href="#fb" class="facebook">Facebook</a>
                    <a href="#tw" class="twitter">Twitter</a>
                </div>

                <hr/>
                <span class="or">or</span>

                <div class="email-signup">
                    <strong>Sign-up via email address</strong>
                    <form id="via-email-form" method="post">
                        <input type="email" name="email" id="via-email" placeholder="name@email.com"/>
                        <input type="button" value="Continue" class="formbtn green" id="via-continue" data-controller="ModalTriggerController" data-modal="#registration"/>
                    </form>
                </div>

            </div>

        </div>

        <div class="member-signin">

            <h3>Already a member?</h3>
            <p>Sign-in below</p>

            <div class="social-signin clearfix">
                <strong>Sign-in with</strong>
                <a href="#fb" id="fb-auth-loginlink" class="facebook">Facebook</a>
                <a href="#tw" class="twitter">Twitter</a>
            </div>

            <hr/>

            <div class="email-signin">

                <span class="status email-status error"></span>
                <span class="status pass-status error"></span>
                <span class="status login-status error"></span>

                <form id="signin-form" method="post">

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="name@email.com"/><br/>

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password"/><br/>

                    <input type="submit" value="Sign-in" class="formbtn green"/>
                    <a href="<?php echo site_url().'/lostpassword?action=lostpassword'; ?>" class="forgot-password">Forgot my information</a>

                    <input type="hidden" name="hdn-signin-form" value="1" />
                    <input type="hidden" name="log" value="" id="wp-log" />
                    <input type="hidden" name="pwd" value="" id="wp-pwd" />
                    <input type="hidden" name="redirect_to" value="" id="wp-redir" />
                </form>

            </div>

        </div>

        <a href="#" class="close">X</a>

    </div>

    <div class="mask"></div>

</div>
<!-- end #login-signup.modal-->