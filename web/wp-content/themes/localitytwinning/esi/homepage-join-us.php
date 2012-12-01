<?php

if (empty($_COOKIE)) {
    $isUserLoggedIn = false;
} else {
    require __DIR__ . '/../../../../wp-load.php';
    $isUserLoggedIn = is_user_logged_in();
}

?>


<?php if (!$isUserLoggedIn): ?>
<div class="row">
    <!-- begin .my-wheels-->
    <div class="my-wheels">
        <div class="main"><div class="pos">
            <h3>My Wheels</h3>
            <p>Save vehicles, create and share comparisons and get exclusive offers</p>
            <a href="#" data-controller="ModalTriggerController" data-modal="#login-signup">Join today</a>
        </div>
        </div>
        <div class="sidebar">
            <div class="pos">
                <ul>
                    <li>
                        <a href="/about-us">Learn more</a>
                    </li>
                    <li>&nbsp;|&nbsp;</li>
                    <li>
                        <a href="#" data-controller="ModalTriggerController" data-modal="#login-signup">Sign-in</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- end .my-wheels-->
</div>
<?php endif; ?>
