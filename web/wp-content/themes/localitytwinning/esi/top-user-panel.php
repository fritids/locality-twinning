<?php

if (empty($_COOKIE)) {
    $isUserLoggedIn = false;
} else {
    require __DIR__ . '/../../../../wp-load.php';
    $isUserLoggedIn = is_user_logged_in();
}

?>

<script type="text/javascript">var LOGGED_IN = '<?php echo $isUserLoggedIn ? 'true' : 'false' ?>';</script>

<div class="mywheels">
    <ul>
    <?php if ($isUserLoggedIn): ?>
        <!--<li><h6><a href="<?php /*echo site_url(); */?>/mywheels/">My Wheels</a></h6></li>-->
        <li><a id="logout" href="#" rel="<?php echo wp_logout_url(site_url()); ?>">সাইন আউট</a></li>
    <?php else: ?>
        <li><a href="#" data-controller="ModalTriggerController" data-modal="#login-signup">সাইন ইন</a></li>
    <?php endif; ?>
    </ul>
</div>