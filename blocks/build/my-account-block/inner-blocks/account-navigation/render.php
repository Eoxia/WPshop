<?php

use wpshop\Pages;

$connection_page = Pages::g()->get_connection_link();
if (!is_user_logged_in()) {
    wp_redirect($connection_page);
    exit;
}

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?> data-logout-url="<?php echo wp_logout_url( home_url() ); ?>">
</div>