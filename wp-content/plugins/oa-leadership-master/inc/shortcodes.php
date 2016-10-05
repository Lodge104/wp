<?php
add_shortcode( 'leadership-position-filter', 'oaldr_filter' );
function oaldr_filter( $atts ) {
	ob_start();
	extract( shortcode_atts( array (
		'type' => 'oaldr_position',
		'order' => 'DESC',
		'orderby' => 'menu_order',
		'posts' => -1,
		'offset' => 0
	), $atts ) );
	$term_args = array(
		'parent' => '0'
		);
	 $terms = get_terms("oaldr_group", $term_args);
	 if ( !empty( $terms ) && !is_wp_error( $terms ) ){ ?>
	 	<div class="oaldr-filter-buttons">
			<div class="group">
				<label>Group</label>
				<a class="filter" data-filter="all" href="#">All</a>	 	
			<?php     
				foreach ( $terms as $term ) { 
			?>
				<a class="filter" data-filter=".group-<?php echo $term->slug; ?>" href="#"><?php echo $term->name; ?></a>
			<?php } ?>
			</div>
			<div class="group">
				<label>Sort</label>
				<a class="sort active" data-sort="default" href="#">by Position</a>						
				<a class="sort" data-sort="lname:asc" href="#">by Last Name</a>
			</div>
	     </div>

<?php

	}

	$myvariable = ob_get_clean();
	return $myvariable;
	}

add_shortcode( 'leadership-positions', 'oaldr_positions' );
function oaldr_positions( $atts ) {
	ob_start();
	extract( shortcode_atts( array (
		'type' => 'oaldr_position',
		'order' => 'DESC',
		'orderby' => 'menu_order',
		'posts' => -1,
	), $atts ) );
	$options = array(
		'post_type' => $type,
		'order' => $order,
		'orderby' => $orderby,
		'posts_per_page' => $posts,
	);
	$query = new WP_Query( $options );
	if ( $query->have_posts() ) { 
		wp_enqueue_script('mixitup');
		wp_enqueue_script('oaldr-main');
		wp_enqueue_style('oaldr-styles');
?>
	<section class="oaldr-positions">
<?php 
	while ( $query->have_posts() ) : $query->the_post(); 
		
		$person = get_field('person')[0];

		$thumb_src = null;
		if ( has_post_thumbnail($person) ) {
			$src = wp_get_attachment_image_src( get_post_thumbnail_id( $person ), 'medium' );
			$thumb_src = $src[0];
		}

		else $thumb_src = get_theme_mod('oaldr_headshot_default');

		$first_name = get_field('first_name', $person);
		$last_name = get_field('last_name', $person);
		$youth_or_adviser = get_field('youth_or_adviser', $person);
		$chapter_array = wp_get_post_terms($person, 'oaldr_chapter');
		$chapter = $chapter_array[0];
		$membership_level = get_field('membership_level', $person);
		$phone_number = get_field('phone_number', $person);
		$last_initial_only = get_field('last_initial_only', $person);
		$group = wp_get_post_terms( get_the_id(), 'oaldr_group');

		if ( get_field('position_email') ) {
			$email = get_field('position_email');
		} else {
			$email = get_field('person_email', $person);
		}

		if (get_field('is_position_available') == true) {
			$available = 'open';
		} else {
			$available = '';
		};

		if ( $last_initial_only == true ) {
			$lname_final = substr($last_name, 0, 1);
		} else {
			$lname_final = $last_name;
		};
?>

<article class="oaldr-position <?php echo ' group-' . strtolower($group[0]->slug); ?> <?php echo $available; ?>" data-lname="<?php echo $lname_final; ?>">

	<div class="oaldr-position-header">
		<img src="<?php echo $thumb_src; ?>" alt="<?php echo $fname.' '.$lname_final; ?> Headshot" class="img-circle">
		<?php if (current_user_can('edit_posts')) : ?>
			<a href="<?php echo get_edit_post_link(); ?>" target="_blank" title="Edit Position"><div class="dashicons dashicons-groups"></div></a>
			<a href="<?php echo get_edit_post_link($person); ?>" target="_blank" title="Edit Person"><div class="dashicons dashicons-admin-users edit-person"></div></a>
		<?php endif; ?>
	</div>
	
	<div class="oaldr-position-content">
		<p class="oaldr-position-title"><?php the_title(); ?></p>
		<h3>
		<?php if ($available == 'true') {
			echo "Position Available";
		} else {
			echo $first_name.' '.$lname_final;
		}
		?>
		</h3>
		<?php if (!empty($chapter)) : ?>
			<p class="oaldr-level-and-group"><?php echo $membership_level; ?> Member of <?php echo $chapter->name; ?></p>
		<?php endif; ?>	
		<?php if ( is_user_logged_in() ) : ?>	
			<?php if (!empty($phone_number)) : ?>
				<p class="oaldr-phone"><a href="tel:<?php echo $phone_number; ?>"><span class="dashicons dashicons-phone"></span> <?php echo $phone_number; ?></a></p>
			<?php endif; ?>
		<?php endif; ?>	
		<p class="oaldr-email"><a href="mailto:<?php echo antispambot( $email ); ?>"><span class="dashicons dashicons-email"></span> <?php echo antispambot( $email ); ?></a></p>
	</div>
</article>

<?php endwhile;
wp_reset_postdata(); ?>

</section>
<?php
		$myvariable = ob_get_clean();
		return $myvariable;
	}
}