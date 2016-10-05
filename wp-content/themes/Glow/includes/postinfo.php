<?php if (!is_single() && get_option('glow_postinfo1') <> '') { ?>

	<?php if (in_array('date', get_option('glow_postinfo1'))) { ?>
		<div class="date">
			<div class="main">
				<div class="rightside">
					<span><?php the_time(get_option('glow_date_format')) ?></span>
				</div>
			</div>
		</div> <!-- end date -->
	<?php }; ?>	
	
	<?php if (in_array('author', get_option('glow_postinfo1')) || in_array('comments', get_option('glow_postinfo1')) || in_array('categories', get_option('glow_postinfo1')) ) { ?>
		<p class="info"><?php esc_html_e('Posted ','Glow'); ?><?php 
			if (in_array('author', get_option('glow_postinfo1'))) { esc_html_e(' by ','Glow'); the_author_posts_link(); }; 
			if (in_array('categories', get_option('glow_postinfo1'))) { esc_html_e(' in ','Glow'); the_category(', '); }; 
			if (in_array('comments', get_option('glow_postinfo1'))) { echo(" | "); comments_popup_link(esc_html__('0 Comments','Glow'), esc_html__('1 Comment','Glow'), esc_html__('% Comments','Glow')); }; ?></p>
	<?php }; ?>
		
<?php } elseif (is_single() && get_option('glow_postinfo2') <> '') { ?>

	 <?php if (in_array('date', get_option('glow_postinfo2'))) { ?>
		<div class="date">
			<div class="main">
				<div class="rightside">
					<span><?php the_time(get_option('glow_date_format')) ?></span>
				</div>
			</div>
		</div> <!-- end date -->
	<?php }; ?>	
	
	<?php if (in_array('author', get_option('glow_postinfo2')) || in_array('comments', get_option('glow_postinfo2')) || in_array('categories', get_option('glow_postinfo2')) ) { ?>
		<p class="info"><?php esc_html_e('Posted ','Glow'); ?><?php 
			if (in_array('author', get_option('glow_postinfo2'))) { esc_html_e(' by ','Glow'); the_author_posts_link(); }; 
			if (in_array('categories', get_option('glow_postinfo2'))) { esc_html_e(' in ','Glow'); the_category(', '); }; 
			if (in_array('comments', get_option('glow_postinfo2'))) { echo(" | "); comments_popup_link(esc_html__('0 Comments','Glow'), esc_html__('1 Comment','Glow'), esc_html__('% Comments','Glow')); }; ?></p>
	<?php }; ?>
		
<?php }; ?>