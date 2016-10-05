<?php
/**
 * Contents of the [leadership-positions] shortcode.
 *
 * @package OA Tools
 */

	$atts = shortcode_atts( array(
		'type' 		=> 'oaldr_position',
		'order' 	=> 'DESC',
		'orderby' 	=> 'menu_order',
		'posts' 	=> -1,
	), $atts);

	$options = array(
		'post_type'      => $atts['type'],
		'order'          => $atts['order'],
		'orderby'        => $atts['orderby'],
		'posts_per_page' => $atts['posts'],
		'meta_key'     => 'position_status',
		'meta_value'   => 'hidden',
		'meta_compare' => '!=',
	);

	$selected_taxonomy 	= get_theme_mod( 'oaldr_categorize_positions' );
	$query 				= new WP_Query( $options );

	if ( $query->have_posts() ) {
		?>

	<section class="oaldr-positions">

	<?php
	while ( $query->have_posts() ) : $query->the_post();

		$person = current( get_field( 'person' ) );

		$thumb_src = null;
		if ( has_post_thumbnail( $person ) ) {
			$src        = wp_get_attachment_image_src( get_post_thumbnail_id( $person ), 'medium' );
			$thumb_src  = $src[0];
		} else {
			$thumb_src = get_theme_mod( 'oaldr_headshot_default' );
		}

		$first_name        = get_field( 'first_name', $person );
		$last_name         = get_field( 'last_name', $person );
		$youth_or_adviser  = get_field( 'youth_or_adviser', $person );
		$taxonomy_array    = wp_get_post_terms( $person, $selected_taxonomy );
		$taxonomy          = current( $taxonomy_array );
		$membership_level  = get_field( 'membership_level', $person );
		$phone_number      = get_field( 'phone_number', $person );
		$last_initial_only = get_field( 'last_initial_only', $person );
		$group             = current( wp_get_post_terms( get_the_id(), 'oaldr_group' ) );
		$available         = get_field( 'is_position_available' );
		$css_classes       = array( 'oaldr-position' );
		if ( ! empty( $group ) ) {
			$css_classes[] = 'group-' . strtolower( $group->slug );
		}

		if ( get_field( 'position_email' ) ) {
			$email = get_field( 'position_email' );
		} else {
			$email = get_field( 'person_email', $person );
		}

		if ( true === $available ) {
			$css_classes[] = 'open';
		}

		if ( true === $last_initial_only ) {
			$lname_final = substr( $last_name, 0, 1 );
		} else {
			$lname_final = $last_name;
		};

		$person_name = $first_name.' '.$lname_final;

		?>

		<div class="<?php esc_attr_e( implode( ' ', $css_classes ) );?>" data-lname="<?php esc_attr_e( $lname_final );?>">

			<div class="oaldr-position-header">
				<img src="<?php esc_attr_e( $thumb_src );?>" alt="<?php esc_attr_e( $person_name );?> Headshot" class="img-circle">
				<?php if ( current_user_can( 'edit_posts' ) ) : ?>
					<a href="<?php esc_attr_e( get_edit_post_link() );?>" target="_blank" title="Edit Position"><div class="dashicons dashicons-groups"></div></a>
					<a href="<?php esc_attr_e( get_edit_post_link( $person ) );?>" target="_blank" title="Edit Person"><div class="dashicons dashicons-admin-users edit-person"></div></a>
				<?php endif;?>
			</div>

			<div class="oaldr-position-content">
				<p class="oaldr-position-title"><?php the_title();?></p>
				<h3>
				<?php
				if ( true === $available ) {
					echo 'Position Available';
				} else {
					esc_attr_e( $person_name );
				}
				?>
				</h3>
				<?php if ( ! empty( $taxonomy ) ) { ?>
					<p class="oaldr-level-and-group"><?php esc_html_e( $membership_level );?> Member of <?php esc_html_e( $taxonomy->name );?></p>
				<?php }?>
				<?php if ( is_user_logged_in() ) { ?>
					<?php if ( ! empty( $phone_number ) ) { ?>
						<p class="oaldr-phone"><a href="tel:<?php esc_attr_e( $phone_number );?>"><span class="dashicons dashicons-phone"></span> <?php esc_html_e( $phone_number );?></a></p>
					<?php } ?>
				<?php } ?>
				<p class="oaldr-email"><a href="mailto:<?php esc_attr_e( antispambot( $email ) );?>"><span class="dashicons dashicons-email"></span> <?php esc_html_e( antispambot( $email ) );?></a></p>
			</div>
		</div>

		<?php
		endwhile;
		wp_reset_postdata();
		?>

		</section>

	<?php

	}
?>
