<?php

/*
* Plugin Name: WPShop
* Description: WPShop is a WordPress plugin that provides a complete e-commerce solution for WordPress sites.
* Version: 2.0.0
*/
function request_get( $route ) {
    $url = get_site_url() . '/wp-json/' . $route;

    $request = wp_remote_get( $url );

    if ( is_wp_error( $request ) ) {
        return [];
    }

    $body_api = wp_remote_retrieve_body( $request );

    $data_api = json_decode( $body_api, true );

    return $data_api;
}