<?php

use wpshop\Pages;

$connection_page = Pages::g()->get_connection_link();
if (!is_user_logged_in()) {
    // Guests are normally redirected before any output (Display_Action::redirect_guest_from_my_account).
    // When the block is used elsewhere, redirect only if the page has not started printing.
    if (!headers_sent()) {
        wp_safe_redirect($connection_page);
        exit;
    }
    return;
}

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?> data-logout-url="<?php echo wp_logout_url( home_url() ); ?>">
</div>