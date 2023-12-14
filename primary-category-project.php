<?php

/**
 * Plugin Name:       	 Primary Categories Project
 * Description:       	 Allow setting primary categories for posts and custom post types
 * Version:           	 0.0.1
 * Text Domain:       	 primary-category-project
 */

if (!defined('WPINC')) {
	die;
}

define('PCP_PATH', plugin_dir_path(__FILE__));
define('PCP_URL', plugin_dir_url(__FILE__));
define('PCP_VERSION', '0.0.1');
define('PCP_SLUG', 'primary-category-project');

require_once(dirname(__FILE__) . '/vendor/autoload.php');


require_once('src/Bootstrap.php');

function swytch_activate()
{
	MAR\PrimaryCategory\Update\Activate::activate();
}

function swytch_deactivate()
{
	MAR\PrimaryCategory\Update\Deactivate::deactivate();
}

function swytch_uninstall()
{
	MAR\PrimaryCategory\Update\Uninstall::uninstall();
}

register_activation_hook(__FILE__, 'swytch_activate');
register_deactivation_hook(__FILE__, 'swytch_deactivate');
register_uninstall_hook(__FILE__, 'swytch_uninstall');
