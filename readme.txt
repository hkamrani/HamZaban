=== Hamzaban ===
Contributors: ertano, mihanwp
Donate link: https://ertano.com
Tags: hreflang, multilingual, SEO, language, meta tags
Requires at least: 5.0
Tested up to: 6.6
Stable tag: 1.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Hamzaban Hreflang Tag Manager helps you manage hreflang tags for your multilingual WordPress site.

== Description ==

Hamzaban is a plugin that helps you add and manage hreflang tags for your WordPress site. Hreflang tags are essential for SEO, especially for multilingual sites, as they tell search engines which language and regional URLs to serve to users based on their location and language preferences.

= Features =
* Add hreflang tags to posts, pages, and custom post types.
* Manage hreflang tags through a simple meta box in the post editor.
* Display hreflang links in your theme's navigation menu.
* Option to enable or disable the display of hreflang links in the navigation menu through the plugin settings.

= Usage =
1. Activate the plugin through the 'Plugins' menu in WordPress.
2. Go to the post or page editor, and you will see a meta box to add hreflang URLs and languages.
3. Done! hreflang metatag will be added to your website pages head.
4. You can use the provided function or template tag to display the hreflang links in your theme.

= Display Hreflang Links in Theme =
To display the `en` language URL in your theme, use the following code:

```php
<?php
if ($hreflang = array_filter(get_post_meta(get_the_ID(), '_hamzaban_post_langs', true), function($lang) { return $lang['lang'] === 'en'; })) {
    $hreflang = reset($hreflang); // Get the first match
    echo '<a href="' . esc_url($hreflang['url']) . '" hreflang="en">' . __('English Version', 'hamzaban') . '</a>';
}
?>
