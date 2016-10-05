<?php
function oaldr_custom_taxonomy_group() {

	$labels = array(
		'name'                       => _x( 'Groups', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Group', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Group', 'text_domain' ),
		'all_items'                  => __( 'All Groups', 'text_domain' ),
		'parent_item'                => __( 'Parent Group', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Group:', 'text_domain' ),
		'new_item_name'              => __( 'New Group Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Group', 'text_domain' ),
		'edit_item'                  => __( 'Edit Group', 'text_domain' ),
		'update_item'                => __( 'Update Group', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate groups with commas', 'text_domain' ),
		'search_items'               => __( 'Search Group', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove groups', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used groups', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'oaldr_group', array( 'oaldr_position' ), $args );

}

add_action( 'init', 'oaldr_custom_taxonomy_group', 0 );

function oaldr_custom_taxonomy_chapter() {

	$labels = array(
		'name'                       => _x( 'Chapters', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Chapter', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Chapter', 'text_domain' ),
		'all_items'                  => __( 'All Chapters', 'text_domain' ),
		'parent_item'                => __( 'Parent Chapter', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Chapter:', 'text_domain' ),
		'new_item_name'              => __( 'New Chapter Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Chapter', 'text_domain' ),
		'edit_item'                  => __( 'Edit Chapter', 'text_domain' ),
		'update_item'                => __( 'Update Chapter', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate groups with commas', 'text_domain' ),
		'search_items'               => __( 'Search Chapter', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove groups', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used groups', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'oaldr_chapter', array( 'oaldr_person' ), $args );

}

function oaldr_custom_taxonomy_lodge() {

	$labels = array(
		'name'                       => _x( 'Lodges', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Lodge', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Lodge', 'text_domain' ),
		'all_items'                  => __( 'All Lodges', 'text_domain' ),
		'parent_item'                => __( 'Parent Lodge', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Lodge:', 'text_domain' ),
		'new_item_name'              => __( 'New Lodge Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Lodge', 'text_domain' ),
		'edit_item'                  => __( 'Edit Lodge', 'text_domain' ),
		'update_item'                => __( 'Update Lodge', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate groups with commas', 'text_domain' ),
		'search_items'               => __( 'Search Lodge', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove groups', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used groups', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'oaldr_lodge', array( 'oaldr_person' ), $args );

}

function oaldr_custom_taxonomy_section() {

	$labels = array(
		'name'                       => _x( 'Sections', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Section', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Section', 'text_domain' ),
		'all_items'                  => __( 'All Sections', 'text_domain' ),
		'parent_item'                => __( 'Parent Section', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Section:', 'text_domain' ),
		'new_item_name'              => __( 'New Section Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Section', 'text_domain' ),
		'edit_item'                  => __( 'Edit Section', 'text_domain' ),
		'update_item'                => __( 'Update Section', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate groups with commas', 'text_domain' ),
		'search_items'               => __( 'Search Section', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove groups', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used groups', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'oaldr_section', array( 'oaldr_person' ), $args );

}

// Pick used category via Customizer
$selected_taxonomy = get_theme_mod('oaldr_categorize_positions');

if ($selected_taxonomy == 'lodge') {
	add_action( 'init', 'oaldr_custom_taxonomy_lodge', 0 );
} else if ($selected_taxonomy == 'chapter') {
	add_action( 'init', 'oaldr_custom_taxonomy_chapter', 0 );
} else if ($selected_taxonomy == 'section') {
	add_action( 'init', 'oaldr_custom_taxonomy_section', 0 );
}

// limit number of categories a person can have
add_filter('wp_terms_checklist_args', 'oaldr_select_one_category');
function oaldr_select_one_category($args) {
    if (isset($args["taxonomy"]) && $args["taxonomy"] == ("lodge" || "chapter" || "section") ) {
        $args["walker"] = new Walker_Category_Radios;
        $args["checked_ontop"] = false;
    }
    return $args;
}
class Walker_Category_Radios extends Walker {
    var $tree_type = "category";
    var $db_fields = array ("parent" => "parent", "id" => "term_id");
    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent<ul class=\"children\">\n";
    }
    function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    function start_el(&$output, $category, $depth, $args, $id = 0) {
        extract($args);
        if (empty($taxonomy)) {
            $taxonomy = "category";
        }
        if ($taxonomy == "category") {
            $name = "post_category";
        } else {
            $name = "tax_input[" . $taxonomy . "]";
        }
        $class = in_array($category->term_id, $popular_cats) ? " class=\"popular-category\"" : "";
        $output .= "\n<li id=\"{$taxonomy}-{$category->term_id}\"$class>" . "<label class=\"selectit\"><input value=\"" . $category->term_id . "\" type=\"radio\" name=\"" . $name . "[]\" id=\"in-" . $taxonomy . "-" . $category->term_id . "\"" . checked(in_array($category->term_id, $selected_cats), TRUE, FALSE) . disabled(empty($args["disabled"]), FALSE, FALSE) . " /> " . esc_html( apply_filters("the_category", $category->name)) . "</label>";
    }
    function end_el(&$output, $category, $depth = 0, $args = array()) {
        $output .= "</li>\n";
    }
}