<?php
if ( function_exists('register_sidebar') )
    register_sidebar(array(
	'name' => 'Sidebar',
    'before_widget' => '<div class="sidebar-block">',
    'after_widget' => '</div> <!-- end sidebar block -->',
    'before_title' => '<h3 class="sidebar-title">',
    'after_title' => '</h3>',
    ));
?>