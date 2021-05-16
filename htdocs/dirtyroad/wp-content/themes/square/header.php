<?php
/**
 * The header for our theme.
 *
 * @package Square
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">

        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>
        <?php wp_body_open(); ?>
        <div id="sq-page">
            <a class="skip-link screen-reader-text" href="#sq-content"><?php esc_html_e('Skip to content', 'square'); ?></a>
            <?php
            $square_header_bg = get_theme_mod('square_header_bg', 'sq-black');
            $square_sticky_header = get_theme_mod('square_disable_sticky_header');
            $square_sticky_header_class = ($square_sticky_header) ? ' disable-sticky' : '';
            ?>
            <header id="sq-masthead" class="sq-site-header <?php echo esc_attr($square_header_bg . $square_sticky_header_class); ?>">
                <div class="sq-container sq-clearfix">
                    <div id="sq-site-branding">
                        <?php
                        if (function_exists('has_custom_logo') && has_custom_logo()) {
                            the_custom_logo();
                        } elseif (get_header_image()) {
                            ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <img src="<?php header_image(); ?>" alt="<?php bloginfo('name'); ?>">
                            </a>
                        <?php } else { ?>
                            <?php if (is_front_page()) { ?>
                                <h1 class="sq-site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                            <?php } else { ?>
                                <p class="sq-site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
                            <?php } ?>
                            <p class="sq-site-description"><?php bloginfo('description'); ?></p>
                        <?php } // End header image check.?>
                    </div><!-- .site-branding -->

                    <a href="#" class="sq-toggle-nav">
                        <span></span>
                    </a>

                    <nav id="sq-site-navigation" class="sq-main-navigation">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'container_class' => 'sq-menu sq-clearfix',
                            'menu_class' => 'sq-clearfix',
                            'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        ));
                        ?>
                    </nav><!-- #site-navigation -->
                </div>
            </header><!-- #masthead -->

            <div id="sq-content" class="sq-site-content sq-clearfix">
