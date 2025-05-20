<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !function_exists('zcswr_enqueue_scripts') ) {
	function zcswr_enqueue_scripts() {
		wp_enqueue_style('wtl_phonecall', ZCSWRP_PLUGIN_URL . 'assets/phonecall.css' );
		
	
		wp_register_script( 
			'r_main', ZCSWRP_PLUGIN_URL . 'js/main.js', ['jquery'], '1.0.0', true 
		);
	
		wp_localize_script( 'r_main', 'zalo_obj', [
			'ajax_url'      =>  admin_url( 'admin-ajax.php' )
		]);
	
		wp_enqueue_script( 'r_main' );
	}
	add_action('wp_enqueue_scripts','zcswr_enqueue_scripts',10);
	
}

if (!function_exists('zcswr_enqueue_admin_js') ) {
	function zcswr_enqueue_admin_js() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style('customzl', ZCSWRP_PLUGIN_URL . 'assets/customzal_adcss.css' );
		 // Make sure to add the wp-color-picker dependecy to js file
		wp_enqueue_script( 'jscolorpck',ZCSWRP_PLUGIN_URL . 'js/jscolorpk.js', array( 'jquery', 'wp-color-picker' ), '', true  );
		 
	}
	add_action('admin_enqueue_scripts','zcswr_enqueue_admin_js',100);
}

?>