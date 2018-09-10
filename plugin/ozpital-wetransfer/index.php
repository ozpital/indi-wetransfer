<?php

/*
Plugin Name: Ozpital WPWeTransfer
Description: Upload to WeTransfer without leaving wordpress
Version: 0.0.8
Author: Laurence Archer
Author URI: https://ozpital.com
*/

require_once('autoload.php');

use Ozpital\WPWeTransfer\Core\OWPWT_Assets as Assets;
use Ozpital\WPWeTransfer\Core\OWPWT_Menu as Menu;
use Ozpital\WPWeTransfer\Core\OWPWT_Option as Option;
use Ozpital\WPWeTransfer\Core\OWPWT_Plugin as Plugin;
use Ozpital\WPWeTransfer\Core\OWPWT_Shortcode as Shortcode;
use Ozpital\WPWeTransfer\Services\OWPWT_AjaxRouteService as Route;

// Define plugin root
define('OWPWT_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('OWPWT_PLUGIN_PATH', OWPWT_DIR . 'index.php');
define('OWPWT_URL', plugin_dir_url(__FILE__));
define('OWPWT_MIN_PHP', '7.0');

if (is_admin()) {
    // Register activation checks
    Plugin::activation();

    // Register Options
    Option::registerApiKey();

    // Register menu
    Menu::register();
}

// Register shortcode
Shortcode::register();

// Register Styles
Assets::styles();

// Register Scripts
Assets::scripts();

// Register Ajax Functions
Route::auth();
Route::transfer();
Route::items();
Route::url();
Route::completeTransfer();