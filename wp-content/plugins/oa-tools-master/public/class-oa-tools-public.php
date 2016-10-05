<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://mckernan.in
 * @since      1.0.0
 * @package	   OA Tools
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Kevin McKernan <kevin@mckernan.in>
 */
class OA_Tools_Public {
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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_shortcode( 'leadership-position-filter', array( $this, 'shortcode_leadership_position_filter' ) );
		add_shortcode( 'leadership-positions', array( $this, 'shortcode_leadership_position_grid' ) );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/*
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in OA_Tools_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The OA_Tools_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'oa-tools-public-css', plugin_dir_url( __FILE__ ).'css/oa-tools-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/*
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in OA_Tools_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The OA_Tools_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_register_script( 'oa-tools-public-js', plugin_dir_url( __FILE__ ) . 'js/oa-tools-public.js', array( 'jquery', 'mixitup' ), $this->version, false );
		wp_register_script( 'mixitup', 'https://cdnjs.cloudflare.com/ajax/libs/mixitup/2.1.11/jquery.mixitup.min.js' );
	}
	/**
	 * Register the customizer settings.
	 *
	 * @since    1.0.0
	 * @param object $wp_customize The $wp_customize object.
	 */
	public function customizer_settings( $wp_customize ) {
		$wp_customize->add_section( 'oaldr_settings', array(
			'title' 	=> __( 'OA Leadership Settings', 'oaldr' ),
			'priority' 	=> 30,
		));

		$wp_customize->add_setting( 'oaldr_headshot_default', array(
			'default' 	=> '',
			'transport' => 'refresh',
		));

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'oaldr_headshot_default_control',
				array(
					'label' 	=> __( 'Upload a placeholder', 'OA Tools Plugin' ),
					'section' 	=> 'oaldr_settings',
					'settings' 	=> 'oaldr_headshot_default',
					'context' 	=> 'your_setting_context',
				)
			)
		);

		$wp_customize->add_setting( 'oaldr_categorize_positions', array(
			'default' 		=> 'lodge',
			'capability' 	=> 'edit_theme_options',
			'transport' 	=> 'refresh',
		));

		$wp_customize->add_control( 'oaldr_categorize_positions', array(
			'label' 	=> __( 'Categorize Positions by?', 'OA Tools Plugin' ),
			'section' 	=> 'oaldr_settings',
			'settings' 	=> 'oaldr_categorize_positions',
			'type' 		=> 'select',
			'choices' 	=> array(
				'oaldr_chapter' => __( 'Chapter', 'OA Tools Plugin' ),
				'oaldr_lodge' 	=> __( 'Lodge', 'OA Tools Plugin' ),
				'oaldr_section' => __( 'Section', 'OA Tools Plugin' ),
			),
		));

		$wp_customize->add_setting( 'oaldr_mailgun_api_key', array(
	        'default' => '',
	    ));

		$wp_customize->add_control( 'oaldr_mailgun_api_key', array(
	        'label' 	=> 'Mailgun API Key',
	        'section' 	=> 'oaldr_settings',
	        'type' 		=> 'text',
			'settings' 	=> 'oaldr_mailgun_api_key',
	    ));

		$wp_customize->add_setting( 'oaldr_mailgun_domain', array(
	        'default' => '',
	    ));

		$wp_customize->add_control( 'oaldr_mailgun_domain', array(
	        'label' 	=> 'Mailgun Domain',
	        'section' 	=> 'oaldr_settings',
	        'type' 		=> 'text',
			'settings' 	=> 'oaldr_mailgun_domain',
	    ));

		$wp_customize->add_setting( 'oaldr_mailgun_main_list', array(
	        'default' => '',
	    ));

		$wp_customize->add_control( 'oaldr_mailgun_main_list', array(
	        'label' 	=> 'Mailgun Main List',
	        'section' 	=> 'oaldr_settings',
	        'type' 		=> 'text',
			'settings' 	=> 'oaldr_mailgun_main_list',
	    ));

		$wp_customize->add_setting( 'oaldr_slack_api_key', array(
	        'default' => '',
	    ));

		$wp_customize->add_control( 'oaldr_slack_api_key', array(
	        'label' 	=> 'Slack API Key',
	        'section' 	=> 'oaldr_settings',
	        'type' 		=> 'text',
			'settings' 	=> 'oaldr_slack_api_key',
	    ));

		$wp_customize->add_setting( 'oaldr_slack_subdomain', array(
	        'default' => '',
	    ));

		$wp_customize->add_control( 'oaldr_slack_subdomain', array(
	        'label' 	=> 'Slack Subdomain',
	        'section' 	=> 'oaldr_settings',
	        'type' 		=> 'text',
			'settings' 	=> 'oaldr_slack_subdomain',
	    ));
	}
	/**
	 * Register the shortcode for the leadership position filter.
	 *
	 * @since    1.0.0
	 * @param array $atts Shortcode attributes.
	 */
	public function shortcode_leadership_position_filter( $atts ) {
		ob_start();
		include( 'partials/shortcode-leadership-position-filter.php' );
		$shortcode_output = ob_get_clean();
		return $shortcode_output;
	}
	/**
	 * Register the shortcode for the leadership position grid.
	 *
	 * @since    1.0.0
	 * @param array $atts Shortcode attributes.
	 */
	public function shortcode_leadership_position_grid( $atts ) {
		ob_start();
		wp_enqueue_script( 'oa-tools-public-js' );
		wp_enqueue_style( 'oa-tools-public-css' );
		include( 'partials/shortcode-leadership-position-grid.php' );
		$shortcode_output = ob_get_clean();
		return $shortcode_output;
	}
}
