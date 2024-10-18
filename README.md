<p align="center"><a href="https://smartystudio.net" target="_blank"><img src="https://smartystudio.net/wp-content/uploads/2023/06/smarty-green-logo-small.png" width="100" alt="SmartyStudio Logo"></a></p>

# Smarty Studio - Localization Popup for WordPress

[![Licence](https://img.shields.io/badge/LICENSE-GPL2.0+-blue)](./LICENSE)

- Developed by: [Smarty Studio](https://smartystudio.net) | [Martin Nestorov](https://github.com/mnestorov)
- Plugin URI: https://github.com/mnestorov/smarty-localization-popup

## Overview

The **Smarty Studio - Localization Popup for WordPress** is a plugin that displays a custom popup to users based on their geographical location using the IPData API. The plugin allows you to dynamically display country-specific content, such as shipping options or localized content, with configurable text and design.

## Description

This plugin is designed to help site administrators deliver a customized experience to users based on their location. By integrating with the IPData API, the plugin detects the user's country and displays a popup with tailored options. All text and settings can be easily configured through a WordPress admin settings page, making the plugin flexible and easy to use.

### Key Features

- **Dynamic Content**: Customize the popup text, button text, and target URLs based on the user’s location.
- **Multi-language Support**: Works with multisite WordPress installations, allowing you to define hreflang tags for SEO.
- **Admin Settings**: Configure all the dynamic content from the WordPress admin area without touching the code.
- **IPData API Integration**: Detect the user's location in real-time using the IPData API.

## Installation

1. **Download the Plugin**: Download the ZIP file from the repo.
2. **Upload to WordPress**:
   - Navigate to your WordPress admin panel.
   - Go to Plugins > Add New > Upload Plugin.
   - Choose the downloaded ZIP file and click 'Install Now.'
3. **Activate the Plugin**:
   - Once installed, click on 'Activate Plugin' to enable its features on your site.

## Usage

1. **Configure Settings**:
   - After activation, go to `Settings > Localization Popup` in the WordPress admin area.
   - Set the popup text, button text, country name, and API key for the IPData service.
   
2. **Customize Popup Content**:
   - Customize the message that will be shown to users based on their location.
   - Use placeholders like `[lp_country]` and `[lp_content]` in the settings to dynamically insert country-specific information.
   
3. **Ensure API Integration**:
   - Make sure to enter your IPData API key in the settings for the plugin to function correctly.

4. **Multisite Support**:
   - For multisite installations, the plugin automatically generates hreflang tags for better SEO across different language sites.

## Changelog

For a detailed list of changes and updates made to this project, please refer to our [Changelog](./CHANGELOG.md).

---

## License

This project is released under the [GPL-2.0+ License](http://www.gnu.org/licenses/gpl-2.0.txt).
