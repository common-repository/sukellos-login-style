<?php

namespace Sukellos\Admin;

use Sukellos\WP_Sukellos_Login_Style_Loader;
use Sukellos\WPFw\Singleton;
use Sukellos\WPFw\WP_Plugin_Admin;
use Sukellos\WPFw\Utils\WP_Log;
use Sukellos\WPFw\AdminBuilder\Item_Type;
use Sukellos\WPFw\Utils\WP_Helper;

defined( 'ABSPATH' ) or exit;

/**
 * Admin class.
 * Main admin is used as controller to init admin menu and all other admin pages
 *
 * @since 1.0.0
 */
class WP_Sukellos_Login_Style_Admin extends WP_Plugin_Admin {

    // Use Trait Singleton
    use Singleton;

    /**
     * Default init method called when instance created
     * This method can be overridden if needed.
     *
     * @since 1.0.0
     * @access protected
     */
    public function init() {

        parent::init();

        // Add action to delegate settings fields creation to Sukellos Fw Tools admin
        // Use priority to order Tools
        add_action( 'sukellos_fw/admin/create_tools_fields', array( $this, 'action_create_tools_fields' ), 12, 1 );

        WP_Log::info( 'WP_Sukellos_Login_Style_Admin->init OK!',[], WP_Sukellos_Login_Style_Loader::instance()->get_text_domain());
    }


    /**
     * Gets the plugin configuration URL
     * This is used to build actions list in plugins page
     * Leave blank ('') to disable
     *
     * @since 1.0.0
     *
     * @return string plugin settings URL
     */
    public function get_settings_url() {

        return admin_url( 'admin.php?page='.WP_Sukellos_Login_Style_Loader::instance()->get_options_suffix_param().'_tools' );
    }

    /**
     *          ===============
     *      =======================
     *  ============ HOOKS ===========
     *      =======================
     *          ===============
     */


    /***
     * Adding CSS and JS into header
     * Default add assets/admin.css and assets/admin.js
     */
    public function admin_enqueue_scripts() {}


    /***
     * Admin page
     * Settings managed by main Sukellos Fw Tools admin
     */
    public function create_items() {}

    /**
     * Tools fields creation
     */
    public function action_create_tools_fields( $admin_page ) {

        // Admin page is a Tabs page
        $admin_tab = $admin_page->create_tab(
            array(
                'id' => WP_Sukellos_Login_Style_Loader::instance()->get_options_suffix_param().'_login_style_tab',
                'name' => WP_Helper::sk__('Login Style' ),
                'desc' => '',
            )
        );

        // Create a header
        $admin_tab->create_header(
            array(
                'id' => WP_Sukellos_Login_Style_Loader::instance()->get_options_suffix_param().'_header_login_page',
                'name' => WP_Helper::sk__('Login Style' ),
                'desc' => WP_Helper::sk__( 'Customize the WordPress login page with colors and logo' ),
            )
        );

        // Create a color option field
        $admin_tab->create_option(
            array(
                'type' => Item_Type::COLOR,
                // Common
                'id' => WP_Sukellos_Login_Style_Loader::instance()->get_options_suffix_param().'_login_main_color',
                'name' => WP_Helper::sk__('Main color' ),
                'desc' => WP_Helper::sk__('Main color is used to customize the whole login page' ),
                'default' => '#2271b1',
            )
        );

        // Create a color option field
        $admin_tab->create_option(
            array(
                'type' => Item_Type::COLOR,
                // Common
                'id' => WP_Sukellos_Login_Style_Loader::instance()->get_options_suffix_param().'_login_button_text_color',
                'name' => WP_Helper::sk__('Button font color' ),
                'desc' => WP_Helper::sk__('Button font color is used to customize the font color of the button. May be used in coherency with the main color as background button color.' ),
                'default' => '#FFFFFF',
            )
        );

        // Check if the logo has already been uploaded, then display the width
        $custom_logo_url_post_id = get_option( WP_Sukellos_Login_Style_Loader::instance()->get_options_suffix_param().'_login_logo', '' );
        $custom_logo_width = null;
        $custom_logo_height = null;
        if ( $custom_logo_url_post_id !== '' ) {

            $post = get_post($custom_logo_url_post_id);
            if ( !is_null( $post )) {

                $img = wp_get_attachment_image_src( $post->ID, "full" );

                $custom_logo_width = $img[1];
                $custom_logo_height = $img[2];
            }
        }

        // Create an upload option field
        $admin_tab->create_option(
            array(
                'type' => Item_Type::UPLOAD,
                // Common
                'id' => WP_Sukellos_Login_Style_Loader::instance()->get_options_suffix_param().'_login_logo',
                'name' => WP_Helper::sk__('Logo' ),
                'desc' => WP_Helper::sk__('Logo to replace the default Wordpress one.<br/>Recommended max width: 320px.').' '.(!is_null($custom_logo_width)?'<br/>'.WP_Helper::sk__( 'Actual format' ).':'.$custom_logo_width.' x '.$custom_logo_height.' px':''),
                // Specific
                'placeholder' => '', // (Optional) The placeholder label shown when the input field is blank
                'size' => 'thumbnail', // The size of the image to use, full or thumbnail
            )
        );
        WP_Log::debug('Settings: ', ['$custom_logo_width' => $custom_logo_width, '$custom_logo_height' => $custom_logo_height]);

        if ( !is_null( $custom_logo_width ) ) {

            $admin_tab->create_option(
                array(
                    'type' => Item_Type::TEXT,
                    // Common
                    'id' => WP_Sukellos_Login_Style_Loader::instance()->get_options_suffix_param().'_login_logo_width',
                    'name' => WP_Helper::sk__('Logo width' ),
                    'desc' => WP_Helper::sk__('Width used for logo display in px' ),
                    'default' => $custom_logo_width,
                    'unit' => 'px',
                    'size' => 'small',
                )
            );
        }
        if ( !is_null( $custom_logo_height ) ) {

            $admin_tab->create_option(
                array(
                    'type' => Item_Type::TEXT,
                    // Common
                    'id' => WP_Sukellos_Login_Style_Loader::instance()->get_options_suffix_param().'_login_logo_height',
                    'name' => WP_Helper::sk__('Logo height' ),
                    'desc' => WP_Helper::sk__('Height used for logo display in px' ),
                    'default' => $custom_logo_height,
                    'unit' => 'px',
                    'size' => 'small',
                )
            );
        }
    }
}