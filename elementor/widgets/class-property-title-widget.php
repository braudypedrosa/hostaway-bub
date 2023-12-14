<?php 
namespace ElementorHostaway\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * Property Title widget class.
 *
 * @since 1.0.0
 */

class Hostaway_Property_Title extends Widget_Base {

    // constructor function
    public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style( 'hostaway-elementor-widgets', plugin_dir_url( __FILE__ ) . 'assets/hostaway-widgets.css', array(), '1.0.0' );
	}


    public function get_name() {
		return 'property-title';
	}

    public function get_title() {
		return __( 'Property Title', 'elementor-hostaway' );
	}

    public function get_icon() {
		return 'eicon-wordpress-light';
	}

    public function get_categories() {
		return array( 'hostaway_widgets' );
	}

    public function get_style_depends() {
		return array( 'hostaway-elementor-widgets' );
	}

    protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'elementor-hostaway' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'elementor-hostaway' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Title', 'elementor-hostaway' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => __( 'Description', 'elementor-hostaway' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Description', 'elementor-hostaway' ),
			)
		);

		$this->add_control(
			'content',
			array(
				'label'   => __( 'Content', 'elementor-hostaway' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => __( 'Content', 'elementor-awesomesauce' ),
			)
		);

		$this->end_controls_section();
	}

    protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'title', 'none' );
		$this->add_inline_editing_attributes( 'description', 'basic' );
		$this->add_inline_editing_attributes( 'content', 'advanced' );
		?>
		<h2 <?php echo $this->get_render_attribute_string( 'title' ); ?>><?php echo wp_kses( $settings['title'], array() ); ?></h2>
		<div <?php echo $this->get_render_attribute_string( 'description' ); ?>><?php echo wp_kses( $settings['description'], array() ); ?></div>
		<div <?php echo $this->get_render_attribute_string( 'content' ); ?>><?php echo wp_kses( $settings['content'], array() ); ?></div>
		<?php
	}

    protected function _content_template() {
		?>
		<#
		view.addInlineEditingAttributes( 'title', 'none' );
		view.addInlineEditingAttributes( 'description', 'basic' );
		view.addInlineEditingAttributes( 'content', 'advanced' );
		#>
		<h2 {{{ view.getRenderAttributeString( 'title' ) }}}>{{{ settings.title }}}</h2>
		<div {{{ view.getRenderAttributeString( 'description' ) }}}>{{{ settings.description }}}</div>
		<div {{{ view.getRenderAttributeString( 'content' ) }}}>{{{ settings.content }}}</div>
		<?php
	}

}