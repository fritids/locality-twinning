<?php
global $wpdb;
$quote = htmlentities(strip_tags($custom_value->quote)); if(!empty($quote)): ?>

<!--Pull Quote-->
<div class="pull-quote">
    <p><?php echo stripslashes($quote) ?></p>
</div>
<?php endif; ?>
