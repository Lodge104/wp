<?php
/**
 * Class responsible for interfacing with the Slack API.
 *
 * @link       http://mckernan.in
 * @since      1.0.0
 * @package		OA Tools
 */

// define( 'SLACK_API_KEY', get_theme_mod( 'oaldr_slack_api_key' ) );
define( 'SLACK_API_KEY', 'xoxp-4251395768-16936426437-22131195252-2bcb15d9f9' );
// define( 'SLACK_SUBDOMAIN', get_theme_mod( 'oaldr_slack_subdomain' ) );
define( 'SLACK_SUBDOMAIN', 'tahosa383' );

/**
 * Class responsible for interfacing with the Mailgun API.
 */
class OA_Tools_Slack {
	/**
	 * Constructor.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
	}

	/**
	 * Display a WordPress Dashboard error message.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Text/HTML content of the error message.
	 */
	public function error_message( $message ) {
		echo '<div class="error notice">';
		echo '<p>';
		esc_html_e( 'OA Tools - ' . $message, 'oa_tools' );
		echo '</p>';
		echo '</div>';
	}

	/**
	 * Invite a user to the Slack Team.
	 *
	 * @since 1.0.0
	 *
	 * @param string $post_id The post ID of the person we're creating an account for.
	 * @param string $fname The first name of the user to be created.
	 * @param string $lname The last name of the user to be created.
	 * @param string $email The email address of the user to be created.
	 *
	 * @return array
	 */
	public function invite_member( $post_id, $fname, $lname, $email ) {
		$slack_created = get_post_meta( $post_id, '_slack_created' );
		if ( ! $slack_created ) {
			$url  = 'https://'.SLACK_SUBDOMAIN.'.slack.com/api/users.admin.invite?t='.time();
			$args = array(
				'body' => array(
					'email'      => $email,
		            'first_name' => $fname,
					'last_name'  => $lname,
		            'token'      => SLACK_API_KEY,
		            'set_active' => true,
		            '_attempts'  => '1',
				),
			);
			$response = wp_remote_post( $url, $args );
			if ( true === $response ) {
				update_post_meta( $post_id, '_slack_created', true );
			} else {
				update_post_meta( $post_id, '_slack_created', false );
			}
			return $response;
		}
	}
}
