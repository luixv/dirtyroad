<?php
/**
 * Typography control class.
 *
 * @since  1.0.0
 * @access public
 */

class Automotive_Centre_Control_Typography extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'typography';

	/**
	 * Array 
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $l10n = array();

	/**
	 * Set up our control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @param  string  $id
	 * @param  array   $args
	 * @return void
	 */
	public function __construct( $manager, $id, $args = array() ) {

		// Let the parent class do its thing.
		parent::__construct( $manager, $id, $args );

		// Make sure we have labels.
		$this->l10n = wp_parse_args(
			$this->l10n,
			array(
				'color'       => esc_html__( 'Font Color', 'automotive-centre' ),
				'family'      => esc_html__( 'Font Family', 'automotive-centre' ),
				'size'        => esc_html__( 'Font Size',   'automotive-centre' ),
				'weight'      => esc_html__( 'Font Weight', 'automotive-centre' ),
				'style'       => esc_html__( 'Font Style',  'automotive-centre' ),
				'line_height' => esc_html__( 'Line Height', 'automotive-centre' ),
				'letter_spacing' => esc_html__( 'Letter Spacing', 'automotive-centre' ),
			)
		);
	}

	/**
	 * Enqueue scripts/styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_script( 'automotive-centre-ctypo-customize-controls' );
		wp_enqueue_style(  'automotive-centre-ctypo-customize-controls' );
	}

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		// Loop through each of the settings and set up the data for it.
		foreach ( $this->settings as $setting_key => $setting_id ) {

			$this->json[ $setting_key ] = array(
				'link'  => $this->get_link( $setting_key ),
				'value' => $this->value( $setting_key ),
				'label' => isset( $this->l10n[ $setting_key ] ) ? $this->l10n[ $setting_key ] : ''
			);

			if ( 'family' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_families();

			elseif ( 'weight' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_weight_choices();

			elseif ( 'style' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_style_choices();
		}
	}

	/**
	 * Underscore JS template to handle the control's output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function content_template() { ?>

		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<ul>

		<# if ( data.family && data.family.choices ) { #>

			<li class="typography-font-family">

				<# if ( data.family.label ) { #>
					<span class="customize-control-title">{{ data.family.label }}</span>
				<# } #>

				<select {{{ data.family.link }}}>

					<# _.each( data.family.choices, function( label, choice ) { #>
						<option value="{{ choice }}" <# if ( choice === data.family.value ) { #> selected="selected" <# } #>>{{ label }}</option>
					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.weight && data.weight.choices ) { #>

			<li class="typography-font-weight">

				<# if ( data.weight.label ) { #>
					<span class="customize-control-title">{{ data.weight.label }}</span>
				<# } #>

				<select {{{ data.weight.link }}}>

					<# _.each( data.weight.choices, function( label, choice ) { #>

						<option value="{{ choice }}" <# if ( choice === data.weight.value ) { #> selected="selected" <# } #>>{{ label }}</option>

					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.style && data.style.choices ) { #>

			<li class="typography-font-style">

				<# if ( data.style.label ) { #>
					<span class="customize-control-title">{{ data.style.label }}</span>
				<# } #>

				<select {{{ data.style.link }}}>

					<# _.each( data.style.choices, function( label, choice ) { #>

						<option value="{{ choice }}" <# if ( choice === data.style.value ) { #> selected="selected" <# } #>>{{ label }}</option>

					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.size ) { #>

			<li class="typography-font-size">

				<# if ( data.size.label ) { #>
					<span class="customize-control-title">{{ data.size.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.size.link }}} value="{{ data.size.value }}" />

			</li>
		<# } #>

		<# if ( data.line_height ) { #>

			<li class="typography-line-height">

				<# if ( data.line_height.label ) { #>
					<span class="customize-control-title">{{ data.line_height.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.line_height.link }}} value="{{ data.line_height.value }}" />

			</li>
		<# } #>

		<# if ( data.letter_spacing ) { #>

			<li class="typography-letter-spacing">

				<# if ( data.letter_spacing.label ) { #>
					<span class="customize-control-title">{{ data.letter_spacing.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.letter_spacing.link }}} value="{{ data.letter_spacing.value }}" />

			</li>
		<# } #>

		</ul>
	<?php }

	/**
	 * Returns the available fonts.  Fonts should have available weights, styles, and subsets.
	 *
	 * @todo Integrate with Google fonts.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_fonts() { return array(); }

	/**
	 * Returns the available font families.
	 *
	 * @todo Pull families from `get_fonts()`.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	function get_font_families() {

		return array(
			'' => __( 'No Fonts', 'automotive-centre' ),
        'Abril Fatface' => __( 'Abril Fatface', 'automotive-centre' ),
        'Acme' => __( 'Acme', 'automotive-centre' ),
        'Anton' => __( 'Anton', 'automotive-centre' ),
        'Architects Daughter' => __( 'Architects Daughter', 'automotive-centre' ),
        'Arimo' => __( 'Arimo', 'automotive-centre' ),
        'Arsenal' => __( 'Arsenal', 'automotive-centre' ),
        'Arvo' => __( 'Arvo', 'automotive-centre' ),
        'Alegreya' => __( 'Alegreya', 'automotive-centre' ),
        'Alfa Slab One' => __( 'Alfa Slab One', 'automotive-centre' ),
        'Averia Serif Libre' => __( 'Averia Serif Libre', 'automotive-centre' ),
        'Bangers' => __( 'Bangers', 'automotive-centre' ),
        'Boogaloo' => __( 'Boogaloo', 'automotive-centre' ),
        'Bad Script' => __( 'Bad Script', 'automotive-centre' ),
        'Bitter' => __( 'Bitter', 'automotive-centre' ),
        'Bree Serif' => __( 'Bree Serif', 'automotive-centre' ),
        'BenchNine' => __( 'BenchNine', 'automotive-centre' ),
        'Cabin' => __( 'Cabin', 'automotive-centre' ),
        'Cardo' => __( 'Cardo', 'automotive-centre' ),
        'Courgette' => __( 'Courgette', 'automotive-centre' ),
        'Cherry Swash' => __( 'Cherry Swash', 'automotive-centre' ),
        'Cormorant Garamond' => __( 'Cormorant Garamond', 'automotive-centre' ),
        'Crimson Text' => __( 'Crimson Text', 'automotive-centre' ),
        'Cuprum' => __( 'Cuprum', 'automotive-centre' ),
        'Cookie' => __( 'Cookie', 'automotive-centre' ),
        'Chewy' => __( 'Chewy', 'automotive-centre' ),
        'Days One' => __( 'Days One', 'automotive-centre' ),
        'Dosis' => __( 'Dosis', 'automotive-centre' ),
        'Droid Sans' => __( 'Droid Sans', 'automotive-centre' ),
        'Economica' => __( 'Economica', 'automotive-centre' ),
        'Fredoka One' => __( 'Fredoka One', 'automotive-centre' ),
        'Fjalla One' => __( 'Fjalla One', 'automotive-centre' ),
        'Francois One' => __( 'Francois One', 'automotive-centre' ),
        'Frank Ruhl Libre' => __( 'Frank Ruhl Libre', 'automotive-centre' ),
        'Gloria Hallelujah' => __( 'Gloria Hallelujah', 'automotive-centre' ),
        'Great Vibes' => __( 'Great Vibes', 'automotive-centre' ),
        'Handlee' => __( 'Handlee', 'automotive-centre' ),
        'Hammersmith One' => __( 'Hammersmith One', 'automotive-centre' ),
        'Inconsolata' => __( 'Inconsolata', 'automotive-centre' ),
        'Indie Flower' => __( 'Indie Flower', 'automotive-centre' ),
        'IM Fell English SC' => __( 'IM Fell English SC', 'automotive-centre' ),
        'Julius Sans One' => __( 'Julius Sans One', 'automotive-centre' ),
        'Josefin Slab' => __( 'Josefin Slab', 'automotive-centre' ),
        'Josefin Sans' => __( 'Josefin Sans', 'automotive-centre' ),
        'Kanit' => __( 'Kanit', 'automotive-centre' ),
        'Lobster' => __( 'Lobster', 'automotive-centre' ),
        'Lato' => __( 'Lato', 'automotive-centre' ),
        'Lora' => __( 'Lora', 'automotive-centre' ),
        'Libre Baskerville' => __( 'Libre Baskerville', 'automotive-centre' ),
        'Lobster Two' => __( 'Lobster Two', 'automotive-centre' ),
        'Merriweather' => __( 'Merriweather', 'automotive-centre' ),
        'Monda' => __( 'Monda', 'automotive-centre' ),
        'Montserrat' => __( 'Montserrat', 'automotive-centre' ),
        'Muli' => __( 'Muli', 'automotive-centre' ),
        'Marck Script' => __( 'Marck Script', 'automotive-centre' ),
        'Noto Serif' => __( 'Noto Serif', 'automotive-centre' ),
        'Open Sans' => __( 'Open Sans', 'automotive-centre' ),
        'Overpass' => __( 'Overpass', 'automotive-centre' ),
        'Overpass Mono' => __( 'Overpass Mono', 'automotive-centre' ),
        'Oxygen' => __( 'Oxygen', 'automotive-centre' ),
        'Orbitron' => __( 'Orbitron', 'automotive-centre' ),
        'Patua One' => __( 'Patua One', 'automotive-centre' ),
        'Pacifico' => __( 'Pacifico', 'automotive-centre' ),
        'Padauk' => __( 'Padauk', 'automotive-centre' ),
        'Playball' => __( 'Playball', 'automotive-centre' ),
        'Playfair Display' => __( 'Playfair Display', 'automotive-centre' ),
        'PT Sans' => __( 'PT Sans', 'automotive-centre' ),
        'Philosopher' => __( 'Philosopher', 'automotive-centre' ),
        'Permanent Marker' => __( 'Permanent Marker', 'automotive-centre' ),
        'Poiret One' => __( 'Poiret One', 'automotive-centre' ),
        'Quicksand' => __( 'Quicksand', 'automotive-centre' ),
        'Quattrocento Sans' => __( 'Quattrocento Sans', 'automotive-centre' ),
        'Raleway' => __( 'Raleway', 'automotive-centre' ),
        'Rubik' => __( 'Rubik', 'automotive-centre' ),
        'Rokkitt' => __( 'Rokkitt', 'automotive-centre' ),
        'Russo One' => __( 'Russo One', 'automotive-centre' ),
        'Righteous' => __( 'Righteous', 'automotive-centre' ),
        'Slabo' => __( 'Slabo', 'automotive-centre' ),
        'Source Sans Pro' => __( 'Source Sans Pro', 'automotive-centre' ),
        'Shadows Into Light Two' => __( 'Shadows Into Light Two', 'automotive-centre'),
        'Shadows Into Light' => __( 'Shadows Into Light', 'automotive-centre' ),
        'Sacramento' => __( 'Sacramento', 'automotive-centre' ),
        'Shrikhand' => __( 'Shrikhand', 'automotive-centre' ),
        'Tangerine' => __( 'Tangerine', 'automotive-centre' ),
        'Ubuntu' => __( 'Ubuntu', 'automotive-centre' ),
        'VT323' => __( 'VT323', 'automotive-centre' ),
        'Varela Round' => __( 'Varela Round', 'automotive-centre' ),
        'Vampiro One' => __( 'Vampiro One', 'automotive-centre' ),
        'Vollkorn' => __( 'Vollkorn', 'automotive-centre' ),
        'Volkhov' => __( 'Volkhov', 'automotive-centre' ),
        'Yanone Kaffeesatz' => __( 'Yanone Kaffeesatz', 'automotive-centre' )
		);
	}

	/**
	 * Returns the available font weights.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_font_weight_choices() {

		return array(
			'' => esc_html__( 'No Fonts weight', 'automotive-centre' ),
			'100' => esc_html__( 'Thin',       'automotive-centre' ),
			'300' => esc_html__( 'Light',      'automotive-centre' ),
			'400' => esc_html__( 'Normal',     'automotive-centre' ),
			'500' => esc_html__( 'Medium',     'automotive-centre' ),
			'700' => esc_html__( 'Bold',       'automotive-centre' ),
			'900' => esc_html__( 'Ultra Bold', 'automotive-centre' ),
		);
	}

	/**
	 * Returns the available font styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_font_style_choices() {

		return array(
			'' => esc_html__( 'No Fonts Style', 'automotive-centre' ),
			'normal'  => esc_html__( 'Normal', 'automotive-centre' ),
			'italic'  => esc_html__( 'Italic', 'automotive-centre' ),
			'oblique' => esc_html__( 'Oblique', 'automotive-centre' )
		);
	}
}
