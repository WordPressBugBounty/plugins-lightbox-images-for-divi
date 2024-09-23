<?php
/*
Plugin Name: Divi Lightbox for Images
Plugin URI: https://servicios.ayudawp.com
Description: Uses Divi's native lightbox effect to every self linked image, not only for galleries. This plugin only works with Divi theme or Divi builder installed & active.
Version: 1.0.6
Author: Fernando Tellado
Author URI: https://tellado.es
License: GPLv2
Text Domain: lightbox-images-for-divi
Domain Path: /languages/
*/

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
/**
 * Init for translations.
 *
 * @since     1.0.0
 */
function lightbox_images_for_divi_init() {
	load_plugin_textdomain( 'lightbox-images-for-divi', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'lightbox_images_for_divi_init' );
/**
 * Plugin functions
 * (New functions and scripts - Props to Divi Booster)
*/
//The new script - Needed from Divi v 4.10
function lightbox_images_for_divi_script() {
    ?>
<script data-name="dbc_links_in_lightbox">
jQuery(function($) {
		var $links = $('.entry-content a, .et_pb_post_content a').filter(db_is_image_link).not(db_is_gallery_image_link);
		$links.filter(db_has_child_img).addClass('et_pb_lightbox_image'); 
		$links.not(db_has_child_img).magnificPopup({type:'image'});
		
		function db_has_child_img() {
			return ($(this).children('img').length);
		}
		
		function db_is_image_link() {
			return (/.(?:jpg|jpeg|gif|png|webp|bmp)$/i.test($(this).attr('href')));
		}
		
		function db_is_gallery_image_link() {
			return ($(this).parent().hasClass("et_pb_gallery_image")); 
		}
	}
);
</script>
<?php
}
add_action('wp_head', 'lightbox_images_for_divi_script');
//Loading Magnific Popup Library
add_action('wp_enqueue_scripts', 'dbc_lightbox_images_register_magnific_popup', 11); // Encolar despues de 10 para evitar lanzar la deteccion en cola del tema hijo en et_divi_enqueue_stylesheet()

function dbc_lightbox_images_register_magnific_popup() {
    if (!defined('ET_BUILDER_URI') || !defined('ET_CORE_VERSION') || version_compare(ET_CORE_VERSION, '4.10', '<')) { return; }
    wp_enqueue_style('dbc-magnific-popup', ET_BUILDER_URI.'/feature/dynamic-assets/assets/css/magnific_popup.css', array(), ET_CORE_VERSION);
    wp_enqueue_script('dbc-magnific-popup', ET_BUILDER_URI.'/feature/dynamic-assets/assets/js/magnific-popup.js', array( 'jquery' ), ET_CORE_VERSION, true);
}
