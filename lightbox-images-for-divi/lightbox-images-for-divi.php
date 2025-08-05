<?php
/*
Plugin Name: Lightbox Images for Divi Enhanced
Plugin URI: https://servicios.ayudawp.com
Description: Apply Divi's native lightbox effect to all auto-linked images, not just galleries. Compatible with Divi 4.10+ and ready for Divi 5. This plugin only works with the Divi theme or Divi Builder installed and active.
Version: 2.0
Author: Fernando Tellado
Author URI: https://ayudawp.com
License: GPLv2
Text Domain: lightbox-images-for-divi
Domain Path: /languages/
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
*/

// Impedir acceso directo al plugin
defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

/**
 * Clase principal del plugin
 * 
 * @since 2.0
 */
class AyudaWP_Lightbox_Images_For_Divi {
    
    /**
     * Versión del plugin
     */
    const VERSION = '2.0';
    
    /**
     * Instancia única del plugin (Singleton)
     */
    private static $instance = null;
    
    /**
     * Constructor privado para implementar Singleton
     */
    private function __construct() {
        $this->init();
    }
    
    /**
     * Obtener la instancia única del plugin
     * 
     * @return AyudaWP_Lightbox_Images_For_Divi
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Inicializar el plugin
     * 
     * @since 2.0
     */
    private function init() {
        // Cargar traducciones
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        
        // Encolar scripts y estilos
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        
        // Hook para verificar compatibilidad con Divi
        add_action( 'admin_notices', array( $this, 'check_divi_compatibility' ) );
        
        // Añadir filtro para personalizar selectores (para desarrolladores)
        add_filter( 'ayudawp_lightbox_selectors', array( $this, 'default_selectors' ) );
    }
    
    /**
     * Cargar archivos de traducción
     * 
     * @since 2.0
     */
    public function load_textdomain() {
        load_plugin_textdomain( 
            'lightbox-images-for-divi', 
            false, 
            dirname( plugin_basename( __FILE__ ) ) . '/languages' 
        );
    }
    
    /**
     * Verificar compatibilidad con Divi
     * 
     * @since 2.0
     */
    public function check_divi_compatibility() {
        // Verificar si Divi está activo
        if ( ! $this->is_divi_active() ) {
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p>' . esc_html__( 'Lightbox Images for Divi Enhanced requires the Divi theme or Divi Builder plugin to be installed and active.', 'lightbox-images-for-divi' ) . '</p>';
            echo '</div>';
            return;
        }
        
        // Verificar versión de Divi
        if ( defined( 'ET_CORE_VERSION' ) && version_compare( ET_CORE_VERSION, '4.10', '<' ) ) {
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p>' . sprintf( 
                /* translators: %s stands for currently installed Divi version */
                esc_html__( 'Lightbox Images for Divi Enhanced requires at least Divi v4.10. Current version: %s', 'lightbox-images-for-divi' ), 
                esc_html( ET_CORE_VERSION ) 
            ) . '</p>';
            echo '</div>';
        }
    }
    
    /**
     * Verificar si Divi está activo
     * 
     * @return bool
     * @since 2.0
     */
    private function is_divi_active() {
        // Verificar si es el tema Divi
        $theme = wp_get_theme();
        if ( 'Divi' === $theme->get( 'Name' ) || 'Divi' === $theme->get_template() ) {
            return true;
        }
        
        // Verificar si Divi Builder está activo como plugin
        if ( defined( 'ET_BUILDER_VERSION' ) ) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Encolar scripts y estilos necesarios
     * 
     * @since 2.0
     */
    public function enqueue_scripts() {
        // Solo cargar si Divi está activo y es la versión correcta
        if ( ! $this->is_divi_active() || ! defined( 'ET_CORE_VERSION' ) || version_compare( ET_CORE_VERSION, '4.10', '<' ) ) {
            return;
        }
        
        // Encolar el script principal del plugin
        wp_enqueue_script(
            'ayudawp-lightbox-images-for-divi',
            plugin_dir_url( __FILE__ ) . 'assets/js/lightbox-images-for-divi.js',
            array( 'jquery' ),
            self::VERSION,
            true
        );
        
            // Pasar datos al script JavaScript
        $selectors = apply_filters( 'ayudawp_lightbox_selectors', $this->default_selectors() );
        wp_localize_script(
            'ayudawp-lightbox-images-for-divi',
            'ayudawpLightboxData',
            array(
                'selectors' => $selectors,
                'imageExtensions' => array( 'jpg', 'jpeg', 'gif', 'png', 'webp', 'bmp', 'svg' ),
                'debug' => defined( 'WP_DEBUG' ) && WP_DEBUG
            )
        );
        
        // Encolar Magnific Popup solo si es necesario
        $this->maybe_enqueue_magnific_popup();
    }
    
    /**
     * Selectores CSS por defecto para buscar enlaces de imagen
     * 
     * @return array
     * @since 2.0
     */
    public function default_selectors() {
        return array(
            '.entry-content a',
            '.et_pb_post_content a',
            '.et_pb_text_inner a',
            '.et_pb_blurb_content a',
            '.et_pb_module a'
        );
    }
    
    /**
     * Encolar Magnific Popup condicionalmente
     * 
     * @since 2.0
     */
    private function maybe_enqueue_magnific_popup() {
        // Verificar si Magnific Popup ya está encolado por Divi
        global $wp_scripts;
        $magnific_enqueued = false;
        
        if ( isset( $wp_scripts->registered ) ) {
            foreach ( $wp_scripts->registered as $handle => $script ) {
                if ( strpos( $script->src, 'magnific' ) !== false || strpos( $handle, 'magnific' ) !== false ) {
                    $magnific_enqueued = true;
                    break;
                }
            }
        }
        
        // Si no está encolado y tenemos acceso a los assets de Divi, encolarlos
        if ( ! $magnific_enqueued && defined( 'ET_BUILDER_URI' ) ) {
            wp_enqueue_style(
                'ayudawp-magnific-popup',
                ET_BUILDER_URI . '/feature/dynamic-assets/assets/css/magnific_popup.css',
                array(),
                ET_CORE_VERSION
            );
            
            wp_enqueue_script(
                'ayudawp-magnific-popup',
                ET_BUILDER_URI . '/feature/dynamic-assets/assets/js/magnific-popup.js',
                array( 'jquery' ),
                ET_CORE_VERSION,
                true
            );
        }
    }
    
    /**
     * Método para desinstalar el plugin
     * 
     * @since 2.0
     */
    public static function uninstall() {
        // Limpiar opciones si las hubiera en el futuro
        delete_option( 'ayudawp_lightbox_images_for_divi_options' );
        
        // Limpiar caché si es necesario
        if ( function_exists( 'wp_cache_flush' ) ) {
            wp_cache_flush();
        }
    }
}

// Inicializar el plugin
AyudaWP_Lightbox_Images_For_Divi::get_instance();

// Hook para desinstalación
register_uninstall_hook( __FILE__, array( 'AyudaWP_Lightbox_Images_For_Divi', 'uninstall' ) );