<?php

use wpshop\Pages;

$my_account_page = Pages::g()->get_account_link();

if (is_user_logged_in() && !is_admin()) {
    wp_redirect($my_account_page);
    exit;
}

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?> data-account-link="<?php echo esc_url( $my_account_page ); ?>">
</div>