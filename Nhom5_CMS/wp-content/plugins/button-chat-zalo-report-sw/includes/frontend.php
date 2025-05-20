<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (!function_exists('zcswr_pcnButton') ) {
	function zcswr_pcnButton() {
		?>
			<div class="fix_chatZalo">
				<?php
					$zalo_phone = get_option('wtlswPhone');
					$zalo_url = esc_url( "https://zalo.me/" . $zalo_phone );
				 ?>
				<a class="btn_chatZalo" target="_blank" href="<?php echo esc_url($zalo_url);?>">
					<span><?php esc_html_e('Chat Zalo'); ?>  </span>
				</a>
			</div><!--end fix-chatZalo-->
			
			
			<div class="fix_tel">
				<div class="ring-alo-phone ring-alo-green ring-alo-show">
					<div class="ring-alo-ph-circle"></div>
					<div class="ring-alo-ph-circle-fill"></div>
					<div class="ring-alo-ph-img-circle">
						<a href="tel:<?php echo esc_html(get_option('wtlswPhone'));?>">
							<?php $img_zalo = ZCSWRP_PLUGIN_URL . '/assets/phone-ring.png' ?>
							<img class="" src="<?php echo esc_url($img_zalo); ?>" />
						</a>
					</div>
					
				</div>
				<div class="tel">
					<p class="fone"><?php echo esc_html(get_option('wtlswPhone'));?></p>
					 
				</div>
			</div><!--end fix_tel-->
	
			<!-- css admin All in one -->
			<style type="text/css">
				.btn_chatZalo {
					background-color: <?php echo esc_html(get_option('wtl_swcolor'));?> !important;
				}
				.ring-alo-phone.ring-alo-green .ring-alo-ph-img-circle {
					background-color: <?php echo esc_html(get_option('wtl_swcolor'));?> !important;
				}
			</style>
			
		<?php
	}
	// Display Button Zalo frontend web
	add_action('wp_footer','zcswr_pcnButton');
} 



?>
