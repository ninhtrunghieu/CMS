<?php
if( !function_exists('zcswr_activate_plugin')) {
	function zcswr_activate_plugin(){
		// 5.8 < 5.0
		if( version_compare( get_bloginfo( 'version' ), '5.8', '<' ) ){
			wp_die( __( "You must update WordPress to use this plugin.", 'sonweb' ) );
		}
		
		global $wpdb;
		$today = date("Y-m-d");
		$table_name = $wpdb->prefix . 'chatzalosw';
		
		$charset_collate = $wpdb->get_charset_collate();
	
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			clickdate date DEFAULT '0000-00-00' NOT NULL,
			click int NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
	
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
		
		$wpdb->insert( 
			$table_name, 
			array( 
				'clickdate' => $today, 
				'click' => 0, 
			) 
		);
	}
}

