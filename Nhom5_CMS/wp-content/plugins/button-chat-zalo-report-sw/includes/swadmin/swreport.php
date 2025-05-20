<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
    <h3><?php esc_html_e('Thống kê click zalo'); ?> </h3>
    <table class="reportzalo">
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'chatzalosw';
        //$myrows = $wpdb->get_results("select * from $table_name");
        $items_per_page = 15;
        $page = sanitize_key(isset($_GET['cpage']) ? abs((int) $_GET['cpage']) : 1);
        $offset = ($page * $items_per_page) - $items_per_page;

        $query = 'SELECT * FROM ' . $table_name;

        $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
        $total = $wpdb->get_var($total_query);
        /* tong click */
        $sqlc = "select sum(click) from $table_name";
        $totalc = $wpdb->get_var($sqlc);
        ?>
        <caption style="text-align:right"><span><?php esc_html_e('Tổng Click:');?> <?php esc_html_e($totalc); ?></span> </caption>
        <thead>
            <tr>
                <th><?php esc_html_e('#');?></th>
                <th><?php esc_html_e('Click');?> </th>
                <th> <?php esc_html_e('Date');?> </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $results = $wpdb->get_results($query . ' ORDER BY clickdate DESC LIMIT ' . $offset . ', ' . $items_per_page, OBJECT);
            foreach ($results as $key => $row) :
            ?>
                <tr>
                    <td> <?php echo esc_html($key + 1); ?> </td>
                    <td> <?php echo esc_html($row->click); ?> </td>
                    <td> <?php echo esc_html(date("d/m/Y", strtotime($row->clickdate) ) ) ; ?> </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="zalo-pagi">
        <?php
            echo paginate_links(array(
                'base' => add_query_arg('cpage', '%#%'),
                'format' => '',
                'prev_text' => __('&laquo;'),
                'next_text' => __('&raquo;'),
                'total' => ceil($total / $items_per_page),
                'current' => $page
            ));
        ?>
    </div>

<?php
?>
