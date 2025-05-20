<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
    <form method="post" action="options.php" class="cnb-container">
        <?php settings_fields('wtlsw_options'); ?>
        <?php
            $wtlswPhone =  sanitize_text_field(get_option('wtlswPhone') );
            $wtlswZalo =   sanitize_text_field(get_option('wtlswZalo') );
            $wtl_swcolor =   sanitize_text_field(get_option('wtl_swcolor') );
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Số điện thoại:'); ?></th>
                <td><input type="text" name="wtlswPhone" value="<?php echo esc_html($wtlswPhone);?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Chat Zalo:'); ?></th>
                <td><input type="text" name="wtlswZalo" value="<?php echo esc_html($wtlswPhone); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Background Color:'); ?></th>
                <td><input type="text" name="wtl_swcolor" class="cpa-color-picker" value="<?php esc_html($wtl_swcolor); ?>" data-default-color="<?php echo esc_html('#0088cc');?> " /></td>
            </tr>
        </table>
        <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
    </form>
    <?php
?>
