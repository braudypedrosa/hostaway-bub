<?php

/**
 * Hostaway Property Title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Hostaway_Property_Data extends \Elementor\Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve oEmbed widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'property_data';
    }

    /**
     * Get widget title.
     *
     * Retrieve oEmbed widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Property Data', 'hostaway-elementor-extension');
    }

    /**
     * Get widget icon.
     *
     * Retrieve oEmbed widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-wordpress-light';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the oEmbed widget belongs to.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['hostaway-widgets'];
    }

    protected function _register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'eicon-wordpress-light'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'property_id',
            [
                'label' => __('Property ID', 'eicon-wordpress-light'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => __('', 'eicon-wordpress-light'),
            ]
        );

        $this->add_control(
            'type_of_data',
            [
                'label' => __('Data to display', 'eicon-wordpress-light'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '2',
				'options' => [
					'title' => esc_html__( 'Title', 'hostaway-elementor-extension' ),
					'content' => esc_html__( 'Content', 'hostaway-elementor-extension' ),
                    'rules' => esc_html__( 'House Rules', 'hostaway-elementor-extension' ),
                    'amenities' => esc_html__( 'Amenities', 'hostaway-elementor-extension' ),
				],
            ]
        );
        

        $this->add_control(
            'wrapper',
            [
                'label' => __('Wrapper', 'eicon-wordpress-light'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '2',
				'options' => [
					'div' => esc_html__( 'div', 'hostaway-elementor-extension' ),
					'p' => esc_html__( 'p', 'hostaway-elementor-extension' ),
                    'h1' => esc_html__( 'h1', 'hostaway-elementor-extension' ),
                    'h2' => esc_html__( 'h2', 'hostaway-elementor-extension' ),
                    'h3' => esc_html__( 'h3', 'hostaway-elementor-extension' ),
				],
            ]
        );

        $this->add_control(
            'additional_class',
            [
                'label' => __('Additional Class', 'eicon-wordpress-light'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'description' => 'For multiple classes, please separate it with a space.',
                'placeholder' => __('', 'eicon-wordpress-light'),
            ]
        );

        $this->end_controls_section();
    }


    /**
     * Render oEmbed widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $post_id = get_queried_object_id();

        $settings = $this->get_settings_for_display();

        $display = false;

        $property_id = $settings['property_id'];

        if ( get_post_type( $post_id ) === 'hostaway_bub' ) { 
            
            $display = true;
            $property_id = get_post_meta($post_id, 'hostaway_listing_id', true);

        } else {
            if($property_id == '') {
                echo 'Not a property page! Please provide the Property ID.';
            } else {

                global $wpdb;

                $sql = "SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'hostaway_listing_id' AND meta_value='".$property_id."'";

                $result = $wpdb->get_results($sql,ARRAY_A);
                $post_id = isset($result[0]['post_id']) ? $result[0]['post_id'] : '';

                if($post_id == null) {
                    echo 'Property not found!';
                } else {
                    $display = true;
                    $property_id = get_post_meta($post_id, 'hostaway_listing_id', true);
                }
            }
        }

     

        if( $display ) {
            echo do_shortcode('[display_property_data wrapper="'.$settings['wrapper'].'" data="'.$settings['type_of_data'].'" propertyid="'.$property_id.'" additionalclass="'.$settings['additional_class'].'"]');
        }
    }
}
