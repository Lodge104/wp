<?php

function oaldr_post_type_person() {

	$labels = array(
		'name'                => 'People',
		'singular_name'       => 'Person',
		'menu_name'           => 'People',
		'parent_item_colon'   => 'Parent Item:',
		'all_items'           => 'All People',
		'view_item'           => 'View Person',
		'add_new_item'        => 'Add Person',
		'add_new'             => 'Add Person',
		'edit_item'           => 'Edit Person',
		'update_item'         => 'Update Person',
		'search_items'        => 'Search People',
		'not_found'           => 'Not found',
		'not_found_in_trash'  => 'Not found in Trash',
	);
	$args = array(
		'label'               => 'people',
		'description'         => 'People ',
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-admin-users',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'rewrite'             => false,
		'capability_type'     => 'page',
	);
	register_post_type( 'oaldr_person', $args );

}

add_action( 'init', 'oaldr_post_type_person', 0 );

function oaldr_post_type_position() {

	$labels = array(
		'name'                => 'Positions',
		'singular_name'       => 'Position',
		'menu_name'           => 'Position',
		'parent_item_colon'   => 'Parent Item:',
		'all_items'           => 'All Positions',
		'view_item'           => 'View Position',
		'add_new_item'        => 'Add Position',
		'add_new'             => 'Add Position',
		'edit_item'           => 'Edit Position',
		'update_item'         => 'Update Position',
		'search_items'        => 'Search Position',
		'not_found'           => 'Not found',
		'not_found_in_trash'  => 'Not found in Trash',
	);
	$args = array(
		'label'               => 'position',
		'description'         => 'Positions',
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-groups',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'rewrite'             => false,
		'capability_type'     => 'page',
	);
	register_post_type( 'oaldr_position', $args );

}

add_action( 'init', 'oaldr_post_type_position', 0 );