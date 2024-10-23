<?php
/**
 * Plugin Name: SM - Localization Popup
 * Plugin URI: https://github.dev/mnestorov/smarty-localization-popup
 * Description: Shows a popup based on the user's IP location using IPData API.
 * Version: 1.0.0
 * Author: Smarty Studio | Martin Nestorov
 * Author URI: https://github.dev/mnestorov
 * Text Domain: smarty-localization-popup
 * WC requires at least: 3.5.0
 * WC tested up to: 9.0.2
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

// Dependencies for the ipdata API client
require __DIR__ . '/vendor/autoload.php';

use Ipdata\ApiClient\Ipdata;
use Symfony\Component\HttpClient\Psr18Client;
use Nyholm\Psr7\Factory\Psr17Factory;

if (!function_exists('smarty_lp_register_settings')) {
    /**
     * Registers settings for the Localization Popup plugin.
     *
     * This function registers the settings that will be used to configure the localization popup.
     * The settings include the popup text, button text, country name, and IPData API key.
     */
    function smarty_lp_register_settings() {
        // Register settings
        register_setting('smarty_lp_settings_group', 'smarty_lp_bg_text');
        register_setting('smarty_lp_settings_group', 'smarty_lp_button_text');
        register_setting('smarty_lp_settings_group', 'smarty_lp_country_name');
        register_setting('smarty_lp_settings_group', 'smarty_lp_api_key');
    }
    add_action('admin_init', 'smarty_lp_register_settings');
}

if (!function_exists('smarty_lp_settings_page')) {
    /**
     * Adds a settings page for the Localization Popup plugin.
     *
     * This function adds a new settings page to the WordPress admin menu
     * where users can configure the Localization Popup plugin.
     */
    function smarty_lp_settings_page() {
        add_options_page(
            __('Localization Popup | Settings', 'smarty-localization-popup'),
            __('Localization Popup', 'smarty-localization-popup'),
            'manage_options',
            'smarty-lp-settings',
            'smarty_lp_settings_page_content'
        );
    }
    add_action('admin_menu', 'smarty_lp_settings_page');
}

if (!function_exists('smarty_lp_settings_page_content')) {
    /**
     * Renders the content of the settings page for the Localization Popup plugin.
     *
     * This function outputs the HTML for the settings page, allowing users to
     * configure the popup text, button text, country name, and IPData API key.
     */
    function smarty_lp_settings_page_content() {
        ?>
        <div class="wrap">
            <h1><?php _e('SM - Localization Popup | Settings', 'smarty-localization-popup'); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields('smarty_lp_settings_group'); ?>
                <?php do_settings_sections('smarty_lp_settings_group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Popup Text', 'smarty-localization-popup'); ?></th>
                        <td>
                            <textarea name="smarty_lp_bg_text" rows="5" cols="50"><?php echo esc_textarea(get_option('smarty_lp_bg_text', 'It looks like you are visiting from [lp_country]. Would you like to see: [lp_content]')); ?></textarea>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Button Text', 'smarty-localization-popup'); ?></th>
                        <td>
                            <input type="text" name="smarty_lp_button_text" value="<?php echo esc_attr(get_option('smarty_lp_button_text', 'Yes')); ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Country Name', 'smarty-localization-popup'); ?></th>
                        <td>
                            <input type="text" name="smarty_lp_country_name" value="<?php echo esc_attr(get_option('smarty_lp_country_name', 'Bulgaria')); ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('IPData API Key', 'smarty-localization-popup'); ?></th>
                        <td>
                            <input type="text" name="smarty_lp_api_key" value="<?php echo esc_attr(get_option('smarty_lp_api_key', 'YOUR_API_KEY')); ?>" />
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

if (!function_exists('smarty_lp_enqueue_scripts')) {
    /**
     * Enqueues scripts and styles for the Localization Popup and localizes script data.
     *
     * This function enqueues the necessary JavaScript and CSS for the localization popup,
     * and localizes dynamic data such as popup text, button text, and the API key.
     */
    function smarty_lp_enqueue_scripts() {
        // Determine the base URL dynamically
        $base_url = get_site_url();

        // Retrieve dynamic settings
        $bg_text = get_option('smarty_lp_bg_text', 'It looks like you are visiting from [lp_country]. Would you like to see: [lp_content]');
        $button_text = get_option('smarty_lp_button_text', 'Yes');
        $country_name = get_option('smarty_lp_country_name', 'Bulgaria');
        $api_key = get_option('smarty_lp_api_key', 'YOUR_API_KEY');

        // Replace placeholders in the text
        $bg_text = str_replace('[lp_country]', $country_name, $bg_text);
        $bg_text = str_replace('[lp_content]', 'Delivery options for ' . $country_name . ' Content in ' . $country_name, $bg_text);

        // Enqueue the popup script and styles
        wp_enqueue_script('lp-script', plugin_dir_url(__FILE__) . 'localization-popup.js', array('jquery'), null, true);
        wp_enqueue_style('lp-style', plugin_dir_url(__FILE__) . 'localization-popup.css');

        // Localize script with dynamic popup text and API key
        wp_localize_script('lp-script', 'ipdataPopupData', array(
            'popup_heading'   => sprintf(__('It looks like you are visiting from %s', 'smarty-localization-popup'), $country_name),
            'popup_subheading'=> __('Would you like to see:', 'smarty-localization-popup'),
            'content_option_1'=> __('Delivery options for ' . $country_name, 'smarty-localization-popup'),
            'content_option_2'=> __('Content in ' . $country_name, 'smarty-localization-popup'),
            'button_text'     => $button_text,
            'bg_url'          => $base_url,
            'api_key'         => $api_key,
            'country_code'    => 'BG', // TODO: The country code for Bulgaria, could be made dynamic as well
            'flag_url'        => 'https://www.worldometers.info/img/flags/bu-flag.gif', // Could also be made dynamic
        ));
    }
    add_action('wp_enqueue_scripts', 'smarty_lp_enqueue_scripts');
}

if (!function_exists('smarty_lp_add_hreflang_tags')) {
    function smarty_lp_add_hreflang_tags() {
        global $post;
    
        // Dynamically determine the base URLs for each language/site based on blog ID
        $english_url = trailingslashit(get_home_url(2));  // English site (ID 2)
        $bulgarian_url = trailingslashit(get_home_url(1));  // Bulgarian site (ID 1)
    
        // Determine the current URL or path
        if (is_home() || is_front_page()) {
            $current_url = get_home_url(null, '/');
        } elseif (function_exists('is_shop') && is_shop()) {
            $current_url = get_permalink(wc_get_page_id('shop'));
        } elseif ($post && $post->post_type === 'product') {
            $current_url = get_permalink($post->ID);
        } else {
            $current_url = get_permalink(get_queried_object_id());
        }
    
        // Extract the path from the current URL and ensure it does not include the site URL
        $path = str_replace(trailingslashit(get_site_url()), '', $current_url);
    
        $current_blog_id = get_current_blog_id();
    
        // Output hreflang tags
        if ($current_blog_id == 1) { // Bulgarian site
            echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($english_url . $path) . '"/>' . "\n";
        } else if ($current_blog_id == 2) { // English site
            echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($english_url . $path) . '"/>' . "\n";
            echo '<link rel="alternate" hreflang="bg-BG" href="' . esc_url($bulgarian_url . $path) . '"/>' . "\n";
        }
    }
    add_action('wp_head', 'smarty_lp_add_hreflang_tags');
}
