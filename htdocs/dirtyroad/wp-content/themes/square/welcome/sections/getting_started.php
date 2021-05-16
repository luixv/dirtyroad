<div class="welcome-getting-started">
    <div class="welcome-manual-setup">
        <h3><?php echo esc_html__('Manual Setup', 'square'); ?></h3>
        <!--
        <div class="welcome-theme-thumb">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/welcome/css/set-front-page.gif'); ?>" alt="<?php echo esc_attr__('Viral Demo', 'square'); ?>">
        </div> -->
        <p><?php echo esc_html__('You can setup the home page sections either from Customizer Panel or from Elementor Pagebuilder', 'square'); ?></p>
        <p><strong><?php echo esc_html__('FROM CUSTOMIZER', 'square'); ?></strong></p>
        <ol>
            <li><?php echo esc_html__('Go to Appearance > Customize', 'square'); ?></li>
            <li><?php echo sprintf(esc_html__('Click on "%s" and turn on the option for "Enable FrontPage" Setting', 'square'), '<a href="' . admin_url('customize.php?autofocus[section]=static_front_page') . '" target="_blank">' . esc_html__('Homepage Settings', 'square') . '</a>'); ?> </li>
            <li><?php echo esc_html__('Now go back and click on "Front Page Sections" and set up the FrontPage Section', 'square'); ?> </li>
        </ol>
        <p><strong><?php echo esc_html__('FROM ELEMENTOR', 'square'); ?></strong></p>
        <ol>
            <li><?php printf(esc_html__('Firstly install and activate "Elementor" and "Hash Elements" plugin from %s.', 'square'), '<a href="' . admin_url('admin.php?page=square-welcome&section=recommended_plugins') . '" target="_blank">' . esc_html__('Recommended Plugin page', 'square') . '</a>'); ?></li>
            <li><?php echo esc_html__('Create a new page and edit with Elementor. Drag and drop the news elements in the Elementor to create your own design.', 'square'); ?></li>
            <li><?php echo esc_html__('Now go to Appearance > Customize > Homepage Settings and choose "A static page" for "Your latest posts" and select the created page for "Home Page" option.', 'square'); ?> </li>
        </ol>
        <p style="margin-bottom: 0"><?php printf(esc_html__('For detailed documentation, please visit %s.', 'square'), '<a href="https://hashthemes.com/documentation/square-documentation/#HomePageSetup" target="_blank">' . esc_html__('Documentation Page', 'square') . '</a>'); ?></p>
    </div>

    <div class="welcome-demo-import">
        <h3><?php echo esc_html__('Demo Importer', 'square'); ?><a href="https://demo.hashthemes.com/<?php echo get_option('stylesheet'); ?>" target="_blank" class="button button-primary"><?php esc_html_e('View Demo', 'square'); ?></a></h3>
        <div class="welcome-theme-thumb">
            <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/screenshot.png'); ?>" alt="<?php printf(esc_attr__('%s Demo', 'square'), $this->theme_name); ?>">
        </div>

        <div class="welcome-demo-import-text">
            <p><?php esc_html_e('Or you can get started by importing the demo with just one click.', 'square'); ?></p>
            <p><?php echo sprintf(esc_html__('Click on the button below to install and activate the HashThemes Demo Importer plugin. For more detailed documentation on how the demo importer works, click %s.', 'square'), '<a href="https://hashthemes.com/documentation/square-documentation/#ImportDemoContent" target="_blank">' . esc_html__('here', 'square') . '</a>'); ?></p>
            <?php echo $this->generate_hdi_install_button(); ?>
        </div>
    </div>
</div>