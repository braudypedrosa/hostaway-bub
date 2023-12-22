<?php

/**
 * Hostaway Property Title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Hostaway_Property_Search extends \Elementor\Widget_Base
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
        return 'property_search';
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
        return __('Property Search', 'hostaway-elementor-extension');
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

        $display = false;

        $booking_engine_url = get_option('hostaway_booking_engine_url');


        if($booking_engine_url == '') {
            echo 'Booking engine URL is required to use this widget. Please set it on the Hostaway settings <a target="blank" href="/wp-admin/edit.php?post_type=hostaway_bub&page=hostaway_settings">page</a>.';
            exit;
        } else {
            echo '<script src="https://d2q3n06xhbi0am.cloudfront.net/widget.js?1640277196"></script>
            <script>
              window.searchBar({
                baseUrl: "'.$booking_engine_url.'",
                showLocation: true,
                color: "#cc2dcf",
                rounded: true,
                openInNewTab: true,
              });
            </script>
            <div id="hostaway-booking-widget"></div>';
        }

    }
}
