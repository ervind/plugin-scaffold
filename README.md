# WordPress Plugin Scaffold

A clean, modern, object-oriented starter boilerplate for building custom WordPress plugins with PHP 8.3+ and Composer PSR-4 autoloading.

---

## Table of Contents

- [About The Project](#about-the-project)
- [Project Structure](#project-structure)
- [Requirements](#requirements)
- [Installation & Getting Started](#installation--getting-started)
- [First Steps After Installation (Setup Checklist)](#first-steps-after-installation-setup-checklist)
  - [1. Update Plugin Header](#1-update-plugin-header)
  - [2. Update Namespace & Composer Autoloading](#2-update-namespace--composer-autoloading)
  - [3. Update Base Classes and Global Helper Function](#3-update-base-classes-and-global-helper-function)
  - [4. Update Text Domain](#4-update-text-domain)
  - [5. Update Asset Handles & JS Localization Object](#5-update-asset-handles--js-localization-object)
  - [6. Update Database Table & Option Prefixes](#6-update-database-table--option-prefixes)
- [How to Customize & Extend](#how-to-customize--extend)
  - [Adding Classes & Functionality](#adding-classes--functionality)
  - [Hook Registration](#hook-registration)
  - [Assets & Script Localization](#assets--script-localization)
  - [Template Rendering](#template-rendering)
- [What Can Be Optionally Removed](#what-can-be-optionally-removed)
  - [Custom Database Tables (DBHelper)](#custom-database-tables-dbhelper)
  - [ACF Dependency & Custom Body Class](#acf-dependency--custom-body-class)
  - [Gravity Forms No-Conflict Integration](#gravity-forms-no-conflict-integration)
  - [JSON Upload MIME Type Support](#json-upload-mime-type-support)
  - [Template Helper (App.php)](#template-helper-appphp)
  - [Frontend or Admin Assets](#frontend-or-admin-assets)
- [License](#license)

---

## About The Project

This scaffold provides a minimal, well-architected starting point for WordPress plugin development. It eliminates repetitive setup tasks while enforcing clean OOP patterns and modern PHP practices:

- **PSR-4 Autoloading:** Managed by Composer, eliminating manual `require_once` chains.
- **Singleton Core:** Global accessor function (`PluginScaffold()`) providing access to the main plugin instance.
- **Base Plugin Utilities:** Quick static methods (`PluginScaffoldPlugin`) for resolving base paths, URLs, versions, and filenames.
- **Modular Initialization:** Dedicated `Initializer` class for registering actions, filters, scripts, and styles.
- **Database Schema Helper:** `DBHelper` class supporting versioned database table migrations via WordPress's `dbDelta`.
- **Template Rendering Helper:** `App::get_template()` for clean separation of PHP logic and HTML view templates.
- **Organized Assets:** Dedicated frontend and admin stylesheets and JavaScript files ready for enqueueing.

---

## Project Structure

```text
plugin-scaffold/
├── assets/
│   ├── admin.css           # Admin-specific styles
│   ├── admin.js            # Admin-specific scripts
│   ├── scripts.js          # Frontend scripts
│   └── style.css           # Frontend styles
├── class/
│   ├── Helpers/
│   │   └── DBHelper.php    # Database versioning & table creation helper
│   ├── App.php             # General app helper (e.g., template rendering)
│   ├── Initializer.php     # WordPress hooks, filters, and asset registration
│   └── PluginCore.php      # Main plugin singleton class
├── composer.json           # Composer configuration & PSR-4 namespace mapping
├── plugin-loader.php       # Main plugin file with WP plugin headers & entry point
└── README.md               # Project documentation
```

---

## Requirements

- **PHP:** 8.3 or higher
- **WordPress:** 6.9 or higher
- **Composer:** For dependency management and autoloading
- *(Optional)* **Advanced Custom Fields PRO:** Only if using ACF-dependent features (e.g. `get_field( 'body_class' )`)

---

## Installation & Getting Started

1. **Clone or Download:**
   Place this repository in your WordPress plugins directory (`wp-content/plugins/`):
   ```bash
   cd wp-content/plugins/
   git clone <your-repository-url> your-plugin-slug
   cd your-plugin-slug
   ```

2. **Install Dependencies:**
   Generate the Composer autoloader:
   ```bash
   composer install
   ```
   *(Or run `composer dump-autoload` if no new external packages are added).*

3. **Activate the Plugin:**
   Go to **WordPress Admin > Plugins** and activate your plugin (or run `wp plugin activate your-plugin-slug` via WP-CLI).

---

## First Steps After Installation (Setup Checklist)

Before writing custom plugin code, search and replace scaffold defaults with your plugin's unique identifiers:

### 1. Update Plugin Header
In `plugin-loader.php`, customize the header metadata:
- **Plugin Name:** Your Plugin Name
- **Description:** Brief description of what your plugin does
- **Version:** Initial version (e.g. `1.0.0`)
- **Author & Author URI:** Your name/company and website
- **Text Domain:** `your-plugin-slug`
- **Requires Plugins:** Remove `advanced-custom-fields-pro` if ACF is not required.

### 2. Update Namespace & Composer Autoloading
1. In `composer.json`, change the namespace mapping and package name:
   ```json
   {
       "name": "your-vendor/your-plugin-slug",
       "autoload": {
           "psr-4": {
               "YourNamespace\\": "class/"
           }
       }
   }
   ```
2. Update the namespace in all PHP files under `class/` and references in `plugin-loader.php`:
   - Replace `namespace PluginScaffold;` with `namespace YourNamespace;`
   - Replace `namespace PluginScaffold\Helpers;` with `namespace YourNamespace\Helpers;`
   - Update `use` statements accordingly.
3. Regenerate Composer autoloader:
   ```bash
   composer dump-autoload
   ```

### 3. Update Base Classes and Global Helper Function
- In `plugin-loader.php`:
  - Rename class `PluginScaffoldPlugin` to `YourPluginPlugin` (or your preferred base helper name).
  - Rename global function `PluginScaffold()` to `YourPlugin()`:
    ```php
    if ( !function_exists( 'YourPlugin' ) ) {
        function YourPlugin() : PluginCore {
            return PluginCore::instance();
        }
    }
    add_action( 'plugins_loaded', 'YourPlugin', 1290 );
    ```

### 4. Update Text Domain
- In `class/PluginCore.php`, update the text domain in `load_plugin_textdomain()`:
  ```php
  load_plugin_textdomain( 'your-plugin-slug', false, YourPluginPlugin::basePath() . '/languages' );
  ```

### 5. Update Asset Handles & JS Localization Object
- In `class/Initializer.php`:
  - Rename style/script handles from `'plugin-scaffold'` and `'plugin-scaffold-admin'` to your unique handles (e.g., `'your-plugin'` / `'your-plugin-admin'`).
  - Rename the JavaScript global object name passed to `wp_localize_script` from `'PLUGIN_SCAFFOLD'` to your identifier (e.g., `'YOUR_PLUGIN_DATA'`).

### 6. Update Database Table & Option Prefixes
If you plan to use custom database tables via `DBHelper.php`:
- In `class/Helpers/DBHelper.php`:
  - Update table prefix in `get_table_name()`:
    ```php
    return $wpdb->prefix . 'your_plugin_' . $sub_name;
    ```
  - Update option name in `get_saved_db_version()` and `update_db_version()`:
    ```php
    get_option( 'your_plugin_db_version' );
    update_option( 'your_plugin_db_version', self::DB_VERSION );
    ```

---

## How to Customize & Extend

### Adding Classes & Functionality
Thanks to PSR-4 autoloading, any class added under `class/` with matching namespace will be automatically loaded without needing manual `require` calls.

Example: Adding `class/Controllers/SettingsController.php`:
```php
<?php

namespace YourNamespace\Controllers;

if ( !defined( 'WPINC' ) ) {
    exit;
}

class SettingsController {
    public function __construct() {
        // Initialization logic
    }
}
```

### Hook Registration
Use `class/Initializer.php` to centralize WordPress action and filter hooks:
```php
public function register_hooks() : void {
    add_action( 'init', [ $this, 'register_post_types' ] );
    add_action( 'rest_api_init', [ $this, 'register_rest_routes' ] );
    // Add additional hooks here
}
```

### Assets & Script Localization
- Add CSS rules in `assets/style.css` (frontend) and `assets/admin.css` (admin).
- Add JavaScript in `assets/scripts.js` (frontend) and `assets/admin.js` (admin).
- Pass server-side data, nonces, or REST API endpoints to JavaScript via `Initializer::get_js_vars()`:
  ```php
  public function get_js_vars() : array {
      return [
          'ajaxUrl' => admin_url( 'admin-ajax.php' ),
          'nonce'   => wp_create_nonce( 'your_plugin_nonce' ),
          'restUrl' => esc_url_raw( rest_url( 'your-plugin/v1/' ) ),
      ];
  }
  ```

### Template Rendering
Create a `templates/` directory in the plugin root. Render templates cleanly with variables using `App::get_template()`:

```php
// In your PHP code / controller:
$html = \YourNamespace\App::get_template( 'card.php', [
    'title' => 'Hello World',
    'items' => [ 'One', 'Two', 'Three' ],
] );

echo $html;
```

---

## What Can Be Optionally Removed

Depending on your plugin requirements, you can strip out parts of the scaffold you don't need:

### Custom Database Tables (DBHelper)
If your plugin does not create custom MySQL tables and only uses standard WordPress posts/taxonomies/options:
1. Delete `class/Helpers/DBHelper.php`.
2. In `class/PluginCore.php`: Remove `public DBHelper $db_helper;` and `$this->db_helper = new DBHelper();`.
3. In `class/Initializer.php`: Remove `add_action( 'init', [ $this, 'setup_db' ] );` and the `setup_db()` method.
4. In `composer.json`: Remove `"ext-mysqli": "*"` if not specifically required.

### ACF Dependency & Custom Body Class
If your plugin does not use Advanced Custom Fields PRO:
1. In `plugin-loader.php`: Remove `Requires Plugins: advanced-custom-fields-pro` from the file header.
2. In `class/Initializer.php`: Remove `add_filter( 'body_class', [ $this, 'filter_body_class' ] );` and the `filter_body_class()` method.

### Gravity Forms No-Conflict Integration
If not integrating with Gravity Forms scripts:
1. In `class/Initializer.php`: Remove `add_filter( 'gform_noconflict_scripts', [ $this, 'register_script' ] );` and the `register_script()` method.

### JSON Upload MIME Type Support
If you do not need to allow uploading `.json` files via the WordPress Media Library:
1. In `class/Initializer.php`: Remove `add_filter( 'upload_mimes', [ $this, 'add_upload_mimes' ] );` and the `add_upload_mimes()` method.

### Template Helper (App.php)
If your plugin is headless, API-only, or does not need a template inclusion helper:
1. Delete `class/App.php`.

### Frontend or Admin Assets
- If your plugin is **admin-only**: Delete `assets/style.css` and `assets/scripts.js`, and remove `enqueue_scripts()` from `Initializer.php`.
- If your plugin has **no admin UI**: Delete `assets/admin.css` and `assets/admin.js`, and remove `admin_enqueue_scripts()` from `Initializer.php`.

---

## License

This project is open-source and licensed under the [GPL v2 or later](https://www.gnu.org/licenses/gpl-2.0.html).
