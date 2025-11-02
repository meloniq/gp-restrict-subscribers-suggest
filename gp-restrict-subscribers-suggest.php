<?php
/**
 * Plugin Name:       GP Restrict Subscribers Suggest
 * Plugin URI:        https://blog.meloniq.net/gp-restrict-subscribers-suggest/
 *
 * Description:       GlotPress plugin to restrict subscribers from suggesting translations.
 * Tags:              glotpress, subscribers, restrict, suggest, translations
 *
 * Requires at least: 4.9
 * Requires PHP:      7.4
 * Version:           1.0
 *
 * Author:            MELONIQ.NET
 * Author URI:        https://meloniq.net/
 *
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain:       gp-restrict-subscribers-suggest
 *
 * Requires Plugins:  glotpress
 *
 * @package Meloniq\GpRestrictSubscribersSuggest
 */

// If this file is accessed directly, then abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'GPRSS_TD', 'gp-restrict-subscribers-suggest' );

/**
 * Filter whether a user can do an action.
 * Return boolean to skip doing a verdict.
 *
 * @param string|bool $verdict Whether user can do an action.
 * @param array $args {
 *     Arguments of the permission check.
 *
 *     @type int     $user_id     The user being evaluated.
 *     @type string  $action      Action to be executed.
 *     @type string  $object_type Object type to execute against.
 *     @type string  $object_id   Object ID to execute against.
 *     @type WP_User $user        The user being evaluated.
 *     @type mixed   $extra       Extra information given to the permission check.
 * }
 */
function gprss_pre_restrict_subscribers_suggest( $verdict, $args ) {
	// Only restrict 'edit' action on 'translation-set' object type.
	if ( 'edit' !== $args['action'] || 'translation-set' !== $args['object_type'] ) {
		return $verdict;
	}

	// Is the user a subscriber?
	$user = $args['user'];
	if ( in_array( 'subscriber', (array) $user->roles, true ) ) {
		// Deny permission to suggest translations.
		return false;
	}

	return $verdict;
}
add_filter( 'gp_pre_can_user', 'gprss_pre_restrict_subscribers_suggest', 10, 2 );
