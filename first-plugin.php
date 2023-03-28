<?php 
/**
 * Plugin Name: First Plugin
 * Description: Plugin de demo
 * Author: Clement Jonckheere
 * Version: 0.0.1
 * 
 */

function first_plugin_hello_word() {
    echo "<p>Hello Word !</p>";
}

add_action( 'admin_notices', 'first_plugin_hello_word');

function first_plugin_hello_title( $title ) {
    $custom_title = 'Hello Noah' . $title;
    return $custom_title;
}

add_filter( 'the_title', 'first_plugin_hello_title');