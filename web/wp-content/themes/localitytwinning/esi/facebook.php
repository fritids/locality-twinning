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
                FB.api('/me', function (me) {
                    $('#fbid').val(me.id);
                    $('#fblogin').submit();
                })
            }
        });

        // respond to clicks on the login and logout links
        $('.facebook').click(function(){
            FB.login();
        });

    }

</script>

<form id="fblogin" action="<?php site_url() ?>/wp-content/plugins/wheels-misc/login_redirect.php" method="post">
    <input id="fbid" type="hidden" name="fbid" value="" />
</form>