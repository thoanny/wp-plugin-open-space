<?php

/**
 * Plugin Name:       Open Space de Thoanny
 * Plugin URI:        https://anthony-destenay.fr
 * Description:       Fonctionnalités liées à Mix It Up, StreamElements et Discord
 * Version:           1.0.1
 * Author:            Anthony Destenay
 * Author URI:        https://anthony-destenay.fr
 * GitHub Plugin URI: https://github.com/thoanny/wp-plugin-open-space
 * Primary Branch:    main
 */

function thoanny_openspace_enqueue_script( $hook ) {
    wp_enqueue_style('thoanny-open-space', plugins_url( '/style.css', __FILE__ ));

    wp_enqueue_script(
        'thoanny-open-space',
        plugins_url( '/script.js', __FILE__ ),
        array( 'jquery' ),
        '1.0.0',
        true
    );

    wp_localize_script(
        'thoanny-open-space',
        'thoannyopenspace',
        array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
        )
    );

}
add_action( 'wp_enqueue_scripts', 'thoanny_openspace_enqueue_script' );

if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title' 	=> 'Open Space',
        'menu_title'	=> 'Open Space',
        'menu_slug' 	=> 'open-space',
        'capability'	=> 'edit_posts',
        'redirect'		=> false,
        'icon_url'      => 'dashicons-games'
    ));

}

// Allow JSON files (for leaderboard)
function thoanny_openspace_mime_types($mimes) {
    $mimes['json'] = 'application/json';
    return $mimes;
}
add_filter('upload_mimes', 'thoanny_openspace_mime_types');


require plugin_dir_path(__FILE__) . 'includes/shortcodes/discord-events.php';
require plugin_dir_path(__FILE__) . 'includes/shortcodes/mixitup-leaderboard.php';
require plugin_dir_path(__FILE__) . 'includes/shortcodes/streamelements-activity.php';
require plugin_dir_path(__FILE__) . 'includes/shortcodes/jeu-decouvre-vod.php';
