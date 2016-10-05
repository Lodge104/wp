<?php
/**
 * Register post types and taxonomies.
 *
 * @link       http://mckernan.in
 * @since      1.0.0
 * @package    OA Tools
 */

/**
 * Register post types and taxonomies.
 *
 * @link       http://mckernan.in
 * @since      1.0.0
 * @package    OA Tools
 */
class OA_Tools_Content_Types {
	/**
	 * Constructor.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'post_type_person' ), 0 );
		add_action( 'init', array( $this, 'post_type_position' ), 0 );
		add_action( 'init', array( $this, 'taxonomy_group' ), 0 );
		$this->taxonomy_register();
	}

	/**
	 * Register the person post type.
	 *
	 * @since    1.0.0
	 */
	public function post_type_person() {
		$labels = array(
			'name' 					=> 'People',
			'singular_name' 		=> 'Person',
			'menu_name' 			=> 'People',
			'parent_item_colon' 	=> 'Parent Item:',
			'all_items' 			=> 'All People',
			'view_item' 			=> 'View Person',
			'add_new_item' 			=> 'Add Person',
			'add_new' 				=> 'Add Person',
			'edit_item' 			=> 'Edit Person',
			'update_item' 			=> 'Update Person',
			'search_items' 			=> 'Search People',
			'not_found' 			=> 'Not found',
			'not_found_in_trash' 	=> 'Not found in Trash',
		);
		$args = array(
			'label' 				=> 'people',
			'description' 			=> 'People ',
			'labels' 				=> $labels,
			'supports' 				=> array( 'title', 'thumbnail' ),
			'hierarchical' 			=> false,
			'public' 				=> true,
			'show_ui' 				=> true,
			'show_in_menu' 			=> true,
			'show_in_nav_menus' 	=> true,
			'show_in_admin_bar' 	=> true,
			'menu_position' 		=> 5,
			'menu_icon' 			=> 'dashicons-admin-users',
			'can_export' 			=> true,
			'has_archive'			=> false,
			'exclude_from_search' 	=> true,
			'publicly_queryable' 	=> true,
			'rewrite' 				=> false,
			'capability_type' 		=> 'page',
		);
		register_post_type( 'oaldr_person', $args );
	}

	/**
	 * Register the position post type.
	 *
	 * @since    1.0.0
	 */
	public function post_type_position() {
		$labels = array(
			'name' 					=> 'Positions',
			'singular_name' 		=> 'Position',
			'menu_name' 			=> 'Positions',
			'parent_item_colon' 	=> 'Parent Item:',
			'all_items' 			=> 'All Positions',
			'view_item' 			=> 'View Position',
			'add_new_item' 			=> 'Add Position',
			'add_new' 				=> 'Add Position',
			'edit_item' 			=> 'Edit Position',
			'update_item' 			=> 'Update Position',
			'search_items' 			=> 'Search Position',
			'not_found' 			=> 'Not found',
			'not_found_in_trash' 	=> 'Not found in Trash',
		);
		$args = array(
			'label' 				=> 'position',
			'description' 			=> 'Positions',
			'labels' 				=> $labels,
			'supports' 				=> array( 'title', 'thumbnail' ),
			'hierarchical' 			=> false,
			'public' 				=> true,
			'show_ui' 				=> true,
			'show_in_menu' 			=> true,
			'show_in_nav_menus' 	=> true,
			'show_in_admin_bar' 	=> true,
			'menu_position' 		=> 5,
			'menu_icon' 			=> 'dashicons-groups',
			'can_export' 			=> true,
			'has_archive' 			=> false,
			'exclude_from_search' 	=> true,
			'publicly_queryable' 	=> true,
			'rewrite' 				=> false,
			'capability_type' 		=> 'page',
		);
		register_post_type( 'oaldr_position', $args );
	}
	/**
	 * Register the group taxonomy.
	 *
	 * @since    1.0.0
	 */
	public function taxonomy_group() {
		$labels = array(
				'name' 							=> _x( 'Groups', 'Taxonomy General Name', 'oa-tools' ),
				'singular_name' 				=> _x( 'Group', 'Taxonomy Singular Name', 'oa-tools' ),
				'menu_name' 					=> __( 'Group', 'oa-tools' ),
				'all_items' 					=> __( 'All Groups', 'oa-tools' ),
				'parent_item' 					=> __( 'Parent Group', 'oa-tools' ),
				'parent_item_colon' 			=> __( 'Parent Group:', 'oa-tools' ),
				'new_item_name' 				=> __( 'New Group Name', 'oa-tools' ),
				'add_new_item' 					=> __( 'Add New Group', 'oa-tools' ),
				'edit_item' 					=> __( 'Edit Group', 'oa-tools' ),
				'update_item' 					=> __( 'Update Group', 'oa-tools' ),
				'separate_items_with_commas' 	=> __( 'Separate groups with commas', 'oa-tools' ),
				'search_items' 					=> __( 'Search Group', 'oa-tools' ),
				'add_or_remove_items' 			=> __( 'Add or remove groups', 'oa-tools' ),
				'choose_from_most_used' 		=> __( 'Choose from the most used groups', 'oa-tools' ),
				'not_found' 					=> __( 'Not Found', 'oa-tools' ),
			);
		$args = array(
			'labels' 				=> $labels,
			'hierarchical' 			=> true,
			'public' 				=> true,
			'show_ui' 				=> true,
			'show_admin_column' 	=> true,
			'show_in_nav_menus' 	=> false,
			'show_tagcloud' 		=> false,
		);
		register_taxonomy( 'oaldr_group', array( 'oaldr_position' ), $args );
	}
	/**
	 * Register the chapter taxonomy.
	 *
	 * @since    1.0.0
	 */
	public function taxonomy_chapter() {
		$labels = array(
			'name' => _x( 'Chapters', 'Taxonomy General Name', 'oa-tools' ),
			'singular_name' => _x( 'Chapter', 'Taxonomy Singular Name', 'oa-tools' ),
			'menu_name' => __( 'Chapter', 'oa-tools' ),
			'all_items' => __( 'All Chapters', 'oa-tools' ),
			'parent_item' => __( 'Parent Chapter', 'oa-tools' ),
			'parent_item_colon' => __( 'Parent Chapter:', 'oa-tools' ),
			'new_item_name' => __( 'New Chapter Name', 'oa-tools' ),
			'add_new_item' => __( 'Add New Chapter', 'oa-tools' ),
			'edit_item' => __( 'Edit Chapter', 'oa-tools' ),
			'update_item' => __( 'Update Chapter', 'oa-tools' ),
			'separate_items_with_commas' => __( 'Separate groups with commas', 'oa-tools' ),
			'search_items' => __( 'Search Chapter', 'oa-tools' ),
			'add_or_remove_items' => __( 'Add or remove groups', 'oa-tools' ),
			'choose_from_most_used' => __( 'Choose from the most used groups', 'oa-tools' ),
			'not_found' => __( 'Not Found', 'oa-tools' ),
		);
		$args = array(
			'labels' 				=> $labels,
			'hierarchical' 			=> true,
			'public' 				=> true,
			'show_ui' 				=> true,
			'show_admin_column' 	=> true,
			'show_in_nav_menus' 	=> false,
			'show_tagcloud' 		=> false,
		);
		register_taxonomy( 'oaldr_chapter', array( 'oaldr_person' ), $args );
	}
	/**
	 * Register the Lodge taxonomy.
	 *
	 * @since    1.0.0
	 */
	public function taxonomy_lodge() {
		$labels = array(
			'name' => _x( 'Lodges', 'Taxonomy General Name', 'oa-tools' ),
			'singular_name' => _x( 'Lodge', 'Taxonomy Singular Name', 'oa-tools' ),
			'menu_name' => __( 'Lodge', 'oa-tools' ),
			'all_items' => __( 'All Lodges', 'oa-tools' ),
			'parent_item' => __( 'Parent Lodge', 'oa-tools' ),
			'parent_item_colon' => __( 'Parent Lodge:', 'oa-tools' ),
			'new_item_name' => __( 'New Lodge Name', 'oa-tools' ),
			'add_new_item' => __( 'Add New Lodge', 'oa-tools' ),
			'edit_item' => __( 'Edit Lodge', 'oa-tools' ),
			'update_item' => __( 'Update Lodge', 'oa-tools' ),
			'separate_items_with_commas' => __( 'Separate groups with commas', 'oa-tools' ),
			'search_items' => __( 'Search Lodge', 'oa-tools' ),
			'add_or_remove_items' => __( 'Add or remove groups', 'oa-tools' ),
			'choose_from_most_used' => __( 'Choose from the most used groups', 'oa-tools' ),
			'not_found' => __( 'Not Found', 'oa-tools' ),
		);
		$args = array(
			'labels' 				=> $labels,
			'hierarchical' 			=> true,
			'public' 				=> true,
			'show_ui' 				=> true,
			'show_admin_column' 	=> true,
			'show_in_nav_menus' 	=> false,
			'show_tagcloud' 		=> false,
		);
		register_taxonomy( 'oaldr_lodge', array( 'oaldr_person' ), $args );
	}
	/**
	 * Register the Section taxonomy.
	 *
	 * @since    1.0.0
	 */
	public function taxonomy_section() {
		$labels = array(
			'name' => _x( 'Sections', 'Taxonomy General Name', 'oa-tools' ),
			'singular_name' => _x( 'Section', 'Taxonomy Singular Name', 'oa-tools' ),
			'menu_name' => __( 'Section', 'oa-tools' ),
			'all_items' => __( 'All Sections', 'oa-tools' ),
			'parent_item' => __( 'Parent Section', 'oa-tools' ),
			'parent_item_colon' => __( 'Parent Section:', 'oa-tools' ),
			'new_item_name' => __( 'New Section Name', 'oa-tools' ),
			'add_new_item' => __( 'Add New Section', 'oa-tools' ),
			'edit_item' => __( 'Edit Section', 'oa-tools' ),
			'update_item' => __( 'Update Section', 'oa-tools' ),
			'separate_items_with_commas' => __( 'Separate groups with commas', 'oa-tools' ),
			'search_items' => __( 'Search Section', 'oa-tools' ),
			'add_or_remove_items' => __( 'Add or remove groups', 'oa-tools' ),
			'choose_from_most_used' => __( 'Choose from the most used groups', 'oa-tools' ),
			'not_found' => __( 'Not Found', 'oa-tools' ),
		);
		$args = array(
			'labels' 				=> $labels,
			'hierarchical' 			=> true,
			'public' 				=> true,
			'show_ui' 				=> true,
			'show_admin_column' 	=> true,
			'show_in_nav_menus' 	=> false,
			'show_tagcloud' 		=> false,
		);
		register_taxonomy( 'oaldr_section', array( 'oaldr_person' ), $args );
	}
	/**
	 * Register a taxonomy based on customizer option.
	 *
	 * @since    1.0.0
	 */
	public function taxonomy_register() {
		// Pick used category via Customizer.
			$selected_taxonomy = get_theme_mod( 'oaldr_categorize_positions' );

		if ( 'oaldr_lodge' === $selected_taxonomy  ) {
			add_action( 'init', array( $this, 'taxonomy_lodge' ), 0 );
		} elseif ( 'oaldr_section' === $selected_taxonomy  ) {
			add_action( 'init', array( $this, 'taxonomy_section' ), 0 );
		} else {
			add_action( 'init', array( $this, 'taxonomy_chapter' ), 0 );
		}
	}
}
