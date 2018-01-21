<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'font-awesome','simple-line-icons','magnific-popup','oceanwp-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css' );

// END ENQUEUE PARENT ACTION


/**
 * Add the OceanWP Settings metabox in your Custom Post Type
 */
function feg_oceanwp_metabox( $types ) {
  // Your custom post type
  $types[] = 'wpfc_preacher';
  $types[] = 'wpfc_sermon_series';
  $types[] = 'wpfc_sermon_topics';
  $types[] = 'wpfc_bible_book';
  $types[] = 'wpfc_service_type';
  return $types;
}
add_filter( 'ocean_main_metaboxes_post_types', 'feg_oceanwp_metabox', 20 );

