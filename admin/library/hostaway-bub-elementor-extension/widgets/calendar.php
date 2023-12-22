<?php

/**
 * Hostaway Property Title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Hostaway_Calendar extends \Elementor\Widget_Base
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
        return 'property_calendar';
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
        return __('Availability Calendar', 'hostaway-elementor-extension');
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
                'label' => __('Widget Settings', 'eicon-wordpress-light'),
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
            'number_of_months',
            [
                'label' => __('Number of Months to Display', 'eicon-wordpress-light'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '2',
				'options' => [
					'1' => esc_html__( 'One', 'hostaway-elementor-extension' ),
					'2' => esc_html__( 'Two', 'hostaway-elementor-extension' ),
				],
            ]
        );

        
        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'eicon-wordpress-light'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __('Enter button text ..', 'eicon-wordpress-light'),
                'default' => 'Book Now'
            ]
        );

        $this->add_control(
            'button_type',
            [
                'label' => __('Button Type', 'eicon-wordpress-light'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'checkout',
				'options' => [
					'checkout' => esc_html__( 'Checkout', 'hostaway-elementor-extension' ),
					'inquiry' => esc_html__( 'Inquiry', 'hostaway-elementor-extension' ),
				],
            ]
        );


        $this->add_control(
			'main_color',
			[
				'label' => esc_html__( 'Main Color', 'hostaway-elementor-extension' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ff0000',
			]
		);

        $this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'hostaway-elementor-extension' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
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

        $property_id = $settings['property_id'];

        $display = false;

        $booking_engine_url = get_option('hostaway_booking_engine_url');


        if($booking_engine_url == '') {
            echo 'Booking engine URL is required to use this widget. Please set it on the Hostaway settings <a target="blank" href="/wp-admin/edit.php?post_type=hostaway_bub&page=hostaway_settings">page</a>.';
            exit;
        }

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
            echo '<div id="hostaway-calendar-widget"></div>
            <script src="https://d2q3n06xhbi0am.cloudfront.net/calendar.js"></script>
            <script>
              window.hostawayCalendarWidget({
                baseUrl: "'.$booking_engine_url.'",
                listingId: '.$property_id.',
                numberOfMonths: '.$settings['number_of_months'].', 
                openInNewTab: true, 
                rounded: true,
                button: {
                  action: "'.$settings['button_type'].'", 
                  text: "'.$settings['button_text'].'",
                },
                color: {
                  mainColor: "'.$settings['main_color'].'",
                  frameColor: "#000000",
                  textColor: "'.$settings['text_color'].'",
                },
              })
            </script>';
        }
    }
}
