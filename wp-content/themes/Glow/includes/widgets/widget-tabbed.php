<?php class TabbedWidget extends WP_Widget
{
    function TabbedWidget(){
		$widget_ops = array('description' => 'Displays Recent-Popular-Comments widget');
		$control_ops = array('width' => 400, 'height' => 500);
		parent::WP_Widget(false,$name='ET Tabbed',$widget_ops,$control_ops);
	}

  /* Displays the Widget in the front-end */
    function widget($args, $instance){
		extract($args);
		$recentPostsNumber = empty($instance['recentPostsNumber']) ? '' : (int) $instance['recentPostsNumber'];
		$popularPostsNumber = empty($instance['popularPostsNumber']) ? '' : (int) $instance['popularPostsNumber'];
		$commentsNumber = empty($instance['commentsNumber']) ? '' : (int) $instance['commentsNumber'];
		
?>

<?php $width = 38;
	  $height = 38; ?>

<div class="custom-sidebar-block">
	<ul class="control">
		<li class="recent active"><a href="#recent-tabbed"><?php esc_html_e('Recent','Glow') ?></a></li>
		<li class="popular"><a href="#popular-tabbed"><?php esc_html_e('Popular','Glow') ?></a></li>
		<li class="comments"><a href="#comments-tabbed"><?php esc_html_e('Comments  ','Glow') ?></a></li>
	</ul>
	<div class="content">
		<div id="recent-tabbed">
			<ul>
				<?php query_posts("showposts=$recentPostsNumber"); ?>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<?php global $post; ?>
					
					<?php $titletext = get_the_title();	
						  $thumbnail = get_thumbnail($width,$height,'',$titletext,$titletext);
						  $thumb = $thumbnail["thumb"]; ?>
					
					<li>
						<?php if($thumb <> '') { ?>
							<a href="<?php the_permalink() ?>" title="<?php printf(esc_attr__ ('Permanent Link to %s', 'Glow'), get_the_title()) ?>">
								<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height); ?>
							</a>
						<?php }; ?>
						
						<h4><a href="<?php the_permalink() ?>" title="<?php printf(esc_attr__ ('Permanent Link to %s', 'Glow'), get_the_title()) ?>"><?php truncate_title(24); ?></a></h4>
						<p class="postinfo"><?php esc_html_e('Posted by ','Glow')?> <?php the_author_posts_link(); ?><?php esc_html_e(' on ','Glow')?><?php the_time(get_option('glow_date_format')) ?></p>
					</li>
				<?php endwhile; endif; wp_reset_query(); ?>
			</ul>
		</div> <!-- end recent -->

		<div id="popular-tabbed">
			<ul>
				<?php global $wpdb;
				$result = $wpdb->get_results("SELECT comment_count,ID,post_title FROM $wpdb->posts ORDER BY comment_count DESC LIMIT 0 , $popularPostsNumber");
				foreach ($result as $post) {
					#setup_postdata($post);
					$postid = $post->ID;
					$title = $post->post_title;
					$commentcount = $post->comment_count;
					if ($commentcount != 0) { ?>
						<?php query_posts("p=$postid"); ?>
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							<?php global $post; ?>
							<?php $titletext = get_the_title();	
							      $thumbnail = get_thumbnail($width,$height,'',$titletext,$titletext);
								  $thumb = $thumbnail["thumb"]; ?>
							
							<li>
								<?php if($thumb <> '') { ?>
									<a href="<?php the_permalink() ?>" title="<?php printf(esc_attr__ ('Permanent Link to %s', 'Glow'), get_the_title()) ?>">
										<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height); ?>
									</a>
								<?php }; ?>
								
								<h4><a href="<?php the_permalink() ?>" title="<?php printf(esc_attr__ ('Permanent Link to %s', 'Glow'), get_the_title()) ?>"><?php truncate_title(24); ?></a></h4>
								<p class="postinfo"><?php esc_html_e('Posted by ','Glow')?> <?php the_author_posts_link(); ?><?php esc_html_e(' on ','Glow')?><?php the_time(get_option('glow_date_format')) ?></p>
							</li>
						<?php endwhile; endif; wp_reset_query(); ?>
					<?php };
				}; ?>
			</ul>
		</div> <!-- end popular -->
		
		<div id="comments-tabbed">
			<?php
				global $wpdb;
				$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url, SUBSTRING(comment_content,1,30) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $commentsNumber";

				$comments = $wpdb->get_results($sql);
				$output = $pre_HTML;
				$output .= "\n<ul>";
				foreach ($comments as $comment) {
					$output .= "\n<li>".strip_tags($comment->comment_author) . esc_html__(' on ','Glow') . "<a href=\"" . get_permalink($comment->ID)."#comment-" . $comment->comment_ID . "\" title=\"on ".$comment->post_title . "\">" . strip_tags($comment->post_title)."</a></li>";
				}
				$output .= "\n</ul>";
				$output .= $post_HTML;
				echo $output;
			?>
		</div> <!-- end comments -->
	</div>
</div> <!-- end custom-sidebar-block-->

<div class="clear"></div>
<?php
	}

  /*Saves the settings. */
    function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['recentPostsNumber'] = stripslashes($new_instance['recentPostsNumber']);
		$instance['popularPostsNumber'] = stripslashes($new_instance['popularPostsNumber']);
		$instance['commentsNumber'] = stripslashes($new_instance['commentsNumber']);

		return $instance;
	}

  /*Creates the form for the widget in the back-end. */
    function form($instance){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array('recentPostsNumber'=>'3', 'popularPostsNumber'=>'3', 'commentsNumber'=>'3') );

		$recentPostsNumber = (int) $instance['recentPostsNumber'];
		$popularPostsNumber = (int) $instance['popularPostsNumber'];
		$commentsNumber = (int) $instance['commentsNumber'];
		
		# Number of Recent Posts
		echo '<p><label for="' . $this->get_field_id('recentPostsNumber') . '">' . 'Number of Recent Posts:' . '</label><input class="widefat" id="' . $this->get_field_id('recentPostsNumber') . '" name="' . $this->get_field_name('recentPostsNumber') . '" type="text" value="' . esc_attr($recentPostsNumber) . '" /></p>';
		
		# Number of Popular Posts
		echo '<p><label for="' . $this->get_field_id('popularPostsNumber') . '">' . 'Number of Popular Posts:' . '</label><input class="widefat" id="' . $this->get_field_id('popularPostsNumber') . '" name="' . $this->get_field_name('popularPostsNumber') . '" type="text" value="' . esc_attr($popularPostsNumber) . '" /></p>';
		
		# Number of Comments
		echo '<p><label for="' . $this->get_field_id('commentsNumber') . '">' . 'Number of Comments:' . '</label><input class="widefat" id="' . $this->get_field_id('commentsNumber') . '" name="' . $this->get_field_name('commentsNumber') . '" type="text" value="' . esc_attr($commentsNumber) . '" /></p>'; 
		
	}

}// end TabbedWidget class

function TabbedWidgetInit() {
	register_widget('TabbedWidget');
}

add_action('widgets_init', 'TabbedWidgetInit');

?>