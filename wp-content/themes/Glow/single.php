<?php get_header(); ?>

<div id="main-area-wrap">
	<div id="wrapper">
		<div id="main">
			<div class="post">
			
				<?php if (get_option('glow_integration_single_top') <> '' && get_option('glow_integrate_singletop_enable') == 'on') echo(get_option('glow_integration_single_top')); ?>	
				
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<div class="new-post">
						<?php get_template_part('includes/postinfo'); ?>
						<h1><?php the_title(); ?></h1>
						<div id="post-content"> 
		
							<?php if (get_option('glow_thumbnails') == 'on') { ?>
								
								<?php $width = (int) get_option('glow_thumbnail_width_posts');
									  $height = (int) get_option('glow_thumbnail_height_posts');
									  $classtext = 'thumbnail alignleft';
									  $titletext = get_the_title();
									
									  $thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
									  $thumb = $thumbnail["thumb"]; ?>

								<?php if($thumb <> '') { ?>
									<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
								<?php };
								
							}; ?>
							<?php the_content(); ?>
							<?php edit_post_link(esc_html__('Edit this page','Glow')); ?>
							
						</div> <!-- end #post-content -->	
					</div> <!-- end new-post -->	
						
					<?php if (get_option('glow_integration_single_bottom') <> '' && get_option('glow_integrate_singlebottom_enable') == 'on') echo(get_option('glow_integration_single_bottom')); ?>		
					<?php if (get_option('glow_468_enable') == 'on') { ?>
						<a href="<?php echo esc_url(get_option('glow_468_url')); ?>"><img src="<?php echo esc_url(get_option('glow_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
					<?php } ?>
					
					<?php if (get_option('glow_show_postcomments') == 'on') comments_template('', true); ?>
            
				<?php endwhile; ?>

				<?php else : ?>
					<!--If no results are found-->
					<div id="post-content"> 
						<h1><?php esc_html_e('No Results Found','Glow'); ?></h1>
						<p><?php esc_html_e('The page you requested could not be found. Try refining your search, or use the navigation above to locate the post.','Glow'); ?></p>
					</div>
					<!--End if no results are found-->
				<?php endif; ?>
			</div> <!-- end post -->
		</div> <!-- end main -->
		
<?php get_sidebar(); ?>
<?php get_footer(); ?>