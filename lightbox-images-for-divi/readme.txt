=== Divi Lightbox for Images ===
Contributors: fernandot
Donate link: https://www.paypal.me/fernandotellado
Tags: divi, builder, composer, lightbox, modal, popup
Requires at least: 4.0
Tested up to: 6.5.2
Stable tag: trunk
License: GPLv2+
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Divi Lightbox for self linked individual images


== Description ==

If Divi Gallery setting is active in Divi Options menu, Divi Theme & Builder use a native Lightbox script to open a modal window when a user click on an image from a gallery. But this script isn't available for individual self linked images.

This plugin use Divi's Native Lightbox also for individual self linked images. The only requisite is to have Divi Theme (or builder) installed and active and, of course, Divi Gallery option active in Divi Options menu.

The purpose of this plugin is to avoid installation of another plugin to achieve the Lightbox effect also for individual images. The goal is to obtain a better performance, reducing the need of installing more plugins instead of use of already installed, and misused, scripts. 

The plugin Divi Lightbox for self linked Individual Images loads the Lightbox script in footer for better optimization and performance.

This plugin only works with Divi Theme or Divi Builder plugin installed & active:

- [Divi by Elegant Themes](https://www.elegantthemes.com/affiliates/idevaffiliate.php?id=3181&url=42890) (yes, this is an affiliate link!)

<strong>No options</strong>, just activate the plugin and it’s done.

== Installation ==

1. Go to your WP Dashboard > Plugins and search for ‘lightbox divi’ or…
2. Download the plugin from WP repository.
3. Upload the plugin folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Does this plugin works with Divi Builder plugin? =

Yes, this plugin works with Divi Theme and with Divi standalone builder plugin too.

= Does this plugin affect to my site performance? =

No. The purpose is just the opposite. With this plugin You will not need any other plugin to get Lightbox effect for your self linked individual images. This plugin make use of the existing script in Divi core builder.

= The plugin doesn't works with WP Rocket plugin enabled =

You must add some exclusions in the 'Delay JavaScript Execution' section. These ones:
<code>dbc_links_in_lightbox
/feature/dynamic-assets/assets/css/magnific_popup.css
/feature/dynamic-assets/assets/js/magnific-popup.js</code>

== Screenshots ==

1. Divi Lightbox active in an individual image.

== Changelog ==
= 1.0.5 =
* Tested with Divi 4.24.2s
* Tested with WordPress 6.5.2     

= 1.0.5 =
* Tested with Divi 4.23
* Tested with WordPress 6.4

= 1.0.4 =
* Tested with Divi 4.20.2
* Tested with WordPress 6.2

= 1.0.3 =
* Tested with Divi 4.18.0
* Tested with WordPress 6.1

= 1.0.1 =
* Tested with Divi 4.18.0
* Tested with WordPress 6.0.2

= 1.0.1 =
* Tested with Divi 4.17.4
* Tested with WordPress 6.0

= 1.0 =
* Plugin refactored in order to work with Divi latest versions
* Tested with Divi 4.14.8
* Tested with WordPress 5.9.1

= 0.9.17 =
* Tested with Divi 4.14.7
* Teste with WordPress 5.9
= 0.9.16 =
* Tested with Divi 4.9.9
* Teste with WordPress 5.8
= 0.9.15 =
* Tested with Divi 4.7.4
* Teste with WordPress 5.6
= 0.9.14 =
* Tested with Divi 4.6.1
* Teste with WordPress 5.5.1
= 0.9.13 =
* Tested with Divi 4.6
* Changed readme link according the plugin directory guidelines
= 0.9.12 =
* Tested up to WordPress 5.5
= 0.9.11 =
* Tested up to WordPress 5.4.1
* Widgets support removed due to several issues

= 0.9.10 =
* Tested up to WordPress 5.4
* Now it works with widgets too (Thanks to @akg-free)

= 0.9.9 =
* Tested up to WordPress 5.3.2

= 0.9.8 =
* Solved issue with Divi galleries

= 0.9.7 =
* Tested up and working with WordPress 5.3

= 0.9.6 =
* Tested up to WordPress 5.2.2

= 0.9.5 =
* Tested up to WordPress 5.2

= 0.9.4 =
* Tested up to WordPress 5.1

= 0.9.3 =
* Tested up to WordPress 5.0 tag added

= 0.9.2 =
* Tested up to WordPress 4.9.6 and Divi latest updates

= 0.9.1 =
* Tested up to WordPress 4.9.5 and Divi latest updates

= 0.9 =
* Initial release
