	




<?php
/**
 * Flatsome functions and definitions
 *
 * @package flatsome
 */

require get_template_directory() . '/inc/init.php';
update_option( 'flatsome_wup_supported_until', '01.01.2024' );
update_option( 'flatsome_wup_purchase_code', 'd9312df0-0cfc-4f64-9008-cac584881ac1' );
update_option( 'flatsome_wup_buyer', 'chowordpress.com' );

/**
 * Note: It's not recommended to add any custom code here. Please use a child theme so that your customizations aren't lost during updates.
 * Learn more here: http://codex.wordpress.org/Child_Themes
 */

// Hàm tạo trường meta _product_views và _product_likes nếu chưa có
function create_product_meta($post_id) {
    if ('product' === get_post_type($post_id)) {
        // Tạo trường meta cho lượt xem nếu chưa có
        if (!get_post_meta($post_id, '_product_views', true)) {
            update_post_meta($post_id, '_product_views', 0);
        }
        // Tạo trường meta cho lượt thích nếu chưa có
        if (!get_post_meta($post_id, '_product_likes', true)) {
            update_post_meta($post_id, '_product_likes', 0);
        }
    }
}
add_action('save_post', 'create_product_meta');

// Hàm tăng số lượt xem khi người dùng truy cập sản phẩm
function increment_product_views($post_id) {
    if (is_singular('product') && !is_admin()) {
        $views = get_post_meta($post_id, '_product_views', true);
        $views = $views ? $views + 1 : 1;
        update_post_meta($post_id, '_product_views', $views);
    }
}

// Hook vào sự kiện khi tải trang sản phẩm
add_action('wp', function () {
    if (is_singular('product')) {
        increment_product_views(get_the_ID());
    }
});

// Hàm xử lý AJAX tăng hoặc bỏ lượt thích
function toggle_product_likes_ajax() {
    if (isset($_POST['product_id'])) {
        $post_id = intval($_POST['product_id']);
        $liked = isset($_COOKIE['product_liked_' . $post_id]) ? true : false;

        if ($liked) {
            // Nếu người dùng đã thích, bỏ lượt thích
            $likes = get_post_meta($post_id, '_product_likes', true);
            $likes = $likes > 0 ? $likes - 1 : 0;
            update_post_meta($post_id, '_product_likes', $likes);

            // Xóa cookie để bỏ "like"
            setcookie('product_liked_' . $post_id, '', time() - 3600, '/'); // Hết hạn cookie
            unset($_COOKIE['product_liked_' . $post_id]);
        } else {
            // Nếu người dùng chưa thích, tăng lượt thích
            $likes = get_post_meta($post_id, '_product_likes', true);
            $likes = $likes ? $likes + 1 : 1;
            update_post_meta($post_id, '_product_likes', $likes);

            // Đặt cookie để ghi nhận rằng người dùng đã "like" sản phẩm này
            setcookie('product_liked_' . $post_id, 'yes', time() + (365 * 24 * 60 * 60), '/'); // Hết hạn sau 1 năm
            $_COOKIE['product_liked_' . $post_id] = 'yes'; // Cập nhật giá trị của cookie trong session
        }

        // Trả về số lượt thích cập nhật
        echo $likes;
    }

    wp_die(); // Kết thúc và trả lại dữ liệu
}

// Hook vào WordPress để xử lý AJAX
add_action('wp_ajax_toggle_product_likes', 'toggle_product_likes_ajax');
add_action('wp_ajax_nopriv_toggle_product_likes', 'toggle_product_likes_ajax');

// Hàm hiển thị số lượt xem và lượt thích trên trang chi tiết sản phẩm
function display_product_views_and_likes() {
    global $post;
    $views = get_post_meta($post->ID, '_product_views', true);
    $likes = get_post_meta($post->ID, '_product_likes', true);
    
    // Nếu chưa có lượt xem, mặc định là 0
    if ($views === '') {
        $views = 0;
    }
    // Nếu chưa có lượt thích, mặc định là 0
    if ($likes === '') {
        $likes = 0;
    }

    // Hiển thị số lượt xem
    echo '<p class="views-info"><i class="fas fa-eye"></i><strong>Lượt xem:</strong> ' . (int)$views . '</p>';
    
    // Kiểm tra xem người dùng đã "like" sản phẩm chưa (dựa vào cookie)
    if (!isset($_COOKIE['product_liked_' . $post->ID])) {
        // Hiển thị nút like chỉ khi người dùng chưa like sản phẩm này
        echo '<button id="like-button" class="like-button" data-product-id="' . $post->ID . '">
                <i class="fas fa-thumbs-up"></i> ' . (int)$likes . ' Lượt thích
              </button>';
    } else {
        // Hiển thị trạng thái đã "like" và thay đổi màu sắc icon
        echo '<button id="like-button" class="like-button liked" data-product-id="' . $post->ID . '">
                <i class="fas fa-thumbs-up"></i> ' . (int)$likes . ' Lượt thích
              </button>';
    }
}

add_action('woocommerce_single_product_summary', 'display_product_views_and_likes', 15);

// Hàm hiển thị số lượt xem và lượt thích trên trang danh sách sản phẩm
function add_views_and_likes_to_loop_products() {
    global $product;
    $views = get_post_meta($product->get_id(), '_product_views', true);
    $likes = get_post_meta($product->get_id(), '_product_likes', true);
    
    if ($views === '') {
        $views = 0;
    }
    if ($likes === '') {
        $likes = 0;
    }
    
    echo '<p class="views-info"><i class="fas fa-eye"></i> ' . (int)$views . ' lượt xem | <i class="fas fa-thumbs-up"></i> ' . (int)$likes . ' lượt thích</p>';
}

// Hook vào WooCommerce để hiển thị số lượt xem và lượt thích trong vòng lặp sản phẩm
add_action('woocommerce_after_shop_loop_item', 'add_views_and_likes_to_loop_products', 15);

// JavaScript để xử lý AJAX khi nhấn nút Like
function enqueue_like_script() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#like-button').on('click', function() {
                var button = $(this);
                var productId = button.data('product-id');
                
                // Gửi yêu cầu AJAX để tăng hoặc bỏ lượt thích
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'toggle_product_likes',
                        product_id: productId
                    },
                    success: function(response) {
                        // Cập nhật số lượt thích sau khi tăng hoặc giảm
                        button.html('<i class="fas fa-thumbs-up"></i> ' + response + ' Lượt thích');

                        // Thêm class 'liked' để đổi màu icon thành xanh khi đã like
                        button.toggleClass('liked');

                        // Cập nhật trạng thái "liked" vào cookie
                        if (button.hasClass('liked')) {
                            document.cookie = 'product_liked_' + productId + '=yes; path=/;';
                        } else {
                            document.cookie = 'product_liked_' + productId + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/;';
                        }
                    }
                });
            });
        });
    </script>
    <?php
}

add_action('wp_footer', 'enqueue_like_script');

// CSS để đổi màu icon khi người dùng nhấn Like
function enqueue_like_styles() {
    ?>
    <style type="text/css">
        /* Đổi màu icon like khi người dùng đã like */
        .like-button.liked i {
            color: #1877F2;; /* Màu xanh */
        }

        /* Đảm bảo nút like trở lại trạng thái ban đầu nếu chưa like */
        .like-button i {
            color: #ccc; /* Màu xám mặc định */
        }
    </style>
    <?php
}

add_action('wp_head', 'enqueue_like_styles');
?>
