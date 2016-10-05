<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://mckernan.in
 * @since      1.0.0
 * @package OA Tools
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Kevin McKernan <kevin@mckernan.in>
 */
class OA_Tools_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var string The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var string The current version of this plugin.
	 */
	private $version;

	/**
	 * An instance of OA_Tools_Slack.
	 *
	 * @since    1.0.0
	 *
	 * @var class An instance of OA_Tools_Slack
	 */
	public $slack;

	/**
	 * An instance of OA_Tools_Mailgun.
	 *
	 * @since    1.0.0
	 *
	 * @var class An instance of OA_Tools_Mailgun
	 */
	public $mailgun;

	/**
	 * Mailgun API Key
	 *
	 * @since    1.1.0
	 *
	 * @var string Mailgun API Key
	 */
	public $mailgun_api_key;

	/**
	 * Mailgun Domain
	 *
	 * @since    1.1.0
	 *
	 * @var string Mailgun Domain
	 */
	public $mailgun_domain;

	/**
	 * Mailgun Main List
	 *
	 * @since    1.1.0
	 *
	 * @var string Mailgun Main List
	 */
	public $mailgun_main_list;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name       = $plugin_name;
		$this->version           = $version;
		$this->mailgun           = new OA_Tools_Mailgun();
		$this->slack             = new OA_Tools_Slack();
		$this->mailgun_api_key   = get_theme_mod( 'oaldr_mailgun_api_key' );
		$this->mailgun_domain    = get_theme_mod( 'oaldr_mailgun_domain' );
		$this->mailgun_main_list = get_theme_mod( 'oaldr_mailgun_main_list' );
		require_once plugin_dir_path( __FILE__ ) . 'acf-fields.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ).'css/oa-tools-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ).'js/oa-tools-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Actions on position save.
	 *
	 * When a position is saved, we create the list for it in Mailgun if it
	 * does not yet exist. We also add them to the LEC list. If a person is
	 * defined for the position, then we also add that person's email address
	 * to the position list.
	 *
	 * @since 1.0.0
	 * @param int  $post_id The post ID.
	 * @param post $post The post object.
	 * @param bool $updated Whether this is an existing post being updated or not.
	 */
	public function position_save_action( $post_id, $post, $updated ) {
		$slug = 'oaldr_position';
		if ( $slug !== $post->post_type ) {
	        return;
	    }
		$position_email = get_field( 'position_email', $post_id );
		$person 		= current( get_field( 'person', $post_id ) );
		$copied_emails 	= get_field( 'copied_emails', $post_id );
		$title 			= get_the_title();
		$lists 			= $this->mailgun->get_lists();
		if ( $position_email ) {
			if ( ! in_array( $position_email, $lists ) ) {
				$this->mailgun->create_list( $position_email, $title );
			}
			$this->mailgun->add_list_member( $this->mailgun_main_list, $position_email, $title );
			$this->mailgun->custom_log( 'Main list:' . $this->mailgun_main_list );
		}
		if ( $copied_emails ) {
			foreach ( $copied_emails as $recipient ) {
				$this->mailgun->add_list_member( $position_email, $recipient['email'], $recipient['name'] );
			}
		}
		if ( $person ) {
			$person_email         = get_field( 'person_email', $person );
			$fname                = get_field( 'first_name', $person );
			$lname                = get_field( 'last_name', $person );
			$copied_emails_person = get_field( 'copied_emails', $person );
			$in_list              = $this->mailgun->check_list_for_member( $position_email, $person_email );
			$addresses            = [];
			if ( ! $in_list ) {
				$addresses[] = [
					'address' => $person_email,
					'name'    => $fname.' '.$lname,
				];
			}
			if ( $copied_emails_person ) {
				foreach ( $copied_emails_person as $recipient ) {
					$addresses[] = [
						'address' => $person_email,
						'name'    => $fname.' '.$lname,
					];
				}
			}
			$addresses = json_encode($addresses);
			$this->mailgun->add_array_list_members( $position_email, $addresses );
		}
	}

	/**
	 * Actions on person save.
	 *
	 * When a person is saved, we get the positions that they are assigned to,
	 * and add their email to each position list, if it is not already in the
	 * specified list.
	 *
	 * @since 1.0.0
	 * @param int  $post_id The post ID.
	 * @param post $post The post object.
	 * @param bool $updated Whether this is an existing post being updated or not.
	 */
	public function person_save_action( $post_id, $post, $updated ) {
		$slug = 'oaldr_person';
		if ( $slug !== $post->post_type ) {
	        return;
	    }
		$fname        = get_field( 'first_name', $post_id );
		$lname        = get_field( 'last_name', $post_id );
		$person_email = get_field( 'person_email', $post_id );
		$parent_email = get_field( 'parent_email', $post_id );
		$title        = get_the_title();
		if ( $person_email ) {
			$options = array(
				'post_type'      => 'oaldr_position',
				'posts_per_page' => 50,
				'meta_query'     => array(
					array(
					  'key' 	=> 'person',
					  'value' 	=> $post_id,
					  'compare' => 'LIKE',
					),
				),
			);
			$query = new WP_Query( $options );
			if ( $query->has_posts() ) {
				$slack_invite = $this->slack->invite_member( $post_id, $fname, $lname, $person_email );
				$addresses = [];
				foreach ( $query->posts as $post ) {
					$position_email = get_field( 'position_email', $post->id );
					// $this->mailgun->add_list_member( $position_email, $person_email, $fname.' '.$lname );
					$addresses[] = [
						'address' => $person_email,
						'name'    => $fname.' '.$lname,
					];
				}
				$addresses = json_encode( $addresses );
				$this->mailgun->add_array_list_members( $position_email, $addresses );
			}
		}
	}

	public function customize_save() {
		$mailgun_api_key   = get_theme_mod( 'oaldr_mailgun_api_key' );
		$mailgun_domain    = get_theme_mod( 'oaldr_mailgun_domain' );
		$mailgun_main_list = get_theme_mod( 'oaldr_mailgun_main_list' );
		if ( $mailgun_domain && $mailgun_api_key && $mailgun_main_list ) {
			$this->mailgun->create_list( $mailgun_main_list, 'Main List' );
		}
	}
}
