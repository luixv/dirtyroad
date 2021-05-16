<?php
/**
 * The template part for top header
 *
 * @package Automotive Centre 
 * @subpackage automotive-centre
 * @since Automotive Centre 1.0
 */
?>

<div id="topbar">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-3">
        <div class="logo">
          <?php if ( has_custom_logo() ) : ?>
            <div class="site-logo"><?php the_custom_logo(); ?></div>
          <?php endif; ?>
          <?php $blog_info = get_bloginfo( 'name' ); ?>
            <?php if ( ! empty( $blog_info ) ) : ?>
              <?php if ( is_front_page() && is_home() ) : ?>
                <?php if( get_theme_mod('automotive_centre_logo_title_hide_show',true) != ''){ ?>
                  <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                <?php } ?>
              <?php else : ?>
                <?php if( get_theme_mod('automotive_centre_logo_title_hide_show',true) != ''){ ?>
                  <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                <?php } ?>
              <?php endif; ?>
            <?php endif; ?>
            <?php
              $description = get_bloginfo( 'description', 'display' );
              if ( $description || is_customize_preview() ) :
            ?>
            <?php if( get_theme_mod('automotive_centre_tagline_hide_show',true) != ''){ ?>
              <p class="site-description">
                <?php echo esc_html($description); ?>
              </p>
            <?php } ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-lg-3 col-md-3">
        <div class="row info-box">
          <?php if( get_theme_mod( 'automotive_centre_phone_text') != '' || get_theme_mod( 'automotive_centre_phone_number') != '') { ?>
            <div class="col-lg-2 col-md-12 col-3">
              <i class="<?php echo esc_attr(get_theme_mod('automotive_centre_phone_icon','fas fa-phone')); ?>"></i>
            </div>
            <div class="col-lg-10 col-md-12 col-9">
              <h6><?php echo esc_html(get_theme_mod('automotive_centre_phone_text',''));?></h6>
              <p><a href="tel:<?php echo esc_url( get_theme_mod('automotive_centre_phone_number','') ); ?>"><?php echo esc_html(get_theme_mod('automotive_centre_phone_number',''));?></a></p>
            </div>
          <?php }?>
        </div>
      </div>
      <div class="col-lg-4 col-md-3">
        <div class="row info-box">
          <?php if( get_theme_mod( 'automotive_centre_email_text') != '' || get_theme_mod( 'automotive_centre_email_address') != '') { ?>
            <div class="col-lg-2 col-md-12 col-3">
              <i class="<?php echo esc_attr(get_theme_mod('automotive_centre_email_icon','fas fa-envelope-open')); ?>"></i>
            </div>
            <div class="col-lg-10 col-md-12 col-9">
              <h6><?php echo esc_html(get_theme_mod('automotive_centre_email_text',''));?></h6>
              <p><a href="mailto:<?php echo esc_html(get_theme_mod('automotive_centre_email_address',''));?>"><?php echo esc_html(get_theme_mod('automotive_centre_email_address',''));?></a></p>
            </div>
          <?php }?>
        </div>
      </div>
      <div class="col-lg-2 col-md-3">
        <?php if( get_theme_mod( 'automotive_centre_top_button_url') != '' || get_theme_mod( 'automotive_centre_top_button_text') != '') { ?>
          <div class="top-btn">
            <a href="<?php echo esc_url(get_theme_mod('automotive_centre_top_button_url',''));?>"><?php echo esc_html(get_theme_mod('automotive_centre_top_button_text',''));?><span class="screen-reader-text"><?php esc_html_e( 'SELL YOUR CAR','automotive-centre' );?></span></a>
          </div>
        <?php }?>
      </div>
    </div>
  </div>
</div>