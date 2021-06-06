<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Buddypress_Shortcode_Groups_Widget extends Widget_Base {

	public function get_name() {
		return 'buddypress_shortcode_groups_widget';
	}

	public function get_title() {
		return esc_html__('Groups', 'shortcodes-for-buddypress' );
	}

	public function get_icon() {
		return 'eicon-toggle';
	}

	public function get_categories() {
		return [ 'buddypress-widgets' ];
	}
	
	public function get_group_types(){
		$group_types = array( '' => 'All' );
		if ( function_exists( 'bp_get_group_type' ) ) {
			$get_group_types       = bp_groups_get_group_types( array(), 'objects' );
			foreach( $get_group_types as $key=>$value) {
				$group_types[$key]	= $value->name;
			}
		}		
		
		return $group_types;
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Query', 'shortcodes-for-buddypress' ),
			]
		);
		
		$this->add_control(
			'sfb_title',
			[
				'label'       => __( 'Title', 'shortcodes-for-buddypress' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Add activity title', 'shortcodes-for-buddypress' )
			]
		);
		
		
		
		
		$this->add_control(
			'sfb_per_page',
			[
				'label'       => __( 'Display number of group', 'shortcodes-for-buddypress' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 20,
				'description' => __( 'How many members display on page.', 'shortcodes-for-buddypress' )
			]
		);
		
		$this->add_control(
			'go_sfb_pro_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => \Shortcodes_For_Buddypress_Public::sfb_go_pro_template(
					[
						'title'    => __( 'Shortcodes for BuddyPress PRO', 'shortcodes-for-buddypress' ),
						'messages' => [
							__( 'Power up up your listing with custom queries and templates.', 'shortcodes-for-buddypress' ),
						],
						'link'     => 'https://wbcomdesigns.com/downloads/shortcodes-for-buddypress-pro',
					]
				),
			]
		);
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Listing Groups', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'sfb_list_item_background',
				'label'    => __( 'Background', 'stax-buddy-builder' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} #groups-list > li > .list-wrap',
			]
		);

		$this->start_controls_tabs( 'tabs_listing_style' );

		$this->start_controls_tab(
			'sfb_tab_listing_normal',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'sfb_listing_box_shadow',
				'selector' => '{{WRAPPER}} #groups-list > li > .list-wrap',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'sfb_tab_listing_hover',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'sfb_listing_box_shadow_hover',
				'selector' => '{{WRAPPER}} #groups-list > li > .list-wrap:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'sfb_hr_listing',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'sfb_listing_border',
				'selector'  => '{{WRAPPER}} #groups-list > li > .list-wrap',				
			]
		);

		$this->add_control(
			'sfb_listing_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #groups-list > li > .list-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);

		$this->add_responsive_control(
			'sfb_listing_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #groups-list > li > .list-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'sfb_listing_border_one',
				'selector'  => '{{WRAPPER}} #groups-list > li',
				
			]
		);

		$this->add_control(
			'sfb_listing_border_radius_one',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #groups-list > li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);

		$this->add_responsive_control(
			'sfb_listing_padding_one',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #groups-list > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);

		$this->end_controls_section();

	}
	
	protected function render() {
		global $groups_args;
		$settings          = $this->get_settings_for_display();
		$current_component = static function () {
			return 'groups';
		};
		$current_component_groups = static function () {
			return true;
		};
		
		add_filter( 'bp_current_component', $current_component );
		//add_filter( 'bp_is_current_component', $current_component );
		//add_filter( 'groups_get_current_group', $current_component_groups );
		//add_filter( 'bp_is_active', $current_component_groups );
		
		add_filter( 'bp_get_groups_pagination_count', '__return_zero' );
		add_filter( 'bp_get_groups_pagination_links', '__return_zero' );
		
		$loop_classes = static function () use ( $classes ) {
			
			$classes = [];
			$classes[] = 'item-list';
			$classes[] = 'groups-list';
			$classes[] = 'bp-list';
			
			$bp_nouveau_appearance = get_option( 'bp_nouveau_appearance' );
			$groups_layout = ( isset($bp_nouveau_appearance['groups_layout']) && $bp_nouveau_appearance['groups_layout'] != '' ) ? $bp_nouveau_appearance['groups_layout'] : '3';
			
			if ( function_exists( 'bp_nouveau_customizer_grid_choices' )) {
				$grid_classes = bp_nouveau_customizer_grid_choices( 'classes' );			
				if ( isset( $grid_classes[ $groups_layout ] ) ) {
					$classes = array_merge( $classes, array(
						'grid',
						$grid_classes[ $groups_layout ],
					) );
				}
			} else {
				$classes = array_merge( $classes, array(
						'grid',
						'three',
					) );
			}
			
			
			
			return $classes;
		};
		add_filter( 'bp_nouveau_get_loop_classes', $loop_classes );
		
		$args = [
					'title'  	=> $settings['sfb_title'],	
					'per_page' 	=> $settings['sfb_per_page'],					
					'object' 	=> 'groups',								
				];
				
		$groups_args = $args;
		unset($groups_args['title']);
		
		$groups_ajax_querystring = static function ( $ajax_querystring, $object ) {
			global $groups_args;
			$qs = array();
			
			if( !empty($groups_args) ) {
				foreach( $groups_args as $key=>$value) {
					if ( $value != '' ) {
						$qs[] = $key . "=" . $value;
					}
				}
			}
			
			$query_string = empty( $qs ) ? '' : join( '&', (array) $qs );
			if ( $query_string != '' ) {
				$ajax_querystring .= '&' . $query_string;
			}
			
			return $ajax_querystring;
		};
		
		add_filter( 'bp_ajax_querystring', $groups_ajax_querystring, 99, 2 );	
		

		?>
		<div class="buddypress-groups-element">
		
			<?php if ( $args['title'] ) : ?>
				<h3 class="activity-shortcode-title"><?php echo $args['title']; ?></h3>
			<?php endif; ?>
			
			<div id="buddypress" class="buddypress-wrap bp-dir-hori-nav groups">
				<?php bp_nouveau_before_groups_directory_content(); ?>
				<?php bp_nouveau_template_notices(); ?>

				<div class="screen-content">
					<div id="groups-dir-list" class="groups dir-list" data-bp-list="">
						<?php bp_get_template_part( 'groups/groups-loop' ); ?>
					</div>

					<?php bp_nouveau_after_groups_directory_content(); ?>
				</div>
			</div>
		</div>
		
		<?php
		
		remove_filter( 'bp_current_component', $current_component );
		//remove_filter( 'bp_is_current_component', $current_component );
		remove_filter( 'bp_nouveau_get_loop_classes', $loop_classes );
		
		remove_filter( 'bp_ajax_querystring', $groups_ajax_querystring, 99, 2 );
		remove_filter( 'bp_get_groups_pagination_count', '__return_zero' );
		remove_filter( 'bp_get_groups_pagination_links', '__return_zero' );
				
	}
	
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Buddypress_Shortcode_Groups_Widget() );