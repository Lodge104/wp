<?php $homepage=false;
if (is_home()) {
	global $ids;
	if (get_option('glow_duplicate') == 'false') {
		$args=array(
		   'showposts'=> (int) get_option('glow_homepage_posts'),
		   'post__not_in' => $ids,
		   'paged'=>$paged,
		   'category__not_in' => (array) get_option('glow_exlcats_recent'),
		);
	} else {
		$args=array(
		   'showposts'=> (int) get_option('glow_homepage_posts'),
		   'paged'=>$paged,
		   'category__not_in' => (array) get_option('glow_exlcats_recent'),
		);
	};
	$homepage=true;
	query_posts($args);
}

if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php $width = 156;
	  $height = 156;
	  $classtext = 'thumbnail alignleft';
	  $titletext = get_the_title();
	
	  $thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
	  $thumb = $thumbnail["thumb"]; ?>

		<div class="post blogstyle<?php if (!($homepage)) echo(" indexpage"); ?>">
			<div class="new-post">
				<?php get_template_part('includes/postinfo'); ?>
				<h2><a href="<?php the_permalink() ?>" title="<?php printf(esc_attr__ ('Permanent Link to %s', 'Glow'), get_the_title()) ?>"><?php the_title(); ?></a></h2>
                
				<?php if (get_option('glow_thumbnails') == 'on') { ?>
					<?php if ($thumb != '') { ?>
						<a href="<?php the_permalink() ?>" title="<?php printf(esc_attr__ ('Permanent Link to %s', 'Glow'), get_the_title()) ?>">
							<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
						</a>
				   <?php };
				}; ?>
				
				<p><?php the_content(""); ?></p>
				<a href="<?php the_permalink() ?>" class="readmore"><span><?php esc_html_e('Read More','Glow'); ?></span></a>
				<div class="clear"></div>
			</div> <!-- end new-post -->	
		</div> <!-- end post -->	
<?php endwhile; ?>

<div class="clear"></div>
	<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } 
	else { ?>
	<div class="pagination">
		<div class="alignleft"><?php next_posts_link(esc_html__('&laquo; Older Entries','Glow')) ?></div>
		<div class="alignright"><?php previous_posts_link(esc_html__('Next Entries &raquo;', 'Glow')) ?></div>
	</div>
	<?php } ?>
<?php else : ?>
	<?php get_template_part('includes/no-results'); ?>
<?php endif; wp_reset_query(); ?>