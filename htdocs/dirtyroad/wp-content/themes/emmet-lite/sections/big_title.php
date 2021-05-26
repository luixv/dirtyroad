<?php
/*
 * Big title section
 */

$mp_emmet_bigtitle_title             = get_theme_mod( 'theme_bigtitle_title' );
$mp_emmet_bigtitle_description       = get_theme_mod( 'theme_bigtitle_description' );
$mp_emmet_bigtitle_brandbutton_label = get_theme_mod( 'theme_bigtitle_brandbutton_label', esc_html__( 'Features', 'emmet-lite' ) );
$mp_emmet_bigtitle_brandbutton_url   = get_theme_mod( 'theme_bigtitle_brandbutton_url', '#features' );
$mp_emmet_bigtitle_whitebutton_label = get_theme_mod( 'theme_bigtitle_whitebutton_label', esc_html__( 'Read more', 'emmet-lite' ) );
$mp_emmet_bigtitle_whitebutton_url   = get_theme_mod( 'theme_bigtitle_whitebutton_url', '#welcome' );
$mp_emmet_bigtitle_radio             = get_theme_mod( 'theme_bigtitle_radio', 'd' );
$mp_emmet_mp_slider                  = get_theme_mod( 'theme_mp_slider' );

$mp_emmet_bigtitle_id_option = esc_attr( get_theme_mod( 'theme_bigtitle_id' ) );
$mp_emmet_bigtitle_id        = empty( $mp_emmet_bigtitle_id_option ) ? 'big-section' : get_theme_mod( 'theme_bigtitle_id' );
?>
	<section id="<?php echo esc_attr($mp_emmet_bigtitle_id); ?>"
	         class="big-section  <?php echo 'transparent-section'; ?>">
		<?php if ( $mp_emmet_bigtitle_radio == 'd' ): ?>
			<?php  ?>
				<div class="container">
					<div class="section-content">
						<?php
						if ( get_theme_mod( 'theme_bigtitle_title', false ) === false ) :
							?>
							<h1 class="section-title"><?php esc_html_e( 'introducing the emmet theme', 'emmet-lite' ); ?></h1>
							<?php
						else:
							if ( ! empty( $mp_emmet_bigtitle_title ) ):
								?>
								<h1 class="section-title"><?php echo esc_html($mp_emmet_bigtitle_title); ?></h1>
								<?php
							endif;
						endif;
						if ( get_theme_mod( 'theme_bigtitle_description', false ) === false ) :
							?>
							<div
								class="section-description"><?php esc_html_e( 'Clean and responsive WordPress theme with a professional design created for corporate and portfolio websites. Emmet comes packaged with page builder and fully integrated with WordPress Customizer. Theme works perfectly with major WordPress plugins like WooCommerce, bbPress, BuddyPress and many others.', 'emmet-lite' ); ?></div>
							<?php
						else:
							if ( ! empty( $mp_emmet_bigtitle_description ) ):
								?>
								<div class="section-description"><?php echo wp_kses($mp_emmet_bigtitle_description, mp_emmet_allowed_html()); ?></div>
								<?php
							endif;
						endif;
						?>
						<div class="section-buttons">
							<?php
							if ( ! empty( $mp_emmet_bigtitle_brandbutton_label ) && ! empty( $mp_emmet_bigtitle_brandbutton_url ) ):
								?>
								<a href="<?php echo esc_url($mp_emmet_bigtitle_brandbutton_url); ?>"
								   title="<?php echo esc_attr($mp_emmet_bigtitle_brandbutton_label); ?>"
								   class="button"><?php echo esc_html($mp_emmet_bigtitle_brandbutton_label); ?></a>
								<?php
							endif;
							if ( ! empty( $mp_emmet_bigtitle_whitebutton_label ) && ! empty( $mp_emmet_bigtitle_whitebutton_url ) ):
								?>
								<a href="<?php echo esc_url($mp_emmet_bigtitle_whitebutton_url); ?>"
								   title="<?php echo esc_attr($mp_emmet_bigtitle_whitebutton_label); ?>"
								   class="button white-button"><?php echo esc_html($mp_emmet_bigtitle_whitebutton_label); ?></a>
								<?php
							endif;
							?>
						</div>

					</div>
				</div>
				<?php  ?>
			<?php
		else:
			echo do_shortcode( $mp_emmet_mp_slider );
		endif;
		?>
	</section>
<?php
