<div class="welcome-header clearfix">
    <div class="welcome-intro">
        <h2><?php
            printf(// WPCS: XSS OK.
                    /* translators: 1-theme name, 2-theme version */
                    esc_html__('Welcome to %1$s - Version %2$s', 'square'), $this->theme_name, $this->theme_version);
            ?></h2>
        <div class="welcome-text">
            <?php
            printf(// WPCS: XSS OK.
                    /* translators: 1-theme name */
                    esc_html__('Welcome and thank you for installing %1$s. Getting started with %1$s is very easy. Here you will find all the necessary information required to get started with the theme. And of course, the premium version if you require more features.', 'square'), $this->theme_name);
            ?>
        </div>

        <div class="free-pro-demos">
            <a class="button button-primary" href="https://demo.hashthemes.com/<?php echo get_option('stylesheet'); ?>/" target="_blank"><span class="dashicons dashicons-visibility"></span><?php esc_html_e('Free Demos', 'square'); ?></a>
            <a class="button button-primary" href="https://demo.hashthemes.com/square-plus/" target="_blank"><span class="dashicons dashicons-cart"></span><?php esc_html_e('Premium Demos', 'square'); ?></a>
        </div>
    </div>

    <div class="welcome-promo-banner">
        <a class="welcome-promo-offer" href="<?php echo esc_url('https://hashthemes.com/wordpress-theme/square-plus/?utm_source=wordpress&utm_medium=square-welcome&utm_campaign=square-upgrade'); ?>" target="_blank"><?php echo esc_html__('Unlock all the possibilities with Square Plus.', 'square'); ?></a>
        <a href="<?php echo esc_url('https://hashthemes.com/wordpress-theme/square-plus/?utm_source=wordpress&utm_medium=square-welcome&utm_campaign=square-upgrade'); ?>" target="_blank" class="button button-primary upgrade-btn"><?php echo esc_html__('Upgrade for $55', 'square'); ?></a>
    </div>
</div>

<div class="welcome-nav-wrapper clearfix">
    <?php foreach ($tabs as $section_id => $label) : ?>
        <?php
        $section = isset($_GET['section']) && array_key_exists($_GET['section'], $tabs) ? $_GET['section'] : 'getting_started';
        $nav_class = 'welcome-nav-tab';
        if ($section_id == $section) {
            $nav_class .= ' welcome-nav-tab-active';
        }
        ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=square-welcome&section=' . $section_id)); ?>" class="<?php echo esc_attr($nav_class); ?>" >
            <?php echo esc_html($label); ?>
        </a>
    <?php endforeach; ?>
</div>