<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WhiteDot


			/**
			 * whitedot_footer_start hook.
			 *
			 * @since 1.0.2
			 *
		 	 * @hooked whitedot_header_column_full_close   - 10
			 *
			 */
			do_action( 'whitedot_footer_start' ); ?>

		<!-- </div> --><!-- .col-full -->
	</div><!-- #content -->

	<?php
	/**
	 * whitedot_before_footer hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'whitedot_before_footer' ); 

	?>

	<footer itemtype="http://schema.org/WPFooter" itemscope class="site-footer">

		<?php
		/**
		 * whitedot_before_footer_content hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'whitedot_before_footer_content' ); 

		/**
		 * Functions hooked in to whitedot_footer_content action
		 *
		 * @hooked whitedot_footer_branding   - 10
		 * @hooked whitedot_footer_widgets   - 20
		 * @hooked whitedot_footer_info       - 30
		 */
		do_action( 'whitedot_footer_content' ); 

		/**
		 * whitedot_after_footer_content hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'whitedot_after_footer_content' );
		?>
		
	</footer><!--.site-footer-->

	<?php
	/**
	 * whitedot_after_footer hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'whitedot_after_footer' ); 

	?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
