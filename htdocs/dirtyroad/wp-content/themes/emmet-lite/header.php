<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>
<?php
$classBody = array( 'emmet' );

if ( get_page_template_slug() != 'template-front-page.php' ) {
	$classBody = array( 'emmet', 'pages-background' );
}
?>
<body <?php body_class( $classBody ); ?> <?php if ( isset( $_POST['scrollPosition'] ) ): ?> onLoad="window.scrollTo(0,<?php echo intval( $_POST['scrollPosition'] ); ?>)"   <?php endif; ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main">
	<?php esc_html_e( 'Skip to content', 'emmet-lite' ); ?></a>
<div
	class="wrapper <?php if ( is_plugin_active( 'motopress-content-editor-lite/motopress-content-editor.php' ) ): echo 'wrapper-mce-lite';
	endif;
	?> <?php
	if ( get_page_template_slug() === 'template-front-page.php' ) {
		echo 'front-page ';
	}
	?>">
	<?php
	$menuClass = '';
	if ( is_front_page() ) :
		if ( get_option( 'show_on_front' ) != 'page' ):
			if ( get_theme_mod( 'theme_custom_page_show', true ) === false
			):
				$menuClass = 'home-menu';
			elseif ( get_theme_mod( 'theme_custom_page_show' ) === 1 ):
				$menuClass = 'home-menu';
			endif;
		endif;
	endif;
	?>
	<?php if ( get_page_template_slug() != 'template-landing-page.php' || is_search() ) : ?>
		<header id="header" class="main-header">
			<?php  ?>
				<div class="top-header">
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
						?>
						<div class="top-menu">
							<?php
							$defaults = array(
								'container'      => '',
								'fallback_cb'    => 'mp_emmet_wp_page_short_menu',
								'theme_location' => 'top-menu',
								'depth'          => '1'
							);
							wp_nav_menu( $defaults );
							?>
							<div class="clearfix"></div>
						</div>

						<div class="social-profile type1 ">
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
						</div>
						<div class="contact-info ">
							<ul class=" info-list">
								<?php if ( get_theme_mod( 'theme_location_info', false ) === false ) : ?>
									<li class="address-wrapper"><?php echo wp_kses_data(MP_EMMET_DEFAULT_ADDRESS); ?></li>
								<?php else: ?>
									<?php if ( ! empty( $mp_emmet_location ) ): ?>
										<li class="address-wrapper"><?php echo wp_kses_data( $mp_emmet_location ); ?></li>
									<?php endif; ?>
								<?php endif; ?>
								<?php if ( get_theme_mod( 'theme_phone_info', false ) === false ) : ?>
									<li class="phone-wrapper"><?php echo wp_kses_data(MP_EMMET_DEFAULT_PHONE); ?></li>
								<?php else: ?>
									<?php if ( ! empty( $mp_emmet_phone ) ): ?>
										<li class="phone-wrapper"><?php echo wp_kses_data( $mp_emmet_phone ); ?></li>
									<?php endif; ?>
								<?php endif; ?>
							</ul>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<?php
				
			?>
			<div class="site-header"
			     data-sticky-menu="<?php if ( get_theme_mod( 'theme_show_sticky_menu', false ) === true ) : ?>on<?php else: if ( get_theme_mod( 'theme_show_sticky_menu', false ) === 1 ): ?>on<?php
			     else: echo 'off';
			     endif;
			     endif;
			     ?>">
				<div class="container">
					<div class="site-logo">
						<?php if ( has_custom_logo() || get_theme_mod( 'theme_logo' ) != "" || get_bloginfo( 'description' ) || get_bloginfo( 'name', 'display' ) != "" ) : ?>
							<a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"
							   title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                                <?php
                                mp_emmet_render_header_logo();
                                ?>
								<div class="site-description">
									<p class="site-title <?php if ( ! get_bloginfo( 'description' ) ) : ?>empty-tagline<?php endif; ?>"><?php bloginfo( 'name' ); ?></p>
									<?php if ( get_bloginfo( 'description' ) ) : ?>
										<p class="site-tagline"><?php bloginfo( 'description' ); ?></p>
									<?php endif; ?>
								</div>
							</a>
						<?php endif ?>
					</div>
                    <button class="menu-toggle" aria-controls="main-menu"
                            aria-expanded="false"><span class="menu-show"><?php esc_html_e('Menu', 'emmet-lite')?></span>
                        <span class="menu-close"><?php esc_html_e('Close', 'emmet-lite')?></span>
                        </span>
                    </button>
					<div id="navbar" class="navbar">
						<nav id="site-navigation" class="main-navigation">
							<?php
							$defaults = array(
								'theme_location' => 'primary',
								'menu_class'     => 'sf-menu ' . $menuClass,
								'menu_id'        => 'main-menu',
								'fallback_cb'    => 'mp_emmet_wp_page_menu'
							);
							wp_nav_menu( $defaults );
							?>
						</nav>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</header>
	<?php endif; ?>
	<div id="main" class="site-main">
