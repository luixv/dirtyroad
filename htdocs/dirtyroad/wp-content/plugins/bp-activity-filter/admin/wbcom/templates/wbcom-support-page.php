<div class="wrap">
	<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
	<h4 class="wbcom-plugin-heading"><?php esc_html_e( 'Support', 'bp-activity-filter' ); ?></h4>
    <div id="wbcom_admin_content_support" class="wbcom_admin_tab_content">
        <strong><?php esc_html_e( 'Do you have any questions or issues?', 'bp-activity-filter' ); ?></strong>
        <p>
			<?php esc_html_e( 'Our team is here to help you out at any time. If you have any idea about how we could improve, you can contact our support.', 'bp-activity-filter' ); ?></p>

        <ul id="wbcom_support_list" class="wbcom_boxes_list wp-clearfix">

            <li class="wbcom_single_box wp-clearfix">
                <div class="wbcom_single_left">
                    <div class="wbcom_single_icon_wrapper">
                        <i class="fas fa-2x fa-tags"></i>
                    </div>
                </div>
                <div class="wbcom_single_right">
                    <div class="wbcom_single_inner">
                        <h4><?php esc_html_e( 'Tickets Support', 'bp-activity-filter' ); ?></h4>
                        <p><?php esc_html_e( 'Please open a ticket on our helpdesk, and we\'ll try to respond within 24 hours during working days. ', 'bp-activity-filter' ); ?></p>
                        <div class="text-right">
                            <a href="https://wbcomdesigns.com/support/" class="wb_btn wb_btn_default" target="_blank">
                                <i class="fas fa-ticket-alt"></i>
								<?php esc_html_e( 'Open a ticket', 'bp-activity-filter' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </li>

            <li class="wbcom_single_box wp-clearfix">
                <div class="wbcom_single_left">
                    <div class="wbcom_single_icon_wrapper">
                        <i class="fas fa-2x fa-comments"></i>
                    </div>
                </div>
                <div class="wbcom_single_right">
                    <div class="wbcom_single_inner">
                        <h4><?php esc_html_e( 'Contact Us', 'bp-activity-filter' ); ?></h4>
                        <p><?php esc_html_e( 'Don’t be a stranger, just say hello. Our support is minimal at wordpress.org threads. ', 'bp-activity-filter' ); ?></p>
                        <div class="text-right">
                            <a href="https://wbcomdesigns.com/contact/" class="wb_btn wb_btn_default" target="_blank">
                                <i class="fas fa-phone-square"></i>
								<?php esc_html_e( 'Contact Us', 'bp-activity-filter' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</div>
