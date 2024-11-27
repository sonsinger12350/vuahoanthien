<?php
	if (!class_exists('WP_List_Table')) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
	}

	class User_Gifts_List_Table extends WP_List_Table {
		public function __construct() {
			parent::__construct([
				'singular' => 'quà tặng',
				'plural'   => 'quà tặng',
				'ajax'     => false
			]);
		}

		public function get_columns() {
			$columns = [
				'user_id'       => 'User',
				'gift'          => 'Quà tặng',
				'img'           => 'Hình ảnh',
				'date_assigned' => 'Ngày nhận',
				'actions'       => 'Hành động'
			];
			return $columns;
		}

		protected function column_cb($item) {
			return sprintf(
				'<input type="checkbox" name="gift[]" value="%s" />',
				$item['gift_id']
			);
		}

		public function prepare_items() {
			$columns = $this->get_columns();
			$hidden = [];
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = [$columns, $hidden, $sortable];

			// Lấy dữ liệu
			$data = $this->get_gifts_data();

			$per_page = 10;
			$current_page = $this->get_pagenum();
			$total_items = count($data);

			$this->set_pagination_args([
				'total_items' => $total_items,
				'per_page'    => $per_page
			]);

			$data = array_slice($data, (($current_page-1)*$per_page), $per_page);

			$this->items = $data;
		}

		public function get_sortable_columns() {
			return [];
		}

		public function column_default($item, $column_name) {
			switch ($column_name) {
				case 'gift':
					return $item['gift_name'];
				case 'img':
					return '<img src="'.wp_get_attachment_url($item['img']).'" width="200px">';
				case 'user_id':
					return $item['display_name'].'('.$item['user_nicename'].')';
				case 'date_assigned':
					return $item[$column_name];
				case 'actions':
					return $this->column_actions($item);
				default:
					return print_r($item, true);
			}
		}

		private function get_gifts_data() {
			global $wpdb;

			$sql = "SELECT g.*,p.post_excerpt gift_name,
				u.display_name, 
				u.user_nicename,
				(SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE g.gift_id = post_id AND meta_key = '_thumbnail_id') img
				FROM {$wpdb->prefix}user_gifts g
				JOIN {$wpdb->prefix}users u ON g.user_id = u.ID
				JOIN {$wpdb->prefix}posts p ON g.gift_id = p.ID
				WHERE status = 0
				ORDER BY g.user_id, g.type
			";

			$results = $wpdb->get_results($wpdb->prepare($sql), ARRAY_A);
			
			return $results;
		}

		public function column_actions($item) {
			$delete_nonce = wp_create_nonce('delete_gift');
			$delete_url = admin_url('admin.php?page=user-gifts-list&action=delete&id=' . $item['id'] . '&_wpnonce=' . $delete_nonce);
		
			return sprintf(
				'<a href="%s" class="delete-gift">Xóa</a>',
				esc_url($delete_url)
			);
		}

		public function extra_tablenav($which) {
			if ($which === 'top') {
				?>
				<div class="alignleft actions">
					<a href="<?php echo admin_url('admin-post.php?action=export_user_gifts'); ?>" class="button action"><?php _e('Export Data', 'textdomain'); ?></a>
				</div>
				<?php
			}
		}

		public function process_bulk_action() {
			if (isset($_GET['export_csv'])) {
				$this->export_csv();
				wp_redirect(remove_query_arg('export_csv'));
			}
		}

		public function export_csv() {
			if (!current_user_can('export')) {
				wp_die(__('Bạn không có quyền export dữ liệu.', 'textdomain'));
			}
	
			// Lấy dữ liệu cần export
			$data = $this->get_gifts_data();

			// Tạo file CSV
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="user_gifts.csv"');
	
			$output = fopen('php://output', 'w');
	
			fputcsv($output, ['User', 'Quà tặng', 'Chi tiết', 'Ngày nhận']);
	
			// Thêm dữ liệu vào CSV
			foreach ($data as $item) {
				fputcsv($output, [$item['display_name'].'('.$item['user_nicename'].')', $item['gift_code'], $item['gift_name'], $item['date_assigned']]);
			}
	
			fclose($output);
			exit;
		}
	}
?>