			<!-- Featured Area -->
			<div id="featured-area">
				<div id="s1" class="pics"> 
<?php global $ids; $ids = array(); $arr = array(); $i=1;
query_posts("showposts=3&cat=".get_catId(get_option('glow_feat_cat')));
while (have_posts()) : the_post(); ?>

	<?php $titletext = get_the_title();
	
		  $thumbnail = get_thumbnail(630,298,'',$titletext,$titletext);
		  $thumb = $thumbnail["thumb"]; 
		  
		  $thumbnail2 = get_thumbnail(67,67,'',$titletext,$titletext);
		  $thumb2 = $thumbnail2["thumb"]; ?>
	
					<div>
						<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, 630, 298, ''); ?>
						<div class="excerpt">
							<p><?php truncate_post(190,true); ?></p>
						</div>
						<a href="<?php the_permalink() ?>" title="<?php printf(esc_attr__ ('Permanent Link to %s', 'Glow'), get_the_title()) ?>"><span class="feat-overlay"></span></a>
					</div>
	
	<?php $arr[$i]["thumb"] = $thumb2;
		  $arr[$i]["use_timthumb"] = $thumbnail2["use_timthumb"];
		  
		  $arr[$i]["title"] = truncate_title(50,false);
		  $arr[$i]["fulltitle"] = truncate_title(250,false);
		  $arr[$i]["postinfo"] = esc_html__("Posted by", "Glow")." ". get_the_author_meta('display_name') . esc_html__(' on ','Glow') . get_the_time(get_option('glow_date_format'));
	$i++; ?>
	<?php $ids[]= $post->ID;
endwhile; wp_reset_query(); ?>

				</div> <!-- end .pics -->

				<div id="slider-control">
<?php for ($i = 1; $i <= 3; $i++) {
		if ($arr[$i]["thumb"] <> '' ) { ?>				
				
					<div class="featitem <?php if ($i==1) echo("active");?>">
						<?php print_thumbnail($arr[$i]["thumb"], $arr[$i]["use_timthumb"], $titletext, 67, 67, ''); ?>
						
						<h2><?php echo esc_html($arr[$i]["title"]);?></h2>
						<span class="order"><?php echo($i); ?></span>
					</div> <!-- end .featitem -->
					
		<?php } else {
			continue;
	    }
} ?>				
				</div> <!-- end slider-control div -->
								
				<div class="clear"></div>
			</div> <!-- end featured area -->