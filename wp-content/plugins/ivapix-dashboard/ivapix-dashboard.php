<?php
/*
 * Plugin Name:       Ivapix Dashboard
 * Plugin URI:        https://ivapix.rs/
 * Description:       .
 * Version:           1.0.0
 * Requires at least: 6.0.0
 * Requires PHP:      7.2
 * Author:            Ivapix
 * Author URI:        https://ivapix.rs/
 * License:           GPL v2 or later
 * Text Domain:       ivapix-dashboard
 */

if(!defined('ABSPATH')) {
    exit();
}

if(!class_exists('Ivapix_Admin_Settings'))
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-ivapix_admin_settings.php' );

if(!class_exists('Ivapix_Register_Domains'))
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-ivapix-register_domains.php' );

if(!class_exists('Ivapix_Dashboard'))
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-ivapix_dashboard.php' );

if(!class_exists('Ivapix_API'))
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-ivapix_api.php' );


/**
 * 
 * Initialize Ivapix Admin Settings page
 */
$ivapixSettings = new Ivapix_Admin_Settings();
$ivapixSettings->init();


/**
 * Initialize domain registration
 */

$ivapixDomainRegistration = new Ivapix_Register_Domains();
$ivapixDomainRegistration->init();

/**
 * Initialize Ivapix dashboard
 */
$ivapixDashboard = new Ivapix_Dashboard();
$ivapixDashboard->init();

/**
 * Initialize Ivapix API
 */
$ivapixApi = new Ivapix_API();
$ivapixApi->init();
