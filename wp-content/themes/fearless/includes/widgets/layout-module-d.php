<?php

class fearless_layout_module_d extends WP_Widget {

	function fearless_layout_module_d() {
		parent::WP_Widget( false, $name = __( 'Fearless Layout Module D', 'fearless' ) );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		if ( $instance['category_id'] )
			$category_data = get_category( (int) $instance['category_id'] );
		
		if ( ! $title && isset( $category_data ) )
			$title = $category_data->name;
		
		if ( isset( $category_data ) )
			$title = '<a href="' . get_category_link( $category_data->term_id ) . '">' . $title . ' <span>&raquo;</span></a>';

		if ( $category_data ) {
			$category_accent_color = fearless_get_category_meta_hierarchical( $instance['category_id'], 'fearless_accent_color' );
			$before_title = str_replace( '<h1', '<h1 style="border-color: ' . $category_accent_color . ';"', $before_title );
			$before_title = str_replace( '<span>', '<span style="background: ' . $category_accent_color . ';">', $before_title );
		}

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
			
		echo '<div class="column boxed">';

		$query_args = array(
			'posts_per_page' => $instance['posts_count'],
			'ignore_sticky_posts' => 1
		);
		if ( $instance['category_id'] )
			$query_args['cat'] = $instance['category_id'];
		$query = new WP_Query( $query_args );
		if ( $query->have_posts() ) :

			$i = 1;
			while( $query->have_posts() ) : $query->the_post();

				$css_class = 'mini';
				$thumbnail_size = 'thumb-83';
				$excerpt_length = false;
				fearless_post_teaser( array( 'class' => $css_class, 'thumbnail' => $thumbnail_size, 'excerpt_length' => $excerpt_length ) );

				if ( ( $instance['posts_count'] / 2 ) == $i )
					echo '</div><div class="column boxed last">';

				$i++;

			endwhile;
			wp_reset_postdata();

		endif;
		
		echo '</div>';
		
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$clean['title'] = strip_tags( $new_instance['title'] );
		$clean['category_id'] = strip_tags( $new_instance['category_id'] );
		$clean['posts_count'] = strip_tags( $new_instance['posts_count'] );
		return $clean;
	}

	function form( $instance ) {
		if ( ! isset( $instance['title'] ) )
			$instance['title'] = null;
		if ( ! isset( $instance['category_id'] ) )
			$instance['category_id'] = null;
		if ( ! isset( $instance['posts_count'] ) )
			$instance['posts_count'] = 6;

		$title = esc_attr( $instance['title'] );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title'); ?>"><?php _e( 'Title', 'fearless' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title'); ?>" name="<?php echo $this->get_field_name( 'title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'category_id'); ?>"><?php _e( 'Category', 'fearless' ); ?></label>
			<?php wp_dropdown_categories( array(
				'name' => $this->get_field_name( 'category_id' ),
				'id' => $this->get_field_id( 'category_id' ),
				'show_option_all' => __( 'All Categories', 'fearless' ),
				'selected' => $instance['category_id'],
				'hide_empty' => 0,
				'hierarchical' => 1
			) ); ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'posts_count'); ?>"><?php _e( 'Number of Posts', 'fearless' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'posts_count'); ?>" name="<?php echo $this->get_field_name( 'posts_count'); ?>">
				<option value="2"<?php selected( $instance['posts_count'], 2 ); ?>>2</option>
				<option value="4"<?php selected( $instance['posts_count'], 4 ); ?>>4</option>
				<option value="6"<?php selected( $instance['posts_count'], 6 ); ?>>6</option>
				<option value="8"<?php selected( $instance['posts_count'], 8 ); ?>>8</option>
				<option value="10"<?php selected( $instance['posts_count'], 10 ); ?>>10</option>
			</select>
		</p>
		<?php
	}

}
add_action( 'widgets_init', create_function( '', 'return register_widget( "fearless_layout_module_d" );' ) );
