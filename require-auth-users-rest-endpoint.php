<?php
/**
 * Plugin Name:       Require Auth Users REST Endpoint
 * Plugin URI:        https://github.com/salcode/require-auth-users-rest-endpoint
 * Description:       Restrict /wp/v2/users REST API endpoint routes to authenticated users.
 * Version:           1.0.0
 * Requires at least: 6.5
 * Requires PHP:      7.2
 * Author:            Sal Ferrarello
 * Author URI:        https://salferrarello.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       require-auth-users-rest-endpoint
 * Domain Path:       /languages
 */

namespace salcode\RequireAuthUsersRestEndpoint;

use WP_Error;

/**
 * Is the route given a Users route?
 *
 * @param string $route The route.
 * @return bool  True if the route is a users route, false otherwise.
 */
function is_users_route( $route ) {
	return '/wp/v2/users' === $route || strpos( $route, '/wp/v2/users/' ) === 0;
}

/**
 * Restrict access to the users route.
 *
 * @param mixed           $result  Response to replace the requested version with. Can be anything
 *                                 a normal endpoint can return, or null to not hijack the request.
 * @param WP_REST_Server  $server  Server instance.
 * @param WP_REST_Request $request Request used to generate the response.
 */
function rest_pre_dispatch( $result, $server, $request ) {
	if ( ! is_users_route( $request->get_route() ) ) {
		// This is not the route we're looking for.
		return $result;
	}

	if ( current_user_can( 'read' ) ) {
		// User has sufficient capabilities.
		return $result;
	}

	// User does NOT have sufficient capabilities.
	return new WP_Error(
		'rest_cannot_view',
		__(
			'Sorry, you are not allowed to view users for this site.',
			'require-auth-users-rest-endpoint'
		),
		[
			'status' => rest_authorization_required_code(),
		],
	);
}

add_filter( 'rest_pre_dispatch', __NAMESPACE__ . '\rest_pre_dispatch', 99999, 3 );
