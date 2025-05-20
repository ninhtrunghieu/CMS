<?php 
/**
 * Plugin Name: SW Info Product Extra Default
 * Description: Thêm thông tin mô tả sản phẩm mặc định dưới mô tả ngắn
 * Version: 1.0.0
 * Author: Hung
 * Author URI:
 * License: GPLv2
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists('swcwp_CustomWooProductSWPlugin') ) {
	class swcwp_CustomWooProductSWPlugin {
		public $plugin;
		private $options_setting;
		
		function __construct() {
			$this->swcwp_define_constants();
			$this->plugin = plugin_basename(__FILE__);
			$this->options_setting = $this->swget_options();
		}
		
		function swcwp_define_constants() {
			if ( !defined('SWCWP_PLUGIN_PATH' ) ) {
				define('SWCWP_PLUGIN_PATH', plugin_dir_path(__FILE__));
			}
			if ( !defined('SWCWP_PLUGIN_URL' ) ) {
				define('SWCWP_PLUGIN_URL', plugin_dir_url(__FILE__));
			}
		}
		
		function register() {
			add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
			add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
			add_action('admin_init',array($this,'swcwp_register_settings'));
			// callback hook add to cart
			add_action( 'woocommerce_before_add_to_cart_form',array($this, 'sw_woo_hook_cart_single' ) );
			// script hooks frontend
			
			add_action('wp_enqueue_scripts',array($this,'sw_enqueue'),10 );
		}
		
		// load page to menu
		public function add_admin_pages() {
			// Then the submenus
			add_submenu_page( 
				'edit.php?post_type=product',// parent plug
				'Thông tin sản phẩm mặc định',// Submenu Page Title
				'Mô tả mặc định', //  Submenu Title
				'manage_options', // capability
				'swcustom-woopact', // slug
				array($this,'swcustomwoop_act') // callback
			);
		}
		
		function swcwp_register_settings() {
			//register our settings
			
			register_setting( 
				'swcwp_options_woo', //  Option group
				'swcwp_setting_woo',//option name
				'sanitize' // callback
			);
		}
		
		function swget_options() {
			return wp_parse_args(get_option('swcwp_setting_woo'),$this->options_setting );
		}
		// create option page
		function swcustomwoop_act() {
			?>
				<div class="wrap">
				
					<h2 class="title-ponsw"><?php _e(' Thêm thông tin sản phẩm mặc định - SonWeb', 'sonweb');?></h2>
					<div class="donate">
						<a class="button button-large" href="<?php echo esc_url(' https://www.paypal.com/paypalme/sonwebtl/2usd');?>" target="_blank">
							<?php _e('Click to Donate - Paypal', 'sonweb');?>
						</a>
					</div><!--end donate-->
					<div id="poststuff">
						<div id="post-body">
							<div id="post-body-content">
								<div class="postbox ponsw-settings">
									<h3 class="hndle"><?php _e('Plugin Settings', 'sonweb');?></h3>
									<hr>
									<div class="inside">
										
										<form method="post" action="options.php" class="cnb-container">
											<?php settings_fields('swcwp_options_woo'); ?>
											<table class="form-table">
												<tr valign="top">
													<th scope="row"><?php _e('Bật hiển thị:','sonweb');?> </th>
													<td>
														<label>
															<input type="checkbox"
																name="swcwp_setting_woo[ck_turnon]"
																id="ck_turnon"
																value="1" <?php checked('1', intval(isset( $this->options_setting['ck_turnon']), true) ); ?>/>
																<?php _e('Bật hiển thị','sonweb');?>
														</label>
													</td>
												</tr>
												<tr valign="top">
													<th scope="row"><?php _e('Hiển thị mặc định:','sonweb');?></th>
													<td>
														
														<?php
															$is_empdefault = file_get_contents( SWCWP_PLUGIN_PATH . "templates/uu-dai.php");
															$val_txtp = $this->options_setting['editor_product_sw'] ? $this->options_setting['editor_product_sw'] : $is_empdefault;
															$settings = array(
																'teeny' => true,
																'textarea_rows' => 9,
																'tabindex' => 1,
																'wpautop' => false,
																'media_buttons' => false,
																'textarea_name' => 'swcwp_setting_woo[editor_product_sw]'
															);
															wp_editor( 
																$val_txtp, // value
																'swcwp_setting_woo_editor', // name 
																$settings
															); 
															
														?>
													</td>
													
												</tr>
											</table>
											
										<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Lưu thay đổi') ?>" /></p>
										</form>
									</div><!--end inside-->
								</div><!--end ponsw-->
								
							</div><!--end post-body-content-->
						</div>
					</div>
				</div>
			<?php
		}
		// display beforea add to cart form single product
		function sw_woo_hook_cart_single() {
			if ( $this->options_setting['ck_turnon'] )
			echo $this->options_setting['editor_product_sw'];
		}
		public function settings_link( $links ) {
			$url_set = admin_url( 'edit.php?post_type=product&page=swcustom-woopact' );
			$settings_link = "<a href='$url_set'>". __( 'Settings' ) .' </a>';
			array_push( $links, $settings_link );
			return $links;
		}
		// css frontend hook
		function sw_enqueue() {
			// enqueue all our scripts
			wp_enqueue_style('swcustom_woo', SWCWP_PLUGIN_URL . 'assets/swcustom_woo.css' );
			
		}
		
	}// end class
	$swcwp = new swcwp_CustomWooProductSWPlugin();
	$swcwp->register();
	
	
}

?>