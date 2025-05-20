<?php

/*
 * Plugin Name: CMS
 * Description: About CMS Hotline:0932.644.183
 * Version: 1.0
 * Author: Hung
 * Author URI: 
 * Text Domain: Hung
 */
 
// Setup
define( 'INFO_PLUGIN_URL', __FILE__ );

// Includes
//include( 'includes/activate.php' );

// Hooks
function custom_admin_logo() {
    echo '<style type="text/css">
        body.login div#login h1 a {
            background-image: url("' . get_site_url() . '/wp-content/uploads/logo-2.png");
            background-size: contain;
            width: 100%;
            height: 200px;
        }
    </style>';
}
add_action( 'login_enqueue_scripts', 'custom_admin_logo' );


// Shortcodes
// Dùng trình soạn thảo cũ
add_filter( 'use_block_editor_for_post', '__return_false' );

// Disable Woocommerce Header in WP Admin 
add_action('admin_head', 'Hide_WooCommerce_Breadcrumb');

function Hide_WooCommerce_Breadcrumb() {
  echo '<style>
    .woocommerce-layout__header {
        display: none;
    }
    .woocommerce-layout__activity-panel-tabs {
        display: none;
    }
    .woocommerce-layout__header-breadcrumbs {
        display: none;
    }
    .woocommerce-embed-page .woocommerce-layout__primary{
        display: none;
    }
    .woocommerce-embed-page #screen-meta, .woocommerce-embed-page #screen-meta-links{top:0;}
    </style>';
}
?>