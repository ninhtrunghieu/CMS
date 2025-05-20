<?php 
/**
 * Plugin Name: Button Chat Zalo Report SW
 * Plugin URI: https://github.com/sonwebtl/ButtonChatZaloReport-SW
 * Description: Display button Chat Zalo and call phone and report click Zalo in day,change color button chat...
 * Version: 1.0.0
 * Author: Hung
 * Text Domain: button-chat-zalo-report-sw
 * Author URI: sonweb.net
 * License: GPLv2
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// setup

//define('SW_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Includes
include( 'includes/activate.php' );
include( 'includes/unistall.php' );
// Hooks
register_activation_hook(__FILE__, 'zcswr_activate_plugin' );
register_deactivation_hook(__FILE__, 'zcswr_deactivate_plugin');

if ( !class_exists('ZaloChatSWPlugin') ) {
    class ZCSWRP_ZaloChatSWPlugin {
        public function __construct()
        {
            define('ZCSWRP_PLUGIN_URL', plugin_dir_url( __FILE__ ));
            define('ZCSWRP_PLUGIN_PATH', plugin_dir_path(__FILE__));
        }

        public function initialize() {
            include_once(ZCSWRP_PLUGIN_PATH .'/includes/option-page.php');
            include_once(ZCSWRP_PLUGIN_PATH .'/includes/enqueue.php');
            include_once(ZCSWRP_PLUGIN_PATH .'/includes/frontend.php');
            // process when click zalo chat
            include_once(ZCSWRP_PLUGIN_PATH .'/includes/process.php');
            // test when click footer frontend
            //include_once(ZCSWRP_PLUGIN_PATH .'/includes/test.php');
        }
    }

    $zalochatsw = new ZCSWRP_ZaloChatSWPlugin();
    $zalochatsw->initialize();
}


 ?>
