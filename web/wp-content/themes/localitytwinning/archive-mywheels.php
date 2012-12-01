<?php global $pageTitle; $pageTitle = 'My Wheels' ?>
<?php get_header('meta'); ?>
<body class="page mywheels">
<!-- begin #container-->
<div id="container" data-role="page">
    <!-- begin #topads-->
    <?php get_header()?>
    <?php if(!is_user_logged_in()): ?>
    <div id="my-wheels" class="section-container clearfix"><h2>Access Denied</h2></div>
    <?php else: ?>
        <div id="my-wheels" class="section-container clearfix"><h2>My Wheels</h2>
            <!-- begin .tabs-section-->
            <?php
            $current_user = wp_get_current_user();
            $current_user_id = $current_user->ID;
            ?>
            <div data-controller="TabsController" class="tab-section">
                <!-- begin .tab-nav-->
                <div class="tab-nav">
                    <ul class="clearfix">
                        <li><a href="#" class="active"><?php echo $current_user->user_login; ?></a></li>
                    </ul>
                </div>
                <!-- end .tab-nav-->
                <!-- begin .tabs-->
                <div class="tabs">
                    <div id="profile" class="tab clearfix">
                        <div class="profile-details clearfix">
                            <div class="member-details">
                                <div class="avatar">
                                    <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.$current_user_id) ?>
                                </div>
                                <h3><?php echo $current_user->user_login; ?></h3>
                                <span>Member since <?php echo date("F Y",strtotime($current_user->user_registered)); ?></span>
                                <a href="<?php echo site_url(); ?>/myprofile" class="primary edit">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                    <br/><br/><br/><br/><br/><br/><br/><br/><br/>
                </div>
                <!-- end .tabs-->
            </div>
            <!-- end .tabs-section-->
        </div>
    <?php endif; ?>
<?php get_footer() ?>
</body>
</html>