<?php get_header(); ?>
<?php if (get_option('glow_featured') == 'on') get_template_part('includes/featured'); ?>
<div id="main-area-wrap">
	<div id="wrapper">
		<div id="main">
	<?php if (get_option('glow_blog_style') == 'on') { get_template_part('includes/blogstyle'); 
		  } else { get_template_part('includes/defaulthome'); } ?>
		</div> <!-- end main -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>