<?php
require_once locate_template('inc/meta-boxes.php');
require_once locate_template('inc/custom-post-types.php');
require_once locate_template('inc/custom-taxonomy.php');

require_once locate_template('inc/theme-config.php');
require_once locate_template('inc/theme-general.php');
require_once locate_template('inc/theme-cleanup.php');
require_once locate_template('inc/theme-functions.php');
require_once locate_template('inc/custom-functions.php');


// set the maximum 'Large' image width to the maximum grid width
if (!isset($content_width)) { $content_width = 995; }


/* THEME SETUP */
function opcaojeans_setup() { 
    add_theme_support( 'post-thumbnails' );

    register_nav_menus( array(
        'primary-nav' => 'Menu Principal'
    ) );

    /* Remove admin bar on site */
    add_filter( 'show_admin_bar', '__return_false' );
}

add_action( 'after_setup_theme', 'opcaojeans_setup' );
/* END */


/* REGISTER SCRIPT & STYLE */
function opcaojeans_register_script_and_style() {
    if ( !is_admin() ) {
        /* jquery */
        wp_deregister_script('jquery');
        wp_register_script('jquery', '', '', '', false);

        /* scripts */
        //wp_register_script('jquery-cycle', get_bloginfo('template_directory').'/js/libs/jquery.cycle.js', array('jquery'), '1.3.2', true);
        //wp_register_script('jquery-colorbox', get_bloginfo('template_directory').'/js/libs/jquery.colorbox.js', array('jquery'), '1.3.19.3', true);

        /* styles */
        //wp_register_style('jquery-colorbox-css', get_bloginfo('template_directory').'/css/colorbox.css');
    } 
}  
add_action('init', 'opcaojeans_register_script_and_style');
/* END */

