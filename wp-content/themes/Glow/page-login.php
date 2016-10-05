<?php 
/*
Template Name: Login Page
*/
?>
<?php 
	$et_ptemplate_settings = array();
	$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );
	
	$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : false;
?>

<?php get_header(); ?>
<div id="main-area-wrap">
	<div id="wrapper">
		<div id="main">
			<div class="post">
<div class="new-post">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<h1><?php the_title() ?></h1>
	<div id="post-content">
	
		<?php $width = get_option('glow_thumbnail_width_pages');
			  $height = get_option('glow_thumbnail_height_pages');
			  $classtext = 'thumbnail alignleft';
			  $titletext = get_the_title();
			
			  $thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
			  $thumb = $thumbnail["thumb"];  ?>
		
		<?php if($thumb <> '' && get_option('glow_page_thumbnails') == 'on') { ?>
			<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
		<?php }; ?>
		
		<?php the_content(); ?>
		
		<div id="et-login">
			<div class='et-protected'>
				<div class='et-protected-form'>
					<form action='<?php echo home_url(); ?>/wp-login.php' method='post'>
						<p><label><?php esc_html_e('Username','Glow'); ?>: <input type='text' name='log' id='log' value='<?php echo esc_attr($user_login); ?>' size='20' /></label></p>
						<p><label><?php esc_html_e('Password','Glow'); ?>: <input type='password' name='pwd' id='pwd' size='20' /></label></p>
						<input type='submit' name='submit' value='Login' class='etlogin-button' />
					</form> 
				</div> <!-- .et-protected-form -->
				<p class='et-registration'><?php esc_html_e('Not a member?','Glow'); ?> <a href='<?php echo site_url('wp-login.php?action=register', 'login_post'); ?>'><?php esc_html_e('Register today!','Glow'); ?></a></p>
			</div> <!-- .et-protected -->
		</div> <!-- end #et-login -->
		
		<div class="clear"></div>
		
		<?php edit_post_link(esc_html__('Edit this page','Glow')); ?>
		<div class="clear"></div>
	</div> <!-- end post-content -->
<?php endwhile; endif; ?>
</div> <!-- end new-post -->	

			</div> <!-- end post -->
		</div> <!-- end main -->
<?php if (!$fullwidth) get_sidebar(); ?>
<?php get_footer(); ?>