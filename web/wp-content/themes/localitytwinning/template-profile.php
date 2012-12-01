<?php
/*
 * Template Name: My Profile
 */
?>
<?php get_header('meta')?>
<body class="page article news mobile-page"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
    <?php get_header()?>
    <!-- begin news article-->
    <div id="news-article" class="section-container">
        <?php //wheels_breadcrumb() ?>


        <div id="profile"  class="modal1" >
            <div class="content"><h3>My Profile</h3>
                <?php
                $current_user = wp_get_current_user();
                $current_user_id = $current_user->ID;

                $row = $wpdb->get_row("SELECT VALUE FROM ".$wpdb->prefix."cimy_uef_data WHERE USER_ID = '$current_user_id' AND FIELD_ID = '1'");
                ?>
                <div class="avatar-selection">
                    <form id="form_upload" name="form_upload" method="post" >
                        <img src="<?php echo (isset($row->VALUE))?$row->VALUE:''; ?>" alt="current avatar"
                             class="current-avatar" width="80" height="80"/>
                        <?php //echo get_avatar( $current_user_id ); ?><br/><br/>
                        <a href="#" id="upload_link" class="upload">Upload a new image</a>
                        <br/><br/>
                    <div class="generic-options"><span>Or, choose one below</span>
                        <!--=========================================================-->
                        <?php
                        $apath = "/wp-content/plugins/wheels-my-wheels/default-avatars/";
                        $dcr = $_SERVER["DOCUMENT_ROOT"] .$apath;
                        $srl = site_url() .$apath;

                        $avatar_defaults = array();
                        $handler = opendir($dcr);
                        while ($file = readdir($handler)) {
                            if ($file != ".") {
                                $avatar_defaults[$file] = $file;
                            }
                        }
                        closedir($handler);

                        $avatar_list = '<br/>';
                        foreach ( $avatar_defaults as $default_key => $default_name ) {
                            if($default_key!='..')
                            {
                                $avatar_list .= "<span><a id='avatar_{$default_key}' class='avt-lnk' rel='" . esc_attr($default_key)  . "' href='javascript:void(0)' > ";
                                $avatar_list .= '<img src="'.$srl.$default_key.'" class="option" alt="'.$default_name.'" width="37px" height="37px">';
                                $avatar_list .= '</a></span>';
                            }
                        }
                        echo apply_filters('default_avatar_select', $avatar_list);
                        ?>
                        <!--==========================================================-->

                    </div>
                    </form>
                </div>
                <div class="profile-form">

                    <form id="profile-form" method="post">
                        <label for="first_name">Firstname</label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo get_user_meta($current_user_id, 'first_name', true); ?>" />
                        <span class="status first_name-status error"></span>

                        <label for="last_name">Lastname</label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo get_user_meta($current_user_id, 'last_name', true); ?>" />
                        <span class="status last_name-status available"></span>

                        <label for="password" class="pass1">Password</label>
                        <input type="password" name="password" id="password" class="pass1"/>
                        <span class="status pass-status error"></span>

                        <label for="password2">Confirm password</label>
                        <input type="password" name="password2" id="password2"/>
                        <span class="status pass2-status error"></span>

<!--                        <input type="checkbox" name="subscribe-newsletter" id="subscribe-newsletter" class="subscribe-newsletter"/>-->
<!--                        <label for="subscribe-newsletter" class="checkbox subscribe-newsletter">-->
<!--                            Subscribe me to the Wheels.ca Weekly Newsletter-->
<!--                        </label>-->
<!---->
<!--                        <input type="checkbox" name="subscribe-deals" id="subscribe-deals" class="subscribe-deals"/>-->
<!--                        <label for="subscribe-deals" class="checkbox subscribe-deals">-->
<!--                            Keep me informed of deals and specials relevant to me-->
<!--                        </label>-->
                        <div style="clear:both"></div><br/><br/>
                        <input type="submit" id="finish" value="Update" class="formbtn green"/>
                        <input type="hidden" name="hdn-profile-form" value="1"/>

                        <input type="hidden" name="hdn_avatar" id="hdn_avatar" value="" />

                    </form>
                </div>
                </div>
            <div class="mask"></div>
        </div>
        <!-- end #registration.modal-->



    </div>
    <?php get_footer()?>
    <script type="text/javascript" src="<?php echo plugin_dir_url('wheels-my-wheels') ?>wheels-my-wheels/rajax.js"></script>
</body>
</html>