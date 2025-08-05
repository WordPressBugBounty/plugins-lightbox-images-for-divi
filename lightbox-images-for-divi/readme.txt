=== Lightbox Images for Divi Enhanced ===
Contributors: fernandot, ayudawp
Donate Link: https://servicios.ayudawp.com
Tags: divi, lightbox, auto-linked, gallery, image
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extends Divi's native lightbox effect to all auto-linked images, not just galleries. Compatible with Divi 4.10+ and ready for Divi 5

== Description ==

The main purpose of the ‘Lightbox Images for Divi’ plugin is to extend Divi's native lightbox functionality to all auto-linked images, not just galleries

**Main features:**

*   **Extended Compatibility:** Works with the Divi or Divi Builder theme, and has been designed with compatibility in mind for Divi 4.10 and future versions, including Divi 5.
*   **Native Integration:** Use the lightbox functionality already present in Divi, ensuring seamless integration with your site's design and performance.
*   **Easy to Use:** Simply install and activate the plugin. No additional configuration is required.
*   **Optimized Performance:** The code has been refactored to follow WordPress best practices, efficiently concatenating scripts and minimizing the impact on page load.
*   **Ready for the Future:** Incorporates robust CSS/jQuery selectors and a script concatenation system that anticipates changes in the Divi 5 structure, such as the new Flexbox system.
*   **Extensible:** Includes a filter (`ayudawp_lightbox_selectors`) that allows developers to customize CSS selectors to include or exclude specific elements.

This plugin enhances the user experience on websites built with Divi, ensuring that any image linked to itself (i.e., clicking on it opens the image in its full size) is displayed in Divi's elegant native lightbox effect.

Unlike Divi's default functionality, which often restricts this effect to galleries, this plugin extends it to all individual images, providing a consistent and engaging visual experience.  

The plugin is ideal for photographers, bloggers, and any Divi user who wants to offer a more polished and professional image viewing experience without the need for complex configurations or additional gallery plugins.

== Installation ==

1. Upload the `lightbox-images-for-divi-enhanced` folder to `/wp-content/plugins/` directory.
2. Activate the plugin via the ‘Plugins’ menu in WordPress.
3. Make sure that the Divi or Divi Builder theme is active and is version 4.10 or higher.
4. That's it! All your auto-linked images will now open in a Divi lightbox.

== Frequently Asked Questions ==

= Do I need Divi for this plugin to work? =
Yes, this plugin is specifically designed to work with the Divi or Divi Builder theme. It will not work with other WordPress themes.

= Is it compatible with Divi 5? =
The plugin has been developed with compatibility with Divi 5 and its structural changes in mind. Robust selectors have been implemented and script loading has been optimized to ensure its functionality in future versions of Divi.

= How can I customize the image selectors? =
For advanced users and developers, the plugin provides a `ayudawp_lightbox_selectors` filter. You can use this filter in your child theme's `functions.php` file to add or modify the CSS selectors that the plugin uses to identify images. For example:

`php
add_filter( 'ayudawp_lightbox_selectors', 'my_custom_lightbox_selectors' );
function my_custom_lightbox_selectors( $selectors ) {
    $selectors[] = '.my-custom-module a'; // Añade un selector para un módulo personalizado
    return $selectors;
}
`

== Changelog ==

= 2.0 - 2025-08-04 =
* Complete refactoring of the code to a class structure (Singleton).
* Migration of the JavaScript script to an external file (`assets/js/lightbox-images-for-divi.js`).
* Use of `wp_enqueue_script` for more efficient script loading and following WordPress best practices.
* Optimization of CSS/jQuery selectors for greater robustness and compatibility with future versions of Divi (including Divi 5).
* Implementation of a filter (`ayudawp_lightbox_selectors`) to allow customization of selectors.
* Improved verification of compatibility with Divi and its version.
* Added uninstall hook for cleaning options.
* Updated plugin information (version, author, etc.).
* Improved internal documentation and the `readme.txt` file.

= 1.0.7 - 2024-07-18 =
* Initial version.

== Upgrade Notice ==

= 2.0 =
This is a major update with code refactoring. We recommend testing in a staging environment before updating in production. Make sure your version of Divi is 4.10 or higher.