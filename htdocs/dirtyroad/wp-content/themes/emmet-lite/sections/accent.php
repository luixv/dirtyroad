<?php
/*
 * Accent section
 */
$mp_emmet_accent_animation_left  = get_theme_mod( 'theme_accent_animation_left', 'fadeInLeft' );
$mp_emmet_accent_animation_right = get_theme_mod( 'theme_accent_animation_right', 'fadeInRight' );
$mp_emmet_accent_id_option       = get_theme_mod( 'theme_accent_id' );
$mp_emmet_accent_id              = empty( $mp_emmet_accent_id_option ) ? 'accent' : get_theme_mod( 'theme_accent_id' );
?>
	<section id="<?php echo esc_attr($mp_emmet_accent_id); ?>" class="accent-section">
		<div class="container">
			<div class="section-content">
				<?php
				$mp_emmet_accent_title        = get_theme_mod( 'theme_accent_title' );
				$mp_emmet_accent_description  = get_theme_mod( 'theme_accent_description' );
				$mp_emmet_accent_button_label = get_theme_mod( 'theme_accent_button_label', esc_html__( 'read more', 'emmet-lite' ) );
				$mp_emmet_accent_button_url   = get_theme_mod( 'theme_accent_button_url', '#accent' );
				?>
				<?php if ( $mp_emmet_accent_animation_left === 'none' ): ?>
				<div class="section-subcontent">
					<?php else: ?>
					<div class="section-subcontent animated anHidden"
					     data-animation="<?php echo esc_attr($mp_emmet_accent_animation_left); ?>">
						<?php endif; ?>
						<?php
						if ( get_theme_mod( 'theme_accent_title', false ) === false ) :
							?>
							<h3 class="section-title"><?php esc_html_e( 'Install Emmet theme now!', 'emmet-lite' ); ?></h3>
							<?php
						else:
							if ( ! empty( $mp_emmet_accent_title ) ):
								?>
								<h3 class="section-title"><?php echo esc_html($mp_emmet_accent_title); ?></h3>
								<?php
							endif;
						endif;
						if ( get_theme_mod( 'theme_accent_description', false ) === false ) :
							?>
							<div
								class="section-description"><?php esc_html_e( 'Install the theme in several clicks and start editing it with a help of a handy WordPress Customizer or use a very user-friendly built-in visual page builder.', 'emmet-lite' ); ?></div>
							<?php
						else:
							if ( ! empty( $mp_emmet_accent_description ) ):
								?>
								<div class="section-description"><?php echo wp_kses($mp_emmet_accent_description, mp_emmet_allowed_html()); ?></div>
								<?php
							endif;
						endif;
						?>
					</div>
					<?php if ( $mp_emmet_accent_animation_right === 'none' ): ?>
					<div class="section-buttons">
						<?php else: ?>
						<div class="section-buttons animated anHidden"
						     data-animation="<?php echo esc_attr($mp_emmet_accent_animation_right); ?>">
							<?php endif; ?>
							<?php
							if ( ! empty( $mp_emmet_accent_button_label ) && ! empty( $mp_emmet_accent_button_url ) ):
								?>
								<a href="<?php echo esc_url($mp_emmet_accent_button_url); ?>"
								   title="<?php echo esc_attr($mp_emmet_accent_button_label); ?>"
								   class="button white-button"><?php echo esc_html($mp_emmet_accent_button_label); ?></a>
								<?php
							endif;
							?>
						</div>

					</div>
				</div>
	</section>
<?php
