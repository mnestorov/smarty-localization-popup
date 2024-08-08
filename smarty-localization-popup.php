<?php
/**
 * Plugin Name: SM - Localization Popup
 * Plugin URI: https://smartystudio.net/smarty-custom-upsell-products-design
 * Description: Shows a popup based on the user's IP location using IPData API.
 * Version: 1.0.0
 * Author: Smarty Studio | Martin Nestorov
 * Author URI: https://smartystudio.net
 * Text Domain: smarty-localization-popup
 * WC requires at least: 3.5.0
 * WC tested up to: 9.0.2
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

// Dependencies for the ipdata API client
require 'vendor/autoload.php';

use Ipdata\ApiClient\Ipdata;
use Symfony\Component\HttpClient\Psr18Client;
use Nyholm\Psr7\Factory\Psr17Factory;

if (!function_exists('smarty_lp_enqueue_scripts')) {
    function smarty_lp_enqueue_scripts() {
        // Enqueue the popup script and styles
        wp_enqueue_script('lp-script', plugin_dir_url(__FILE__) . 'localization-popup.js', array('jquery'), null, true);
        wp_enqueue_style('lp-style', plugin_dir_url(__FILE__) . 'localization-popup.css');

        // Localize script with popup text and API key
        wp_localize_script('ipdata-popup-script', 'ipdataPopupData', array(
            'bg_text'       => 'Изглежда, че идвате от България. Искате ли да видите: Възможности за доставка за България Съдържание на български',
            'button_text'   => 'Да',
            'bg_url'        => 'https://staging.dr-d.eu/',
            'api_key'       => '6a8ff4ce98625436051bd6bff55bd579890d4b01cf3a952c5c4c749f'
        ));
    }
    add_action('wp_enqueue_scripts', 'smarty_lp_enqueue_scripts');
}

if (!function_exists('smarty_lp_add_hreflang_tags')) {
    function smarty_lp_add_hreflang_tags() {
        // Define your site information with language and corresponding site URL
        $sites = array(
            'en' => 'https://staging.dr-d.eu/en/',
            'bg' => 'https://staging.dr-d.eu/',
        );

        // Get the current site and page information
        global $wp;
        $current_url = home_url(add_query_arg(array(), $wp->request));

        // Get the current language code (assumes 'en' is the default)
        $current_site_lang = 'en';
        if (strpos(get_bloginfo('language'), 'bg') !== false) {
            $current_site_lang = 'bg';
        }

        foreach ($sites as $lang => $url) {
            // Skip adding hreflang for the current site
            if ($current_site_lang === $lang) {
                continue;
            }

            // Build the corresponding URL for the other languages
            $parsed_current_url = parse_url($current_url);
            $path = isset($parsed_current_url['path']) ? $parsed_current_url['path'] : '';
            $translated_url = rtrim($url, '/') . $path;

            // Output the hreflang tag
            if ($lang === 'bg') {
                echo '<link rel="alternate" hreflang="bg" href="' . esc_url($translated_url) . '" />' . "\n";
            } else {
                echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($translated_url) . '" />' . "\n";
            }
        }
    }
    add_action('wp_head', 'smarty_lp_add_hreflang_tags');
}
