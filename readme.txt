=== SnipZine Slider ===
Contributors: snipzine
Tags: slider, slideshow, jquery, responsiveslidesjs
Requires at least: 3.5
Tested up to: 3.8.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create multiple slideshows, each with its own settings, only in a few clicks.

== Description ==

Using this plugin, you can create multiple slideshows, for use on different part of your website by using the [sz_slider] shortcode.
You can choose from different design themes, or use the default one and extend it yourself.
Every slideshow can be customized.
You can use the default image sizes your theme has, or create new ones from "Options" page in administration area.
At the moment, the plugin only use ResponsiveSlides.js for creating the slideshows.

== Installation ==

1. Upload the contents of `SnipZineSlider` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Now a new menu appeared, 'SnipZine Slider', in which you can view/add/edit slideshows or alter some common options.
4. After you created a slideshow, you can include it in your site by putting the [sz_slider slideshow="ID"] into your page(s).
5. If you want to programatically include a slideshow in your theme, you can use the following syntax: <?php echo do_shortcode('[sz_slider slideshow="ID"]'); ?>

== Frequently Asked Questions ==

= How do I add a new slideshow =

In the plugin administration area, which you can find in your website administration menu, is a button called "Add new slideshow". Clicking on it will open a modal in which you can enter the title of your new slideshow. After you enter the title and click "Add slideshow", page will reload and you will be able to see the new slideshow in the slideshows list.

= How do I add new slides to a slideshow =

Clicking on the slideshow name in the table present on plugin administration area will open the editing slideshow page. Here, you can add new slides by clicking "Add slides" button. A media uploader modal will appear, where you can select multiple images to add, or drag them from your workstation onto this area. After images are uploaded, upon clicking "Done" the page will refresh and you will be able to see the new slides.

= How do I order the slides in a slideshow =

You can order the slides by drag&dropping the table rows on slideshow edit page. When you click the "Save" button in the "Slideshow Settings" box the new order will be saved.

== Screenshots ==

1. Clean theme
2. Clean-Invert theme