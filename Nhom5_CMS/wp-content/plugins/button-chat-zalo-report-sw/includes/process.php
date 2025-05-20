<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if( !function_exists('zcswr_zaloprocess_init') ) {
	function zcswr_zaloprocess_init() {
		//do bên js để dạng json nên giá trị trả về dùng phải encode
		
		$today = date("Y-m-d");
		global $wpdb;
		//$last_id = 1;
		$table_name = $wpdb->prefix . 'chatzalosw';
		$sql = "select * from $table_name where clickdate = '$today' order by clickdate desc limit 1 ";
		$objzalo = $wpdb->get_row($sql);
		if ( empty($objzalo) ) {
			$wpdb->insert( 
				$table_name, 
				array( 
					'clickdate' => $today, 
					'click' => 1, 
				) 
			);
			$last_id = $wpdb->insert_id;
		} else {
			$last_id = $objzalo->id;
			$wpdb->update($table_name,array('click' =>$objzalo->click + 1,'clickdate'	=>$today ), array('id' => $last_id));
		}
		
		wp_send_json_success('Chào mừng bạn đến với '. $last_id);
		
		die();//bắt buộc phải có khi kết thúc
	}

	add_action( 'wp_ajax_zaloprocess', 'zcswr_zaloprocess_init' );
	add_action( 'wp_ajax_nopriv_zaloprocess', 'zcswr_zaloprocess_init' );
}

?>