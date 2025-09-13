=== Lightbox Images for Divi Enhanced ===
Contributors: fernandot, ayudawp
Tags: divi, lightbox, auto-linked, gallery, image
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extends Divi's native lightbox effect to all auto-linked images. Requires Divi Theme or Divi Builder Plugin. Compatible with Divi 4.10+ and Divi 5.

== Description ==

The main purpose of the 'Lightbox Images for Divi' plugin is to extend Divi's native lightbox functionality to all auto-linked images, not just Divi galleries.

**Requirements:**
This plugin requires **ONE** of the following to be installed and active:
* Divi Theme (version 4.10 or higher)
* Divi Builder Plugin (version 4.10 or higher)

The plugin will automatically check for these requirements during activation and will not activate if they are not met.

**Main features:**

* **Dependency Checking:** Automatically verifies Divi theme or Divi Builder plugin is active before allowing activation
* **Extended Compatibility:** Works with the Divi or Divi Builder theme, and has been designed with compatibility in mind for Divi 4.10 and future versions, including Divi 5
* **Native Integration:** Uses the lightbox functionality already present in Divi, ensuring seamless integration with your site's design and performance
* **Easy to Use:** Simply install and activate the plugin. No additional configuration is required
* **Optimized Performance:** The code has been refactored to follow WordPress best practices, efficiently concatenating scripts and minimizing the impact on page load
* **Ready for the Future:** Incorporates robust CSS/jQuery selectors and a script concatenation system that anticipates changes in the Divi 5 structure, such as the new Flexbox system
* **Extensible:** Includes a filter (`ayudawp_lightbox_selectors`) that allows developers to customize CSS selectors to include or exclude specific elements

This plugin enhances the user experience on websites built with Divi, ensuring that any image linked to itself (i.e., clicking on it opens the image in its full size) is displayed in Divi's elegant native lightbox effect.

Unlike Divi's default functionality, which often restricts this effect to galleries, this plugin extends it to all individual images, providing a consistent and engaging visual experience.  

The plugin is ideal for photographers, bloggers, and any Divi user who wants to offer a more polished and professional image viewing experience without the need for complex configurations or additional gallery plugins.

== Installation ==

1. **Important:** Make sure you have either Divi theme active OR Divi Builder plugin installed and active before proceeding
2. Upload the `lightbox-images-for-divi-enhanced` folder to `/wp-content/plugins/` directory
3. Activate the plugin via the 'Plugins' menu in WordPress
4. The plugin will automatically check for Divi compatibility and prevent activation if requirements are not met
5. That's it! All your auto-linked images will now open in a Divi lightbox

== Frequently Asked Questions ==

= What happens if I don't have Divi installed? =
The plugin will not activate and will show an error message explaining that either Divi theme or Divi Builder plugin is required.

= Can I use this plugin with other themes? =
No, this plugin is specifically designed to work only with the Divi ecosystem (Divi theme or Divi Builder plugin). It will not function with other WordPress themes.

= What happens if I deactivate Divi after installing this plugin? =
The plugin will automatically deactivate itself and show an admin notice explaining why it was deactivated.

= Is it compatible with Divi 5? =
Yes, the plugin has been developed with compatibility with Divi 5 and its structural changes in mind. Robust selectors have been implemented and script loading has been optimized to ensure functionality in future versions of Divi.

= How can I customize the image selectors? =
For advanced users and developers, the plugin provides an `ayudawp_lightbox_selectors` filter. You can use this filter in your child theme's `functions.php` file to add or modify the CSS selectors that the plugin uses to identify images. For example:

`php
add_filter( 'ayudawp_lightbox_selectors', 'my_custom_lightbox_selectors' );
function my_custom_lightbox_selectors( $selectors ) {
    $selectors[] = '.my-custom-module a'; // Add selector for custom module
    return $selectors;
}
`

= Does this plugin work with Divi Builder plugin on non-Divi themes? =
Yes, as long as you have the Divi Builder plugin installed and active, the plugin will work regardless of your active theme.

== Changelog ==

= 2.1 - 2025-09-11 =
* Improved user experience during plugin activation with friendly admin notices instead of error pages
* Enhanced dependency checking system with automatic plugin deactivation
* Added comprehensive compatibility verification during plugin activation
* Improved error messages with clear requirements explanation
* Added automatic deactivation if Divi is removed after plugin activation
* Better detection of Divi Builder plugin vs Divi theme
* Added support link in plugin action links
* Improved version detection for better compatibility checking
* Enhanced admin notices for better user experience
* Removed deprecated load_plugin_textdomain() function call

= 2.0 - 2025-08-04 =
* Complete refactoring of the code to a class structure (Singleton)
* Migration of the JavaScript script to an external file (`assets/js/lightbox-images-for-divi.js`)
* Use of `wp_enqueue_script` for more efficient script loading and following WordPress best practices
* Optimization of CSS/jQuery selectors for greater robustness and compatibility with future versions of Divi (including Divi 5)
* Implementation of a filter (`ayudawp_lightbox_selectors`) to allow customization of selectors
* Improved verification of compatibility with Divi and its version
* Added uninstall hook for cleaning options
* Updated plugin information (version, author, etc.)
* Improved internal documentation and the `readme.txt` file

= 1.0.7 - 2024-07-18 =
* Initial version

== Upgrade Notice ==

= 2.1 =
This update improves the activation experience with user-friendly admin notices and enhanced dependency checking. No breaking changes.

= 2.0 =
This is a major update with code refactoring. We recommend testing in a staging environment before updating in production. Make sure your version of Divi is 4.10 or higher.