<?php
/** @var DelivereaShipping $delivereashipping */
$delivereashipping = $GLOBALS['DELIVEREASHIPPING'];
?>

<form method="POST">
    <?php do_settings_sections('deliverea-config'); ?>
    <?php submit_button(); ?>
</form>