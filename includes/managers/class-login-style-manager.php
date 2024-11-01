<?php
namespace Sukellos;

use Sukellos\WP_Sukellos_Login_Style_Loader;
use Sukellos\WPFw\WP_PLoad;
use Sukellos\WPFw\Utils\WP_Log;
use WP_Query;
use Sukellos\WPFw\Singleton;

defined( 'ABSPATH' ) or exit;

/**
 * Login Style management
 *
 * @since 1.0.0
 */
class Login_Style_Manager {

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

        // To customize logo
        add_action( 'login_head', array($this, 'action_login_head') );

        // To change URLs
        add_filter( 'lostpassword_url', array($this, 'filter_lostpassword_url'), 10, 2);
        add_filter( 'register_url', array($this, 'filter_register_url'), 10, 1);
        // To change Register text
        add_filter(  'gettext',  array($this, 'filter_register_text')  );
        add_filter(  'ngettext',  array($this, 'filter_register_text')  );
    }

    /**
     *          ===============
     *      =======================
     *  ============ HOOKS ===========
     *      =======================
     *          ===============
     */

    /**
     * Change logo and colors (CSS)
     */
    public function action_login_head() {
        $custom_logo_url_post_id = get_option( WP_Sukellos_Login_Style_Loader::instance()->get_options_suffix_param().'_login_logo' );
        $post = get_post($custom_logo_url_post_id);
        $custom_logo_url = $post->guid;

        $custom_logo_width = get_option( WP_Sukellos_Login_Style_Loader::instance()->get_options_suffix_param().'_login_logo_width' );
        $custom_logo_height = get_option( WP_Sukellos_Login_Style_Loader::instance()->get_options_suffix_param().'_login_logo_height' );
        if ( ($custom_logo_width === '') || ($custom_logo_height === '') ) {

            $img = wp_get_attachment_image_src($post->ID, "full");

            if ($custom_logo_width === '') {
                $custom_logo_width = $img[1];
            }
            if ($custom_logo_height === '') {

                $custom_logo_height = $img[2];
            }
        }

        $custom_main_color = get_option( WP_Sukellos_Login_Style_Loader::instance()->get_options_suffix_param().'_login_main_color' );
        $custom_font_color = get_option( WP_Sukellos_Login_Style_Loader::instance()->get_options_suffix_param().'_login_button_text_color' );

        WP_Log::debug('Login_Page_Manager->action_login_head', ['$custom_logo_width' => $custom_logo_width, '$custom_logo_height' => $custom_logo_height, '$custom_main_color' => $custom_main_color]);

        $html_content = '
            <style>
                h1 a{';
        if ($custom_logo_url) {

            $html_content .= 'background-image:url("'.$custom_logo_url.'")!important;';
        }
        if ( ($custom_logo_width !== '') || ($custom_logo_height !== '') ) {

            $html_content .= '
                    background-size:'.( ($custom_logo_width !== '') ? $custom_logo_width.'px ' : '' ).( ($custom_logo_height !== '') ? $custom_logo_height.'px ' : '' ).'!important;';
        }
        $html_content .= '
                }
                .login h1 a {';
        if ($custom_logo_height !== '') {

            $html_content .= 'height:'.$custom_logo_height.'px;';
        }
        if ($custom_logo_width !== '') {

            $html_content .= 'width:'.$custom_logo_width.'px;';
        }
        $html_content .= '
                } 
                .login .button,
                .login .button-primary,
                .login .button-secondary {
                    background: ' . $custom_main_color . ';
                    border-color: ' . $custom_main_color . ';
                    color: '.$custom_font_color.';
                    text-decoration: none;
                    text-shadow: none;
                }
                .login .button:hover,
                .login .button-secondary:hover,
                .login .button-primary:hover {
                    background: ' . $custom_main_color . ';
                    border-color: ' . $custom_main_color . ';
                    color: '.$custom_font_color.';
                    text-decoration: none;
                    text-shadow: none;
                }
                .login input[type=text]:focus,
                .login input[type=password]:focus {
                    border-color: '.$custom_main_color.';
                    box-shadow: 0 0 0 1px '.$custom_main_color.';
                }
                .login .button:focus,
                .login .button-primary:focus,
                .login .button-secondary:focus {
                    border-color: '.$custom_main_color.';
                    box-shadow: 0 0 0 1px '.$custom_main_color.';
                    background: ' . $custom_main_color . ';
                }
                .login .button:active,
                .login .button-primary:active,
                .login .button-secondary:active {
                    border-color: '.$custom_main_color.';
                    box-shadow: 0 0 0 1px '.$custom_main_color.';
                    background: ' . $custom_main_color . ';
                }
                .login select:focus,
                .login .button.wp-hide-pw:focus {
                    background: 0 0;
                    border-color: '.$custom_main_color.';
                    box-shadow: 0 0 0 1px '.$custom_main_color.';
                }
                .login .button.wp-hide-pw {
                    color: '.$custom_main_color.';
                }
                .login .message, .login .success {
                    border-left: 4px solid '.$custom_main_color.';
                }
                .login select:hover {
                    color: '.$custom_main_color.';
                }
            </style>
		';

        echo $html_content;
    }

    /**
     * To redirect register
     *
     * @param string $register
     * @return string
     */
    public function filter_register_url( $register ) {

        return $register;
    }

    /**
     * To redirect lost password
     *
     * @param string $lostpassword_url
     * @param string $redirect
     * @return string
     */
    public function filter_lostpassword_url( $lostpassword_url, $redirect ) {

        return $lostpassword_url;
    }


    /**
     * Change register text in login form
     * @param $translated
     * @return array|string|string[]
     */
    public function filter_register_text( $translated ) {
        $translated = str_ireplace(  'Register',  'Register',  $translated );
        return $translated;
    }
}
