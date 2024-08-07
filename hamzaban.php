<?php
/*
Plugin Name: Hamzaban
Description: Hamzaban Hreflang Tag Manager
Author: Ertano
Version: 1.0
Author URI: https://ertano.com
Plugin URI: https://ertano.com/hamzaban/
Text Domain: hamzaban
Domain Path: /languages
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function hamzaban_load_textdomain() {
    load_plugin_textdomain('hamzaban', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'hamzaban_load_textdomain');

function hamzaban_lang_meta_box() {
    $screens = ['post', 'page'];
    foreach ($screens as $screen) {
        add_meta_box(
            'hamzaban_post_lang',
            __('Languages', 'hamzaban'),
            'hamzaban_lang_meta_box_html',
            $screen,
            'side'
        );
    }
}
add_action('add_meta_boxes', 'hamzaban_lang_meta_box');

function hamzaban_lang_meta_box_html($post) {
    wp_nonce_field('hamzaban_save_meta_box_data', 'hamzaban_meta_box_nonce');
    
    $langs = [
        'en' => 'English',
        'fa' => 'فارسی',
        'es' => 'Español',
        'fr' => 'Français',
        'de' => 'Deutsch',
        'it' => 'Italiano',
        'ru' => 'Русский',
        'zh' => '中文',
        'ja' => '日本語',
        'ko' => '한국어',
        'ar' => 'العربية',
        'pt' => 'Português',
        'hi' => 'हिन्दी',
        'bn' => 'বাংলা',
        'ur' => 'اردو',
        'tr' => 'Türkçe',
        'vi' => 'Tiếng Việt',
        'nl' => 'Nederlands',
        'sv' => 'Svenska',
        'no' => 'Norsk',
        'da' => 'Dansk',
        'fi' => 'Suomi',
        'el' => 'Ελληνικά',
        'he' => 'עברית',
        'id' => 'Bahasa Indonesia',
        'ms' => 'Bahasa Melayu',
        'pl' => 'Polski',
        'ro' => 'Română',
        'th' => 'ไทย',
        'cs' => 'Čeština',
        'hu' => 'Magyar',
        'uk' => 'Українська'
    ];
    $hreflangs = get_post_meta($post->ID, '_hamzaban_post_langs', true) ?: [];
    $default_lang = get_option('hamzaban_default_lang', 'en');
    ?>
    <div id="hamzaban-hreflang-wrapper">
        <?php foreach ($hreflangs as $hreflang): ?>
            <div class="hamzaban-hreflang-row">
                <label for="hamzaban_post_lang_href_meta"><?php esc_html_e('Hreflang URL', 'hamzaban'); ?></label>
                <input name="hamzaban_post_lang_href_meta[]" class="components-text-control__input" value="<?php echo esc_attr($hreflang['url']); ?>" placeholder="<?php esc_attr_e('Input your hreflang URL', 'hamzaban'); ?>"/>
                <label for="hamzaban_post_lang"><?php esc_html_e('Language', 'hamzaban'); ?></label>
                <select name="hamzaban_post_lang[]">
                    <?php foreach ($langs as $code => $name): ?>
                        <option value="<?php echo esc_attr($code); ?>" <?php selected($code, $hreflang['lang']); ?>><?php echo esc_html($name); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="hamzaban-remove-hreflang"><?php esc_html_e('Remove', 'hamzaban'); ?></button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="hamzaban-add-hreflang"><?php esc_html_e('Add Hreflang', 'hamzaban'); ?></button>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let defaultLang = '<?php echo esc_js($default_lang); ?>';
            
            document.getElementById('hamzaban-add-hreflang').addEventListener('click', function () {
                let wrapper = document.getElementById('hamzaban-hreflang-wrapper');
                let newRow = document.createElement('div');
                newRow.classList.add('hamzaban-hreflang-row');
                newRow.innerHTML = `
                    <label for="hamzaban_post_lang_href_meta"><?php esc_html_e('Hreflang URL', 'hamzaban'); ?></label>
                    <input name="hamzaban_post_lang_href_meta[]" class="components-text-control__input" value="" placeholder="<?php esc_attr_e('Input your hreflang URL', 'hamzaban'); ?>"/>
                    <label for="hamzaban_post_lang"><?php esc_html_e('Language', 'hamzaban'); ?></label>
                    <select name="hamzaban_post_lang[]">
                        <?php foreach ($langs as $code => $name): ?>
                            <option value="<?php echo esc_attr($code); ?>" <?php echo $code === $default_lang ? 'selected' : ''; ?>><?php echo esc_html($name); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="hamzaban-remove-hreflang"><?php esc_html_e('Remove', 'hamzaban'); ?></button>
                `;
                wrapper.appendChild(newRow);
            });

            document.getElementById('hamzaban-hreflang-wrapper').addEventListener('click', function (e) {
                if (e.target.classList.contains('hamzaban-remove-hreflang')) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
    <style>
        .hamzaban-hreflang-row {
            margin-bottom: 10px;
        }
        .hamzaban-hreflang-row label {
            display: block;
            margin-top: 5px;
        }
        .hamzaban-hreflang-row select, .hamzaban-hreflang-row input {
            width: 100%;
            margin-top: 5px;
        }
        .hamzaban-remove-hreflang {
            margin-top: 10px;
            background: #dc3232;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        #hamzaban-add-hreflang {
            background: #0073aa;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
    <?php
}

function hamzaban_lang_meta_box_save_post($post_id) {
    if (!isset($_POST['hamzaban_meta_box_nonce']) || !wp_verify_nonce($_POST['hamzaban_meta_box_nonce'], 'hamzaban_save_meta_box_data')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (array_key_exists('hamzaban_post_lang_href_meta', $_POST) && array_key_exists('hamzaban_post_lang', $_POST)) {
        $hreflangs = [];
        $urls = $_POST['hamzaban_post_lang_href_meta'];
        $langs = $_POST['hamzaban_post_lang'];
        for ($i = 0; $i < count($urls); $i++) {
            if (!empty($urls[$i]) && !empty($langs[$i])) {
                $hreflangs[] = [
                    'url' => sanitize_text_field($urls[$i]),
                    'lang' => sanitize_text_field($langs[$i])
                ];
            }
        }
        update_post_meta($post_id, '_hamzaban_post_langs', $hreflangs);
    }
}
add_action('save_post', 'hamzaban_lang_meta_box_save_post');

function hamzaban_add_hreflang_tag() {
    if (is_singular()) {
        global $post;
        $hreflangs = get_post_meta($post->ID, '_hamzaban_post_langs', true) ?: [];
        foreach ($hreflangs as $hreflang) {
            if (!empty($hreflang['url']) && !empty($hreflang['lang'])) {
                echo '<link rel="alternate" href="' . esc_url($hreflang['url']) . '" hreflang="' . esc_attr($hreflang['lang']) . '" />';
            }
        }
    }
}
add_action('wp_head', 'hamzaban_add_hreflang_tag');

function hamzaban_settings_menu() {
    add_options_page(
        __('Hamzaban Settings', 'hamzaban'),
        __('Hamzaban', 'hamzaban'),
        'manage_options',
        'hamzaban',
        'hamzaban_settings_page_html'
    );
}
add_action('admin_menu', 'hamzaban_settings_menu');

function hamzaban_settings_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['hamzaban_default_lang_nonce']) && wp_verify_nonce($_POST['hamzaban_default_lang_nonce'], 'hamzaban_save_default_lang')) {
        if (isset($_POST['hamzaban_default_lang'])) {
            update_option('hamzaban_default_lang', sanitize_text_field($_POST['hamzaban_default_lang']));
            echo '<div class="updated"><p>' . esc_html__('Settings saved', 'hamzaban') . '</p></div>';
        }
    }

    $langs = [
        'en' => 'English',
        'fa' => 'فارسی',
        'es' => 'Español',
        'fr' => 'Français',
        'de' => 'Deutsch',
        'it' => 'Italiano',
        'ru' => 'Русский',
        'zh' => '中文',
        'ja' => '日本語',
        'ko' => '한국어',
        'ar' => 'العربية',
        'pt' => 'Português',
        'hi' => 'हिन्दी',
        'bn' => 'বাংলা',
        'ur' => 'اردو',
        'tr' => 'Türkçe',
        'vi' => 'Tiếng Việt',
        'nl' => 'Nederlands',
        'sv' => 'Svenska',
        'no' => 'Norsk',
        'da' => 'Dansk',
        'fi' => 'Suomi',
        'el' => 'Ελληνικά',
        'he' => 'עברית',
        'id' => 'Bahasa Indonesia',
        'ms' => 'Bahasa Melayu',
        'pl' => 'Polski',
        'ro' => 'Română',
        'th' => 'ไทย',
        'cs' => 'Čeština',
        'hu' => 'Magyar',
        'uk' => 'Українська'
    ];
    $default_lang = get_option('hamzaban_default_lang', 'en');
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Hamzaban Settings', 'hamzaban'); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('hamzaban_save_default_lang', 'hamzaban_default_lang_nonce'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Default Language', 'hamzaban'); ?></th>
                    <td>
                        <select name="hamzaban_default_lang">
                            <?php foreach ($langs as $code => $name): ?>
                                <option value="<?php echo esc_attr($code); ?>" <?php selected($code, $default_lang); ?>><?php echo esc_html($name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

?>
