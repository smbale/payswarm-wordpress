<?php
/*
Plugin Name: PaySwarm
Plugin URI: http://payswarm.com/
Description: PaySwarm allows you to charge micropayments for access to your website. PaySwarm is an open, royalty-free, Internet standard that enables micropayments across many different websites. You can learn more at the <a href='http://payswarm.com/'>PaySwarm standards website</a>.
Version: 1.0
Author: Digital Bazaar, Inc.
Author URI: http://digitalbazaar.com/
License: GPLv2
*/

/*
Copyright 2010-2012 Digital Bazaar, Inc. (email : support@digitalbazaar.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * The current URL to the PaySwarm plugin base directory.
 *
 * @package payswarm
 * @since 1.0
 * @global string PAYSWARM_PLUGIN_URL
 */
define('PAYSWARM_PLUGIN_URL', site_url() . '/wp-content/plugins/' .
  str_replace(basename(__FILE__), '', plugin_basename(__FILE__)));

require_once('payswarm-config.inc');
require_once('payswarm-admin.inc');
require_once('payswarm-article.inc');

// handle install/uninstall PaySwarm database
register_activation_hook(__FILE__, 'payswarm_install_database');
register_deactivation_hook(__FILE__, 'payswarm_uninstall_database');

// add actions associated with the WordPress processing
add_action('admin_init', 'payswarm_admin_init');
add_action('admin_menu', 'payswarm_admin_menu');
add_action('payswarm_hourly_event', 'payswarm_database_cleanup_sessions');
add_action('wp_print_styles', 'payswarm_add_stylesheets');
add_action('add_meta_boxes', 'payswarm_add_meta_boxes');
add_action('save_post', 'payswarm_save_post_data');
add_action('added_postmeta', 'payswarm_added_postmeta');
add_action('updated_postmeta', 'payswarm_updated_postmeta');

// add filters for text that the PaySwarm plugin will modify
add_filter('the_content', 'payswarm_filter_paid_content');

// add the javascript for the PaySwarm plugin
wp_enqueue_script('payswarm', PAYSWARM_PLUGIN_URL . 'payswarm.js');

/**
 * Adds the required stylesheets for the PaySwarm plugin.
 *
 * @package payswarm
 * @since 1.0
 */
function payswarm_add_stylesheets() {
  $css_url = PAYSWARM_PLUGIN_URL . 'payswarm.css';
  wp_register_style('payswarm-style', $css_url);
  wp_enqueue_style( 'payswarm-style');
}

/* end of file, omit ?> */
