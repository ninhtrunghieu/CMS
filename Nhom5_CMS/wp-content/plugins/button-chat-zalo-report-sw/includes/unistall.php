<?php
if ( !function_exists('zcswr_deactivate_plugin')) {
  function zcswr_deactivate_plugin() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'chatzalosw';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
  }
  
}


