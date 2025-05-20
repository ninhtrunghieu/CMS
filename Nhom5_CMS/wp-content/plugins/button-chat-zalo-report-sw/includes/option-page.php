<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* create menu & page dashboard */
add_action('admin_menu','zcswr_register_pcbsw_page');
function zcswr_register_pcbsw_page(){
	global $phonecall_title;
	add_submenu_page('options-general.php','Chat Zalo Report SW','Chat Zalo Report SW','manage_options','phone-call-zalo','zcswr_phone_callsw_setting_page');
	add_action('admin_init','zcswr_register_pcnsw__settings');
}
function zcswr_register_pcnsw__settings(){
	//register our settings
	register_setting( 'wtlsw_options', 'wtlswPhone' );
	register_setting( 'wtlsw_options', 'wtlswZalo' );
	register_setting( 'wtlsw_options', 'wtl_swcolor' );
}

// Add Page into menu
function zcswr_phone_callsw_setting_page() {
	//Get the active tab from the $_GET param
	$default_tab = null;
    $url_setting = "?page=phone-call-zalo&tab=settings";
    $url_report = "?page=phone-call-zalo&tab=report";
	$tab = sanitize_key(isset($_GET['tab']) ? $_GET['tab'] : $default_tab);
	?>
		<div class="wrap">
			<h2><?php esc_html_e('Chat Zalo Button');?><span class="version"><?php esc_html_e('1.0.0');?></span></h2>
			<nav class="nav-tab-wrapper">
				<a href="<?php echo esc_url($url_setting);?>" class="nav-tab <?php if($tab==='settings'):?>nav-tab-active<?php endif;?>"><?php esc_html_e('Zalo Contact');?></a>
				<a href="<?php echo esc_url($url_report);?>" class="nav-tab <?php if($tab==='report'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('Zalo Report');?></a>
			</nav>
			<div class="zalopage tab-content">
			<?php switch($tab) :
				case 'settings':
                    //option setting field chat & call zalo
					include ZCSWRP_PLUGIN_PATH . 'includes/swadmin/option-setting.php';
					break;
				case 'report':
                     //option report chat & call zalo
                     include ZCSWRP_PLUGIN_PATH . 'includes/swadmin/swreport.php';
					break;
				default:
					// default
                    include ZCSWRP_PLUGIN_PATH . 'includes/swadmin/option-setting.php';
					break;
				endswitch; 
			?>
				
			</div><!--end chat-zalo-->
			
		</div><!--end wrap-->
		<style>
			
		</style>
	<?php 
}

?>