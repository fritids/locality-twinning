<?php
/*
        Template Name: My wheels
 */
?>
<?php get_header('meta')?>
<body class="page mywheels"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
<?php get_header()?>
<!-- begin #main-->
<div id="main" role="main" class="clearfix">
<div id="my-wheels" class="section-container clearfix"><h2>My Wheels</h2>

<div data-controller="MessageController" class="user-message introduction"><p>Welcome to My Wheels, Lorem
    ipsum dolor sit amet, consectetur adipiscing elit. Phasellus laoreet accumsan rhoncus. Vivamus mollis
    malesuada mauris, sodales posuere purus congue quis.
</p><a href="#" class="close"></a></div>
<!-- begin .tabs-section-->
<?php
$current_user = wp_get_current_user();
$current_user_id = $current_user->ID;
?>
<div data-controller="TabsController" class="tab-section"><!-- begin .tab-nav-->
<div class="tab-nav">
    <ul class="clearfix">
        <li><a href="#" class="active"><?php echo $current_user->user_login; ?></a></li>
        <li><a href="#">My Garage</a></li>
        <li><a href="#">My Comparisons</a></li>
        <li class="settings"><a href="#">Settings</a></li>
    </ul>
</div>
<!-- end .tab-nav--><!-- begin .tabs-->

<div class="tabs">
<div id="profile" class="tab clearfix">
    <div class="profile-details clearfix">
        <div class="member-details">
            <div class="avatar">
                <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.$current_user_id) ?>
            </div>
            <h3><?php echo $current_user->user_login; ?></h3><span>Member since <?php echo date("F Y",strtotime($current_user->user_registered)); ?></span><a href="<?php echo site_url(); ?>/my-profile" class="primary edit">Edit
            Profile</a></div>

    </div>

</div>



</div>
<!-- end .tabs--></div>
<!-- end .tabs-section--></div>
<!-- begin .leaderboard-->
<div class="leaderboard"><a href="#" target="_blank"><img src="/img/ads/leaderboard.png" width="728" height="90"
                                                          alt=""/></a></div>
<!-- end .leaderboard-->
</div><!-- end #main-->
<?php get_footer() ?>