<?php
/*
Plugin Name: Estatik Bookings
Description: Create booking.
Version: 1.0.0
Author: Dariia Repina
*/

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Estatik_Bookings')) {

    class Estatik_Bookings
    {

        function __construct()
        {

            register_activation_hook(__FILE__, array($this, 'ebwp_activate'));

            register_deactivation_hook(__FILE__, array($this, 'ebwp_deactivate'));

            add_action('init', array($this, 'ebwp_register_estatik_booking_post_type'));

            add_action('add_meta_boxes', array($this, 'ebwp_add_estatik_booking_meta_box'));

            add_action('save_post', array($this, 'ebwp_save_estatik_booking_meta_data'));

            add_filter('the_content', array($this, 'ebwp_display_estatik_booking_data'));

            add_action('admin_menu', array($this, 'ebwp_estatik_booking_settings_page'));

            add_action('admin_init', array($this, 'ebwp_register_settings'));

        }

        function ebwp_activate()
        {
            $this->ebwp_register_estatik_booking_post_type();

            flush_rewrite_rules();
        }

        function ebwp_deactivate()
        {

            flush_rewrite_rules();

        }

        function ebwp_register_estatik_booking_post_type()
        {
            $labels = array(
                'name' => __('Estatik Bookings'),
                'singular_name' => __('Estatik Booking'),
                'add_new' => __('Add New Estatik Booking'),
                'edit_item' => __('Edit Estatik Booking'),
                'new_item' => 'New Estatik Booking',
                'view_item' => 'View Estatik Booking',
                'search_items' => 'Search Estatik Booking',
            );

            $args = array(
                'labels' => $labels,
                'menu_icon' => 'dashicons-calendar-alt',
                'public' => true,
                'has_archive' => true,
                'supports' => array('title', 'thumbnail'),
            );

            register_post_type('ebwp-estatik-booking', $args);
        }


        function ebwp_add_estatik_booking_meta_box()
        {
            add_meta_box('ebwp_estatik_booking_meta_box', __('Booking Info'), array($this, 'ebwp_render_estatik_booking_meta_box'), 'ebwp-estatik-booking', 'normal', 'high');
        }

        function ebwp_estatik_booking_settings_page()
        {
            add_submenu_page(
                'edit.php?post_type=ebwp-estatik-booking',
                'Settings',
                'Settings',
                'manage_options',
                'ebwp_estatik-booking_settings',
                array($this, 'ebwp_render_settings_page'),
            );
        }

        // Render settings page
        public function ebwp_render_settings_page()
        {
            ?>
            <div class="wrap">
                <h2>
                    <?php echo __('Settings', 'ebwp'); ?>
                </h2>
                <form method="post" action="options.php">
                    <?php settings_fields('ebwp_estatik-booking_settings_group'); ?>
                    <?php do_settings_sections('ebwp_estatik-booking_settings'); ?>
                    <?php submit_button(); ?>
                </form>
            </div>
            <?php
        }

       
        public function ebwp_register_settings()
        {
            register_setting('ebwp_estatik-booking_settings_group', 'ebwp_google_map_api_key');
            add_settings_section('ebwp_estatik-booking_settings_section', 'API Keys', array($this, 'ebwp_settings_section_callback'), 'ebwp_estatik-booking_settings');
            add_settings_field('ebwp_google_map_api_key', 'Google Maps API Key (required)', array($this, 'ebwp_text_input_callback'), 'ebwp_estatik-booking_settings', 'ebwp_estatik-booking_settings_section');
        }

        public function ebwp_settings_section_callback()
        {
            echo __('Enter your Google Maps API Key:', 'ebwp');
        }

        // Creating Input for API Key
        public function ebwp_text_input_callback()
        {
            $google_map_api_key = get_option('ebwp_google_map_api_key');

            echo '<input type="text" name="ebwp_google_map_api_key" value="' . esc_attr($google_map_api_key) . '" />';
        }

        //Formatted Date
        function ebwp_date_formatted($date)
        {
            $date_formatted = date('Y-m-d\TH:i', $date);

            return $date_formatted;
        }

        // Displaying metadata in the admin panel
        function ebwp_render_estatik_booking_meta_box($post)
        {
            $ebwp_meta_box_template = plugin_dir_path(__FILE__) . 'meta-box-booking-info-template.php';

            $start_date = get_post_meta($post->ID, 'ebwp_start_date', true);

            if ($start_date) {
                $start_date_formatted = $this->ebwp_date_formatted($start_date);
            }

            $end_date = get_post_meta($post->ID, 'ebwp_end_date', true);

            if ($end_date) {
                $end_date_formatted = $this->ebwp_date_formatted($end_date);
            }

            $address = get_post_meta($post->ID, 'ebwp_address', true);

            if (file_exists($ebwp_meta_box_template)) {
                include $ebwp_meta_box_template;
            }

        }

        // Saving metadata
        function ebwp_save_estatik_booking_meta_data($post_id)
        {
            if (array_key_exists('ebwp_start_date', $_POST)) {
                $start_date = strtotime($_POST['ebwp_start_date']);
                update_post_meta($post_id, 'ebwp_start_date', $start_date);
            }

            if (array_key_exists('ebwp_end_date', $_POST)) {
                $end_date = strtotime($_POST['ebwp_end_date']);
                update_post_meta($post_id, 'ebwp_end_date', $end_date);
            }

            if (array_key_exists('ebwp_address', $_POST)) {
                update_post_meta($post_id, 'ebwp_address', sanitize_text_field($_POST['ebwp_address']));
            }

        }

        // Receiving and processing data for the front
        function ebwp_display_estatik_booking_data($content)
        {
            global $post;

            if ($post->post_type === 'ebwp-estatik-booking') {
                $start_date = get_post_meta($post->ID, 'ebwp_start_date', true);

                if ($start_date) {
                    $start_date_formatted = $this->ebwp_date_formatted($start_date);
                }

                $end_date = get_post_meta($post->ID, 'ebwp_end_date', true);

                if ($end_date) {
                    $end_date_formatted = $this->ebwp_date_formatted($end_date);
                }

                $address = get_post_meta($post->ID, 'ebwp_address', true);

                $formatted_date_start = date_i18n('j F Y., H:i', strtotime($start_date_formatted));

                $formatted_date_end = date_i18n('j F Y., H:i', strtotime($end_date_formatted));

                $google_map_api_key = get_option('ebwp_google_map_api_key');

                $encoded_address = urlencode($address);

                $ebwp_meta_box_template = plugin_dir_path(__FILE__) . 'frontend/content-frontend.php';

                ob_start();

                include $ebwp_meta_box_template;

                $content = ob_get_clean();

                return $content;
            }

        }

    }

    $estatik_bookings = new Estatik_Bookings();
}
