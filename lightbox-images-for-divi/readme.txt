=== Lightbox Images for Divi Enhanced ===
Contributors: fernandot, ayudawp
Tags: divi, lightbox, image, gallery, divi5
Requires at least: 5.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 2.2.5
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

* **Full Divi 5 Support:** Works with Divi 5 pages, including backwards compatibility mode. No jQuery required — the plugin works in vanilla JavaScript with an elegant built-in lightbox when Magnific Popup is not available
* **Attachment Page Links:** Now handles images linked to WordPress attachment pages (not just direct image URLs), resolving the full-size image automatically
* **Dependency Checking:** Automatically verifies Divi theme or Divi Builder plugin is active before allowing activation
* **Extended Compatibility:** Works with the Divi or Divi Builder theme, compatible with Divi 4.10+ and Divi 5
* **Native Integration:** Uses Divi's Magnific Popup when available, falls back to a lightweight built-in lightbox otherwise
* **Easy to Use:** Simply install and activate the plugin. No additional configuration is required
* **Optimized Performance:** Vanilla JavaScript with no hard dependencies. CSS and JS loaded conditionally
* **Ready for the Future:** Works in both Divi 4 (jQuery/Magnific Popup) and Divi 5 (vanilla JS fallback) environments
* **Extensible:** Includes a filter (`ayudawp_lightbox_selectors`) that allows developers to customize CSS selectors to include or exclude specific elements

This plugin enhances the user experience on websites built with Divi, ensuring that any image linked to itself (i.e., clicking on it opens the image in its full size) is displayed in a lightbox effect.

Unlike Divi's default functionality, which often restricts this effect to galleries, this plugin extends it to all individual images, providing a consistent and engaging visual experience.  

The plugin is ideal for photographers, bloggers, and any Divi user who wants to offer a more polished and professional image viewing experience without the need for complex configurations or additional gallery plugins.

== Installation ==

1. **Important:** Make sure you have either Divi theme active OR Divi Builder plugin installed and active before proceeding
2. Upload the `lightbox-images-for-divi-enhanced` folder to `/wp-content/plugins/` directory
3. Activate the plugin via the 'Plugins' menu in WordPress
4. The plugin will automatically check for Divi compatibility and prevent activation if requirements are not met
5. That's it! All your auto-linked images will now open in a lightbox

== Frequently Asked Questions ==

= What happens if I don't have Divi installed? =
The plugin will not activate and will show an error message explaining that either Divi theme or Divi Builder plugin is required.

= Can I use this plugin with other themes? =
No, this plugin is specifically designed to work only with the Divi ecosystem (Divi theme or Divi Builder plugin). It will not function with other WordPress themes.

= What happens if I deactivate Divi after installing this plugin? =
The plugin will automatically deactivate itself and show an admin notice explaining why it was deactivated.

= Is it compatible with Divi 5? =
Yes, the plugin fully supports Divi 5. It works both in backwards compatibility mode (using Magnific Popup) and in native Divi 5 pages (using a lightweight built-in lightbox). No jQuery is required.

= Does it work with images linked to attachment pages? =
Yes, since version 2.2.0 the plugin handles both direct image URLs (e.g. photo.jpg) and WordPress attachment page links. The full-size image is resolved automatically from the embedded image element.

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

= 2.2.5 =
* Fixed: Linked images (e.g. button-style images linking to other pages) were being hijacked by the lightbox since 2.2.0, overriding the original `href`
* Improved: Attachment-page detection now requires the link to be same-origin, not open in a new window, and either carry an `attachment_id` query parameter or have a path slug that matches the image file name
* Plain external or internal links wrapping an `<img>` are now left untouched, restoring the original click behavior
* Changed: Version history moved to a dedicated changelog.txt file served from the plugin's public SVN, keeping readme.txt focused on the current release

= 2.2.4 =
* Fixed 404 error in browser console on Divi 5 for `magnific-popup.js` and `magnific_popup.css`
* The plugin now verifies the bundled Magnific Popup files actually exist on disk before enqueuing them, and cleanly falls back to the built-in vanilla JS lightbox when they are not available
* Enqueue hook moved to priority 20 so Magnific Popup detection correctly sees scripts already registered by Divi
* Hardened script detection loop against non-string values (prevents potential PHP 8+ warnings)

= 2.2.3 =
* Fixed lightbox not working on images in the WordPress classic/block editor post content field when rendered by Divi's Post Content module
* The Post Content module container (`.et_pb_post_content`) is now correctly recognized as an allowed content area

For older changelog entries, please check the [changelog.txt](https://plugins.svn.wordpress.org/lightbox-images-for-divi/trunk/changelog.txt) file

== Upgrade Notice ==

= 2.2.5 =
Fixes a regression introduced in 2.2.0 where images wrapped in regular links (buttons, CTAs) were being captured by the lightbox, overriding the original link. Recommended for all users.

== Support ==

= Need help or have suggestions? =
* [Official website](https://servicios.ayudawp.com/)
* [WordPress support forum](https://wordpress.org/support/plugin/lightbox-images-for-divi/)
* [YouTube channel](https://www.youtube.com/AyudaWordPressES)
* [Documentation and tutorials](https://ayudawp.com/)

**Love the plugin?** Please leave us a 5-star review and help spread the word!

== About AyudaWP ==

We are specialists in WordPress security, SEO, and performance optimization plugins. We create tools that solve real problems for WordPress site owners while maintaining the highest coding standards and accessibility requirements.