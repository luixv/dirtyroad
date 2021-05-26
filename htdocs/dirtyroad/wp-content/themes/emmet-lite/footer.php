<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
?>

</div><!-- #main -->
<?php if ( get_page_template_slug() != 'template-landing-page.php' || is_search() ): ?>
	<footer id="footer" class="site-footer">
		<a href="#" id="toTop" class="toTop"><i class="fa fa-angle-up"></i></a>
		<?php
		
			get_sidebar( 'footer' );
			
		?>
		<div class="footer-inner">
			<div class="container">
				<?php
				$mp_emmet_location         = get_theme_mod( 'theme_location_info' );
				$mp_emmet_phone            = get_theme_mod( 'theme_phone_info' );
				$mp_emmet_facbook_link     = get_theme_mod( 'theme_facebook_link', '#' );
				$mp_emmet_twitter_link     = get_theme_mod( 'theme_twitter_link', '#' );
				$mp_emmet_linkedin_link    = get_theme_mod( 'theme_linkedin_link', '#' );
				$mp_emmet_google_plus_link = get_theme_mod( 'theme_google_plus_link', '#' );
				$mp_emmet_pinterest_link   = get_theme_mod( 'theme_pinterest_link', '' );
				$mp_emmet_instagram_link   = get_theme_mod( 'theme_instagram_link', '' );
				$mp_emmet_tumblr_link      = get_theme_mod( 'theme_tumblr_link', '' );
				$mp_emmet_youtube_link     = get_theme_mod( 'theme_youtube_link', '' );
				$mp_emmet_vk_link          = get_theme_mod( 'theme_vk_link', '' );
				$mp_emmet_skype_link       = get_theme_mod( 'theme_skype_link', '' );
                $mp_emmet_meetup_link      = get_theme_mod( 'theme_meetup_link', '' );

				$mp_emmet_theme_copyright = get_theme_mod( 'theme_copyright' );
				?>
				<p class="social-profile type1 pull-right">
					<?php if ( ! empty( $mp_emmet_facbook_link ) ): ?>
						<a href="<?php echo esc_url($mp_emmet_facbook_link); ?>" class="button-facebook" title="Facebook"
						   target="_blank"><i class="fa fa-facebook-square"></i></a>
					<?php endif; ?>
					<?php if ( ! empty( $mp_emmet_twitter_link ) ): ?>
						<a href="<?php echo esc_url($mp_emmet_twitter_link); ?>" class="button-twitter" title="Twitter"
						   target="_blank"><i class="fa fa-twitter-square"></i></a>
					<?php endif; ?>
					<?php if ( ! empty( $mp_emmet_linkedin_link ) ): ?>
						<a href="<?php echo esc_url($mp_emmet_linkedin_link); ?>" class="button-linkedin"
						   title="LinkedIn" target="_blank"><i class="fa fa-linkedin-square"></i></a>
					<?php endif; ?>
					<?php if ( ! empty( $mp_emmet_google_plus_link ) ): ?>
						<a href="<?php echo esc_url($mp_emmet_google_plus_link); ?>" class="button-google"
						   title="Google +" target="_blank"><i class="fa fa-google-plus-square"></i></a>
					<?php endif; ?>
					<?php if ( ! empty( $mp_emmet_pinterest_link ) ): ?>
						<a href="<?php echo esc_url($mp_emmet_pinterest_link); ?>" class="button-pinterest"
						   title="Pinterest" target="_blank"><i class="fa fa-pinterest-square"></i></a>
					<?php endif; ?>
					<?php if ( ! empty( $mp_emmet_instagram_link ) ): ?>
						<a href="<?php echo esc_url($mp_emmet_instagram_link); ?>" class="button-instagram"
						   title="Instagram" target="_blank"><i class="fa fa-instagram"></i></a>
					<?php endif; ?>
					<?php if ( ! empty( $mp_emmet_tumblr_link ) ): ?>
						<a href="<?php echo esc_url($mp_emmet_tumblr_link); ?>" class="button-tumblr"
						   title="Tumblr" target="_blank"><i class="fa fa-tumblr-square"></i></a>
					<?php endif; ?>
					<?php if ( ! empty( $mp_emmet_youtube_link ) ): ?>
						<a href="<?php echo esc_url($mp_emmet_youtube_link); ?>" class="button-youtube"
						   title="Youtube" target="_blank"><i class="fa fa-youtube-square"></i></a>
					<?php endif; ?>
					<?php if ( ! empty( $mp_emmet_vk_link ) ): ?>
						<a href="<?php echo esc_url($mp_emmet_vk_link); ?>" class="button-vk"
						   title="Vk" target="_blank"><i class="fa fa-vk"></i></a>
					<?php endif; ?>
					<?php if ( ! empty( $mp_emmet_skype_link ) ): ?>
						<a href="skype:<?php echo esc_attr($mp_emmet_skype_link); ?>?call" class="button-skype"
						   title="Skype"><i class="fa fa-skype"></i></a>
					<?php endif; ?>
                    <?php if ( ! empty( $mp_emmet_meetup_link ) ): ?>
                        <a href="<?php echo esc_attr($mp_emmet_meetup_link); ?>" class="button-meetup"
                           title="Meetup"><i class="fa fa-meetup"></i></a>
                    <?php endif; ?>

				</p>
				<p class="copyright"><span class="copyright-date">
						<?php 
							$dateObj = new DateTime;
							$current_year = $dateObj->format( "Y" );
						    /* translators: %1$s - current year */
							printf( esc_html_x( '&copy; Copyright %1$s', '%1$s - current year','emmet-lite' ), esc_html($current_year) );
						?>
                    </span>
					<?php
					?>
					  <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name'); ?>" target="_blank"><?php bloginfo('name'); ?></a>
					  <?php printf(__('&#8226; Designed by', 'emmet-lite')); ?> <a href="<?php echo esc_url(__('https://motopress.com/', 'emmet-lite' )); ?>" rel="nofollow" title="<?php esc_attr_e('Premium WordPress Plugins and Themes', 'emmet-lite' ); ?>"><?php _e('MotoPress', 'emmet-lite'); ?></a>
					  <?php printf(__('&#8226; Proudly Powered by ',  'emmet-lite')); ?><a href="<?php echo esc_url(__('http://wordpress.org/', 'emmet-lite')); ?>"  rel="nofollow" title="<?php esc_attr_e('Semantic Personal Publishing Platform', 'emmet-lite' ); ?>"><?php _e('WordPress',  'emmet-lite' ); ?></a>
					  <?php
					?>
				</p><!-- .copyright -->
			</div>
		</div>
	</footer>
<?php endif; ?>
</div>
<?php wp_footer(); ?>
</body>
</html>