<?php
/*
 * lastnews section
 */
$mp_emmet_lastnews_animation_description = get_theme_mod( 'theme_lastnews_animation_description', 'fadeInRight' );
$mp_emmet_lastnews_animation             = get_theme_mod( 'theme_lastnews_animation', 'fadeInLeft' );
$mp_emmet_lastnews_id_option             = esc_attr( get_theme_mod( 'theme_lastnews_id' ) );
$mp_emmet_lastnews_id                    = empty( $mp_emmet_lastnews_id_option ) ? 'lastnews' : get_theme_mod( 'theme_lastnews_id' );

?>
	<section id="<?php echo esc_attr($mp_emmet_lastnews_id); ?>" class="lastnews-section grey-section default-section">
		<div class="container">
			<div class="section-content">
				<?php
				$mp_emmet_lastnews_title        = get_theme_mod( 'theme_lastnews_title' );
				$mp_emmet_lastnews_description  = get_theme_mod( 'theme_lastnews_description' );
				$mp_emmet_lastnews_button_url   = get_theme_mod( 'theme_lastnews_button_url', '#lastnews' );
				$mp_emmet_lastnews_button_label = get_theme_mod( 'theme_lastnews_button_label', __( 'view all posts', 'emmet-lite' ) );
				if ( get_theme_mod( 'theme_lastnews_title', false ) === false ) :
					?>
					<h2 class="section-title"><?php esc_html_e( 'blog posts', 'emmet-lite' ); ?></h2>
					<?php
				else:
					if ( ! empty( $mp_emmet_lastnews_title ) ):
						?>
						<h2 class="section-title"><?php echo esc_html($mp_emmet_lastnews_title); ?></h2>
						<?php
					endif;
				endif;
				if ( get_theme_mod( 'theme_lastnews_description', false ) === false ) :
				?>
				<?php if ( $mp_emmet_lastnews_animation_description === 'none' ): ?>
				<div class="section-description">
					<?php else: ?>
					<div class="section-description animated anHidden"
					     data-animation="<?php echo esc_attr($mp_emmet_lastnews_animation_description); ?>">
						<?php endif; ?>
						<?php esc_html_e( 'Keep in touch with the all the latest news and events', 'emmet-lite' ); ?></div>
					<?php
					else:
					if ( ! empty( $mp_emmet_lastnews_description ) ):
					?>
					<?php if ( $mp_emmet_lastnews_animation_description === 'none' ): ?>
					<div class="section-description">
						<?php else: ?>
						<div class="section-description animated anHidden"
						     data-animation="<?php echo esc_attr($mp_emmet_lastnews_animation_description); ?>">
							<?php endif; ?>
							<?php echo wp_kses($mp_emmet_lastnews_description, mp_emmet_allowed_html()); ?></div>
						<?php
						endif;
						endif;
						?>
						<div class="row">
							<?php
							$args   = array(
								'post_type'           => 'post',
								'posts_per_page'      => 4,
								'post_status'         => 'publish',
								'orderby'             => 'date',
								'ignore_sticky_posts' => 1,
							);
							$prizes = new WP_Query( $args );
							if ( $prizes->have_posts() ) {
							?>
							<div class="lastnews-list">
								<?php
								while ( $prizes->have_posts() ) {
								$prizes->the_post();
								?>
							<?php if ( $mp_emmet_lastnews_animation === 'none' ): ?>
								<div
									id="post-<?php the_ID(); ?>" <?php post_class( 'post col-xs-12 col-sm-3 col-md-3 col-lg-3' ); ?>>

									<?php else: ?>
									<div
										id="post-<?php the_ID(); ?>" <?php post_class( 'post col-xs-12 col-sm-3 col-md-3 col-lg-3 animated anHidden' ); ?>
										data-animation="<?php echo esc_attr($mp_emmet_lastnews_animation); ?>">
										<?php endif; ?>
										<?php if ( has_post_thumbnail() && ! post_password_required() && ! is_attachment() ) : ?>
											<div class="entry-thumbnail">
												<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'mp-emmet-thumb-medium' ); ?></a>
											</div>
										<?php else:
											?>
											<div class="entry-thumbnail empty-entry-thumbnail">
												<a href="<?php the_permalink(); ?>" rel="external"
												   title="<?php the_title(); ?>"><span class="date-post ">
                                                            <?php echo esc_html(get_post_time( 'j M', false, null, true )); ?>
                                                        </span></a>
											</div>
										<?php endif; ?>
										<div class="entry-header">
											<h5 class="entry-title">
												<a href="<?php the_permalink(); ?>"
												   rel="bookmark"><?php the_title(); ?></a>
											</h5>
										</div>
										<div class="entry entry-content">
											<p>
												<?php
												mp_emmet_get_content_theme( 95, false );
												?>
											</p>
										</div>
									</div>
									<?php }
									?>
									<div class="clearfix"></div>
								</div>
								<?php
								} else {
									esc_html_e( 'No news!', 'emmet-lite' );
								}
								?>
							</div>
							<div class="section-buttons">
								<?php
								if ( ! empty( $mp_emmet_lastnews_button_label ) && ! empty( $mp_emmet_lastnews_button_url ) ):
									?>
									<a href="<?php echo esc_url($mp_emmet_lastnews_button_url); ?>"
									   title="<?php echo esc_attr($mp_emmet_lastnews_button_label); ?>"
									   class="button white-button"><?php echo esc_html($mp_emmet_lastnews_button_label); ?></a>
									<?php
								endif;
								?>
							</div>

						</div>
					</div>
	</section>
<?php

    