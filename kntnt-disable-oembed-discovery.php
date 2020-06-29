<?php

/**
 * @wordpress-plugin
 * Plugin Name:
 * Plugin URI:        https://www.kntnt.com/
 * Description:       Disables oEmbed dicovery of this site.
 * Version:           1.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

defined( 'ABSPATH' ) || die;

add_action( 'init', function () {


    //Remove the REST API endpoint.
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );

    // Turn off oEmbed auto discovery.
    add_filter( 'embed_oembed_discover', '__return_false' );

    //Don't filter oEmbed results.
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

    //Remove oEmbed discovery links.
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

    //Remove oEmbed JavaScript from the front-end and back-end.
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );

    //Remove oEmbed JavaScript from the back-end.
    add_filter( 'tiny_mce_plugins', function ( $plugins ) {
        return array_diff( $plugins, [ 'wpembed' ] );
    } );

    // Remove all embeds rewrite rules.
    add_filter( 'rewrite_rules_array', function ( $rules ) {
        foreach ( $rules as $rule => $rewrite ) {
            if ( false !== strpos( $rewrite, 'embed=true' ) ) {
                unset( $rules[ $rule ] );
            }
        }
        return $rules;
    } );

    // Remove filter of the oEmbed result before any HTTP requests are made.
    remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );

}, 9999 );