<?php
/*
Plugin Name: Lightbox Images for Divi Enhanced
Plugin URI: https://servicios.ayudawp.com
Description: Apply Divi's native lightbox effect to all auto-linked images, not just galleries. Compatible with Divi 4.10+ and Divi 5. This plugin only works with the Divi theme or Divi Builder installed and active.
Version: 2.2.2
Author: Fernando Tellado
Author URI: https://ayudawp.com
License: GPLv2
Text Domain: lightbox-images-for-divi
Requires at least: 5.0
Tested up to: 7.0
Requires PHP: 7.4
*/

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

/**
 * Main plugin class
 *
 * @since 2.0
 */
class AyudaWP_Lightbox_Images_For_Divi {

    /**
     * Plugin version
     */
    const VERSION = '2.2.2';

    /**
     * Minimum required Divi version
     */
    const MIN_DIVI_VERSION = '4.10';

    /**
     * Unique plugin instance (Singleton)
     */
    private static $instance = null;

    /**
     * Private constructor to implement Singleton
     */
    private function __construct() {
        $this->init();
    }

    /**
     * Get the unique plugin instance
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
     * Initialize the plugin
     *
     * @since 2.0
     */
    private function init() {
        // Check compatibility on plugin activation
        register_activation_hook( __FILE__, array( $this, 'activate_plugin' ) );

        // Check if dependencies are still active on every admin page load
        add_action( 'admin_init', array( $this, 'check_dependencies' ) );

        // Show activation error notices
        add_action( 'admin_notices', array( $this, 'show_activation_notices' ) );

        // Enqueue scripts and styles only if dependencies are met
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // Add filter to customize selectors (for developers)
        add_filter( 'ayudawp_lightbox_selectors', array( $this, 'default_selectors' ) );

        // Add action links to plugin page
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_action_links' ) );
    }

    /**
     * Plugin activation hook
     * Checks if Divi is available before allowing activation
     *
     * @since 2.1
     */
    public function activate_plugin() {
        if ( ! $this->is_divi_available() ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            set_transient( 'ayudawp_lightbox_activation_error', 'missing_divi', 30 );
            return;
        }

        if ( $this->is_divi_available() && ! $this->is_divi_version_compatible() ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            set_transient( 'ayudawp_lightbox_activation_error', 'incompatible_version', 30 );
            return;
        }
    }

    /**
     * Check if dependencies are still active
     * Deactivates plugin if Divi is no longer available
     *
     * @since 2.1
     */
    public function check_dependencies() {
        if ( ! $this->is_divi_available() ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );

            add_action( 'admin_notices', function () {
                echo '<div class="notice notice-error is-dismissible">';
                echo '<p><strong>' . esc_html__( 'Lightbox Images for Divi Enhanced has been deactivated', 'lightbox-images-for-divi' ) . '</strong></p>';
                echo '<p>' . esc_html__( 'This plugin requires either Divi theme or Divi Builder plugin to be active.', 'lightbox-images-for-divi' ) . '</p>';
                echo '</div>';
            } );
        }
    }

    /**
     * Show activation error notices
     *
     * @since 2.1
     */
    public function show_activation_notices() {
        $error = get_transient( 'ayudawp_lightbox_activation_error' );

        if ( ! $error ) {
            return;
        }

        delete_transient( 'ayudawp_lightbox_activation_error' );

        if ( 'missing_divi' === $error ) {
            echo '<div class="notice notice-error is-dismissible">';
            echo '<p><strong>' . esc_html__( 'Lightbox Images for Divi Enhanced could not be activated', 'lightbox-images-for-divi' ) . '</strong></p>';
            echo '<p>' . esc_html__( 'This plugin requires either Divi Theme (active) OR Divi Builder Plugin (installed and active).', 'lightbox-images-for-divi' ) . '</p>';
            echo '</div>';
        } elseif ( 'incompatible_version' === $error ) {
            echo '<div class="notice notice-error is-dismissible">';
            echo '<p><strong>' . esc_html__( 'Lightbox Images for Divi Enhanced could not be activated', 'lightbox-images-for-divi' ) . '</strong></p>';
            echo '<p>' . sprintf(
                /* translators: %1$s: Required Divi version, %2$s: Current Divi version */
                esc_html__( 'This plugin requires Divi version %1$s or higher. Current version: %2$s', 'lightbox-images-for-divi' ),
                esc_html( self::MIN_DIVI_VERSION ),
                esc_html( $this->get_divi_version() )
            ) . '</p>';
            echo '</div>';
        }
    }

    /**
     * Check if Divi is available (theme or plugin)
     *
     * @return bool
     * @since 2.1
     */
    private function is_divi_available() {
        $theme = wp_get_theme();
        if ( 'Divi' === $theme->get( 'Name' ) || 'Divi' === $theme->get_template() ) {
            return true;
        }

        if ( is_plugin_active( 'divi-builder/divi-builder.php' ) ) {
            return true;
        }

        if ( defined( 'ET_BUILDER_VERSION' ) ) {
            return true;
        }

        return false;
    }

    /**
     * Check if Divi version is compatible
     *
     * @return bool
     * @since 2.1
     */
    private function is_divi_version_compatible() {
        $divi_version = $this->get_divi_version();

        if ( empty( $divi_version ) ) {
            return false;
        }

        return version_compare( $divi_version, self::MIN_DIVI_VERSION, '>=' );
    }

    /**
     * Get current Divi version
     *
     * @return string
     * @since 2.1
     */
    private function get_divi_version() {
        if ( defined( 'ET_CORE_VERSION' ) ) {
            return ET_CORE_VERSION;
        }

        if ( defined( 'ET_BUILDER_VERSION' ) ) {
            return ET_BUILDER_VERSION;
        }

        $theme = wp_get_theme();
        if ( 'Divi' === $theme->get( 'Name' ) || 'Divi' === $theme->get_template() ) {
            return $theme->get( 'Version' );
        }

        return '';
    }

    /**
     * Enqueue scripts and styles on the frontend
     *
     * @since 2.0
     */
    public function enqueue_scripts() {
        if ( ! $this->is_divi_available() || ! $this->is_divi_version_compatible() ) {
            return;
        }

        // Build script dependencies: use jQuery if available, but don't require it
        $dependencies = array();
        if ( wp_script_is( 'jquery', 'registered' ) ) {
            $dependencies[] = 'jquery';
        }

        // Enqueue main plugin script (works with or without jQuery)
        wp_enqueue_script(
            'ayudawp-lightbox-images-for-divi',
            plugin_dir_url( __FILE__ ) . 'assets/js/lightbox-images-for-divi.js',
            $dependencies,
            self::VERSION,
            true
        );

        // Enqueue fallback lightbox styles
        wp_enqueue_style(
            'ayudawp-lightbox-images-for-divi',
            plugin_dir_url( __FILE__ ) . 'assets/css/lightbox-images-for-divi.css',
            array(),
            self::VERSION
        );

        // Pass configuration data to JavaScript
        $selectors = apply_filters( 'ayudawp_lightbox_selectors', $this->default_selectors() );

        wp_localize_script(
            'ayudawp-lightbox-images-for-divi',
            'ayudawpLightboxData',
            array(
                'selectors'       => $selectors,
                'imageExtensions' => array( 'jpg', 'jpeg', 'gif', 'png', 'webp', 'bmp', 'svg' ),
                'debug'           => defined( 'WP_DEBUG' ) && WP_DEBUG,
            )
        );

        // Try to make Magnific Popup available (best experience with Divi)
        $this->maybe_enqueue_magnific_popup();
    }

    /**
     * Default CSS selectors for image links
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
        );
    }

    /**
     * Try to enqueue Magnific Popup from Divi assets.
     * Not critical: the JS fallback handles the lightbox when this is absent.
     *
     * @since 2.0
     */
    private function maybe_enqueue_magnific_popup() {
        global $wp_scripts;

        // Check if Magnific Popup is already enqueued by Divi
        if ( isset( $wp_scripts->registered ) ) {
            foreach ( $wp_scripts->registered as $handle => $script ) {
                if ( false !== strpos( $script->src, 'magnific' ) || false !== strpos( $handle, 'magnific' ) ) {
                    return; // Already enqueued
                }
            }
        }

        // Try to enqueue from Divi's bundled assets
        if ( ! defined( 'ET_BUILDER_URI' ) ) {
            return;
        }

        $css_path = ET_BUILDER_URI . '/feature/dynamic-assets/assets/css/magnific_popup.css';
        $js_path  = ET_BUILDER_URI . '/feature/dynamic-assets/assets/js/magnific-popup.js';

        wp_enqueue_style(
            'ayudawp-magnific-popup',
            $css_path,
            array(),
            $this->get_divi_version()
        );

        $mp_deps = array();
        if ( wp_script_is( 'jquery', 'registered' ) ) {
            $mp_deps[] = 'jquery';
        }

        wp_enqueue_script(
            'ayudawp-magnific-popup',
            $js_path,
            $mp_deps,
            $this->get_divi_version(),
            true
        );
    }

    /**
     * Add action links to plugin page
     *
     * @param array $links Existing action links.
     * @return array
     * @since 2.1
     */
    public function add_action_links( $links ) {
        $plugin_links = array(
            '<a href="https://servicios.ayudawp.com" target="_blank">' . esc_html__( 'Support', 'lightbox-images-for-divi' ) . '</a>',
        );

        return array_merge( $plugin_links, $links );
    }

    /**
     * Plugin uninstall method
     *
     * @since 2.0
     */
    public static function uninstall() {
        delete_option( 'ayudawp_lightbox_images_for_divi_options' );
        delete_transient( 'ayudawp_lightbox_activation_error' );

        if ( function_exists( 'wp_cache_flush' ) ) {
            wp_cache_flush();
        }
    }
}

// Initialize plugin
if ( defined( 'ABSPATH' ) ) {
    AyudaWP_Lightbox_Images_For_Divi::get_instance();
}

// Uninstall hook
register_uninstall_hook( __FILE__, array( 'AyudaWP_Lightbox_Images_For_Divi', 'uninstall' ) );