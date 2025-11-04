<?php
/**
 * Plugin Name: Custom Category Post Order
 * Plugin URI: https://scriptut.com/wordpress/custom-category-post-order/
 * Description: Arrange posts by category or custom post type using a simple drag-and-drop interface. Supports ordering for home page, taxonomies, and custom post types.
 * Version: 2.1
 * Author: Faaiq Ahmed
 * Author URI: mailto:nfaaiq@gmail.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: custom-category-post-order
 * Domain Path: /languages
 *
 * @package CustomCategoryPostOrder
 */

global $ccpo_db_version;
$ccpo_db_version = "2.5";

class customcategorypostorder
{
	function __construct()
	{
		add_action('admin_enqueue_scripts', array($this, 'ccpo_enqueue_admin_scripts'));
		add_action('admin_menu', array($this, 'ccpo_menu'));
		add_action('wp_head', array($this, 'add_slideshowjs'));
		add_action('init', array($this, 'process_post'));
		add_action('wp_ajax_build_order', array($this, 'build_order_callback'));
		add_action('save_post', array($this, 'ccpo_update_post_order'));
		add_action('admin_head', array($this, 'admin_load_js'));
		add_action('wp_ajax_user_ordering', array($this, 'user_ordering'));
		add_action('wp_ajax_ccpo_get_taxonomies', [$this, 'ajax_get_taxonomies']);
		add_action('wp_ajax_ccpo_get_terms', [$this, 'ajax_get_terms']);
		add_action('wp_ajax_ccpo_load_posts', [$this, 'ajax_load_posts']);

		add_action( 'wp_ajax_ccpo_get_meta_keys', [ $this, 'ajax_ccpo_get_meta_keys' ] );

		add_action('pre_get_posts', [$this, 'ccpo_custom_taxonomy_ordering']);
		add_action('pre_get_posts', [$this, 'ccpo_custom_category_ordering']);
		
		
		add_action( 'plugins_loaded', [$this,'ccpo_load_textdomain'] );

		register_activation_hook(__FILE__, array($this, 'ccpo_install'));
		register_deactivation_hook(__FILE__, array($this, 'ccpo_uninstall'));
	}

	function ccpo_load_textdomain() {
 		load_plugin_textdomain( 'custom-category-post-order', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
	}
	
	
	function ccpo_custom_taxonomy_ordering($query) {
		if (is_admin() || !$query->is_main_query() || is_category()) {
			return;
		}
		
		// Check if this is a taxonomy archive for your custom taxonomy
		$term = get_queried_object();
		if($term) {
			$term_id = $term->term_id;
			
			
			$option_name = 'ccpo_category_ordering_' . sanitize_key($term_id);
			$ordering_enabled = get_option($option_name) ? true : false;

			if (!$ordering_enabled) {
				return;
			}

			$query->set('ccpo_custom_category_id', $term_id);
			$query->set('orderby', 'none');

			// Attach clause filter
			add_filter('posts_clauses', array($this,'ccpo_custom_posts_clauses_filter')	, 10, 2);
		}
	
	}

	function ccpo_custom_posts_clauses_filter($clauses, $query) {
		global $wpdb;

		$term_id = $query->get('ccpo_custom_category_id');

		if (!$term_id) {
			return $clauses;
		}

		$ccpo_table = $wpdb->prefix . 'ccpo_post_order_rel';

		$clauses['join'] .= "
			LEFT JOIN $ccpo_table AS ccpo_rel 
			ON {$wpdb->posts}.ID = ccpo_rel.post_id 
			AND ccpo_rel.category_id = " . intval($term_id);
			//. " AND ccpo_rel.incl = 1";

		$clauses['orderby'] = "ccpo_rel.weight ASC";

		return $clauses;
	}


	function ccpo_custom_category_ordering($query) {
		if (is_admin() || !$query->is_main_query() || !is_category()) {
			return;
		}

		$category = get_queried_object();
		$term_id = $category->term_id;
		if($term_id) {
		
			$option_name = 'ccpo_category_ordering_' . sanitize_key($term_id);
			
			$ordering_enabled = get_option($option_name) ? true : false;
			
			

			if (!$ordering_enabled) {
				return; // Custom ordering not enabled for this category
			}
			

			// Store category ID to use later in SQL filters
			$query->set('ccpo_custom_category_id', $term_id);
			
			// Set orderby to none to avoid default ordering
			$query->set('orderby', 'none');

			// Add custom SQL clauses
			add_filter('posts_clauses', array($this,'ccpo_posts_clauses_filter'), 10, 2);
		}
	}

	function ccpo_posts_clauses_filter($clauses, $query) {
		global $wpdb;

		$category_id = $query->get('ccpo_custom_category_id');

		if (!$category_id) {
			return $clauses;
		}

		$ccpo_table = $wpdb->prefix . 'ccpo_post_order_rel';

		// Join the custom table
		$clauses['join'] .= " 
			LEFT JOIN $ccpo_table AS ccpo_rel 
			ON {$wpdb->posts}.ID = ccpo_rel.post_id 
			AND ccpo_rel.category_id = " . intval($category_id);
			//. " AND ccpo_rel.incl = 1";

		// Order by weight
		$clauses['orderby'] = "ccpo_rel.weight ASC";

		return $clauses;
	}


	
	
	public function ajax_get_terms() {
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ccpo_get_terms')) {
			wp_send_json_error('Invalid nonce');
		}
		if (!current_user_can( 'ccpo_sort_posts' )) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'custom-category-post-order' ) );
		}
		$taxonomy = sanitize_text_field($_POST['taxonomy'] ?? '');

		if ($taxonomy !== 'home' && (empty($taxonomy) || !taxonomy_exists($taxonomy))) {
			wp_send_json_error('Invalid taxonomy');
		}


		$terms = get_terms([
			'taxonomy' => $taxonomy,
			'hide_empty' => false,
		]);

		if (is_wp_error($terms)) {
			wp_send_json_error('Failed to get terms');
		}

		$data = [];
		foreach ($terms as $term) {
			$data[] = [
				'term_id' => $term->term_id,
				'name'    => $term->name
			];
		}
		
		wp_send_json_success($data);
	}

	public function ajax_load_posts() {
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ccpo_load_posts')) {
			wp_send_json_error('Invalid nonce');
		}

		if (!current_user_can( 'ccpo_sort_posts' )) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'custom-category-post-order' ) );
		}

		$post_type = sanitize_text_field($_POST['post_type'] ?? '');
		$taxonomy  = sanitize_text_field($_POST['taxonomy'] ?? '');
		$term_id   = sanitize_text_field($_POST['term'] ?? '');

		// Special case: Home page

		$option_name = 'ccpo_category_ordering_' . sanitize_key($term_id);
	
		
		$ordering_enabled = get_option($option_name) ? true : false;

		if (!$post_type || (!$is_home && (!$taxonomy || !$term_id))) {
			wp_send_json_error('Missing data');
		}

		global $wpdb;

		// Load custom order

		$order_result = $wpdb->get_results($wpdb->prepare(
			"SELECT post_id, incl FROM {$wpdb->prefix}ccpo_post_order_rel WHERE category_id = %s ORDER BY weight ASC",
			$term_id
		));


		

		$ordered_ids = wp_list_pluck($order_result, 'post_id');
		$order_map = [];
		foreach ($order_result as $row) {
			$order_map[$row->post_id] = $row;
		}

		ob_start();
		echo '<ul id="sortable" class="sortableul">';

		// Query 1: Ordered posts
		if (!empty($ordered_ids)) {
			$ordered_query_args = [
				'post_type'      => $post_type,
				'post__in'       => $ordered_ids,
				'orderby'        => 'post__in',
				'posts_per_page' => -1,
				'post_status'    => 'publish'
			];

			$ordered_query = new WP_Query($ordered_query_args);

			foreach ($ordered_query->posts as $post) {
				$post_id = esc_attr($post->ID);
				$post_title = esc_html($post->post_title);
				$row = $order_map[$post->ID];

				echo '<li class="sortable" id="' . $post_id . '" rel="' . $post_id . '" post_title="' . esc_attr($post_title) . '">';
				echo '<div id="post" class="drag_post">' . $post_title . '</div>';
				echo '</li>';
			}
		}

		// Query 2: Remaining posts not in order
		$remaining_query_args = [
			'post_type'      => $post_type,
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'post__not_in'   => $ordered_ids,
			'orderby'        => 'title',
			'order'          => 'ASC'
		];

		// Only apply taxonomy filter if NOT home
		
		$remaining_query_args['tax_query'] = [[
			'taxonomy' => $taxonomy,
			'field'    => 'term_id',
			'terms'    => [$term_id],
		]];
		
		
		$remaining_query = new WP_Query($remaining_query_args);

		foreach ($remaining_query->posts as $post) {
			$post_id = esc_attr($post->ID);
			$post_title = esc_html($post->post_title);

			echo '<li class="sortable" id="' . $post_id . '" rel="' . $post_id . '" post_title="' . esc_attr($post_title) . '">';
			echo '<div id="post" class="drag_post">' . $post_title . '<div class="ar_link"></div></div>';
			echo '</li>';
		}

		echo '</ul>';
		$html = ob_get_clean();

		wp_send_json_success(['html' => $html, 'ordering_enabled' => $ordering_enabled]);
	}

	public function ajax_get_taxonomies() {
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ccpo_get_taxonomies')) {
			wp_send_json_error('Invalid nonce');
		}
		if (!current_user_can( 'ccpo_sort_posts' )) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'custom-category-post-order' ) );
		}

		$post_type = sanitize_text_field($_POST['post_type'] ?? '');

		if ($post_type !== 'home' && (empty($post_type) || !post_type_exists($post_type))) {
			wp_send_json_error('Invalid post type');
		}

		$data = [];

		// For default 'post' type, only return the 'category' taxonomy
		if ($post_type === 'post') {
			$taxonomy = get_taxonomy('category');
			if ($taxonomy && $taxonomy->public) {
				$data[] = [
					'name'  => $taxonomy->name,
					'label' => $taxonomy->labels->singular_name
				];
			}
		} else {
			$taxonomies = get_object_taxonomies($post_type, 'objects');
			foreach ($taxonomies as $taxonomy) {
				if ($taxonomy->public) {
					$data[] = [
						'name'  => $taxonomy->name,
						'label' => $taxonomy->labels->singular_name
					];
				}
			}
		}

		wp_send_json_success($data);
	}

	function ccpo_enqueue_admin_scripts($hook) {
		if ($hook !== 'toplevel_page_ccpo') return; // Adjust based on your actual page slug
		
		wp_enqueue_script(
			'ccpo-admin-script',
			plugin_dir_url(__FILE__) . 'js/admin.js',
			array('jquery'),
			'1.0',
			true
		);

		wp_enqueue_style(
			'ccpo-admin-style', // Handle
			plugin_dir_url(__FILE__) . 'css/custom-category-post-order', // Path
			array(), // Dependencies
			'1.0.0' // Version
		);
		
		wp_localize_script('ccpo-admin-script', 'ccpo_ajax_object', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonces'   => array(
				'user_ordering' => wp_create_nonce('ccpo_user_ordering_nonce'),
				'build_order'       => wp_create_nonce('ccpo_build_order_nonce'),
				'get_taxonomies' => wp_create_nonce('ccpo_get_taxonomies'),
				'get_terms'     => wp_create_nonce('ccpo_get_terms'),
				'load_posts'    => wp_create_nonce('ccpo_load_posts'),
				'ccpo_sort_nonce'    => wp_create_nonce('ccpo_sort_nonce'),
				'ccpo_get_meta_key_nonce'    => wp_create_nonce('ccpo_get_meta_key_nonce'),
				'ccpo_meta_key_search_apply'    => wp_create_nonce('ccpo_meta_key_search_apply'),
				
				// Add more as needed
			)
		));
	}

	function admin_load_js()
	{
		$url = plugins_url();
	}

	function ccpo_menu() {
		// Get the capability assigned to manage post ordering (defaults to 'administrator')
		
		
		// Always allow administrators to access full plugin menu
		if ( current_user_can( 'ccpo_sort_posts' ) ) {
			add_menu_page(
				__( 'Post Orders', 'custom-category-post-order' ),
				__( 'Post Order', 'custom-category-post-order' ),
				'ccpo_sort_posts',
				'ccpo',
				array($this, 'post_order_category'),
				plugin_dir_url(__FILE__) . 'assets/ic_order.png' // Path to your icon
			);

			add_submenu_page(
				'ccpo',
				__( 'Order Permission', 'custom-category-post-order' ),
				__( 'Permission', 'custom-category-post-order' ),
				'administrator',
				'subccpo',
				array($this, 'ccpo_admin_right')
			);
		}
	}


	function ccpo_admin_right() {
		global $wp_roles;

		// ✅ Only users with permission to manage the plugin
		if ( ! current_user_can( 'ccpo_sort_posts' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'custom-category-post-order' ) );
		}

		$message = '';

		// ✅ Handle form submission
		if (
			$_SERVER['REQUEST_METHOD'] === 'POST' &&
			isset( $_POST['_wpnonce'] ) &&
			wp_verify_nonce( $_POST['_wpnonce'], 'update-options' )
		) {
			$submitted_roles = isset( $_POST['roles'] ) ? (array) $_POST['roles'] : [];
			$submitted_roles = array_map( 'sanitize_text_field', $submitted_roles );

			$all_roles = $wp_roles->get_names();
			$valid_roles = array_keys( $all_roles );

			$selected_roles = array_intersect( $submitted_roles, $valid_roles );

			// Update capability for selected roles
			foreach ( $valid_roles as $role_key ) {
				$role_obj = get_role( $role_key );
				if ( ! $role_obj ) {
					continue;
				}

				if ( in_array( $role_key, $selected_roles, true ) ) {
					$role_obj->add_cap( 'ccpo_sort_posts' );
				} else {
					$role_obj->remove_cap( 'ccpo_sort_posts' );
				}
			}

			// Save selected roles in an option (optional)
			update_option( 'ccpo_order_managers', $selected_roles );

			$message = esc_html__( 'Roles updated successfully.', 'custom-category-post-order' );
		}

		$current_roles = (array) get_option( 'ccpo_order_managers', [ 'administrator' ] );
		$roles         = $wp_roles->get_names();

		// ✅ Build checkboxes
		$checkboxes = '';
		foreach ( $roles as $key => $label ) {
			$checked    = in_array( $key, $current_roles, true ) ? 'checked' : '';
			$checkboxes .= '<label><input type="checkbox" name="roles[]" value="' . esc_attr( $key ) . '" ' . $checked . '> ' . esc_html( $label ) . '</label><br>';
		}

		// ✅ Output UI
		echo '<div class="wrap">';
		echo '<h2>' . esc_html__( 'Who can arrange the post', 'custom-category-post-order' ) . '</h2>';

		if ( ! empty( $message ) ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
		}

		echo '<form method="post">';
		wp_nonce_field( 'update-options' );

		echo '<table class="form-table">
			<tr valign="top">
				<th scope="row">' . esc_html__( 'Select Roles:', 'custom-category-post-order' ) . '</th>
				<td>' . $checkboxes . '</td>
			</tr>
			<tr valign="top">
				<td colspan="2">
					<input type="submit" class="button-primary" value="' . esc_attr__( 'Submit', 'custom-category-post-order' ) . '" />
				</td>
			</tr>
		</table>';

		echo '</form></div>';
	}

	function ccpo_get_post_type() {
		$cache_key = 'ccpo_post_types_with_taxonomies';
		$post_types = wp_cache_get($cache_key, 'custom-category-post-order');

		if (false === $post_types) {
			// Get all public post types
			$all_post_types = get_post_types(['public' => true], 'objects');

			$post_types = [];

			foreach ($all_post_types as $post_type => $post_type_obj) {
				// Skip media/revision etc.
				if (in_array($post_type, ['attachment', 'revision', 'nav_menu_item'])) {
					continue;
				}

				$taxonomies = get_object_taxonomies($post_type);
				if (!empty($taxonomies)) {
					$post_types[$post_type] = $post_type_obj->labels->singular_name;
				}
			}

			wp_cache_set($cache_key, $post_types, 'custom-category-post-order', 300);
		}

		return $post_types;
	}



	function add_slideshowjs()
	{

	}

	function check_order_table(int $post_id, string $category_id): int {
		global $wpdb;

		$table = $wpdb->prefix . 'ccpo_post_order_rel';

		return (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $table WHERE category_id = %s AND post_id = %d",
				$category_id,
				$post_id
			)
		);
	}



	function process_post() {
		// Enqueue jQuery UI Sortable (no need to define the path manually)
		    wp_localize_script('custom-post-order', 'ccpo_ajax_object', array(
        	    'ajax_url' => admin_url('admin-ajax.php'),
            	'nonce'    => wp_create_nonce('ccpo_rmppost_nonce')
        	));
    
			wp_enqueue_script('jquery-ui-sortable');
	}
		



	function build_order_callback() {
		global $wpdb;
		if ( ! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'ccpo_build_order_nonce') ) {
			wp_send_json_error('Invalid nonce');
		}
		if (!current_user_can( 'ccpo_sort_posts' )) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'custom-category-post-order' ) );
		}

		if (!isset($_POST['order']) || !isset($_POST['category'])) {
			wp_send_json_error('Missing parameters');
		}

		$order = array_map('intval', explode(",", $_POST['order']));
		$category = sanitize_text_field($_POST['category']);
		$table = $wpdb->prefix . "ccpo_post_order_rel";

		$total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE category_id = %s", $category));
		
		if ($total == 0) {
			$values = [];
			$weight = 0;

			foreach ($order as $post_id) {
				if ($post_id > 0) {
					$weight++;
					$values[] = $wpdb->prepare("(%s, %d, %d)", $category, $post_id, $weight);
				}
			}
			
			if (!empty($values)) {
				$sql = "INSERT INTO $table (category_id, post_id, weight) VALUES " . implode(',', $values);
				$wpdb->query($sql);
			}
		} else {
			$weight = 0;
			foreach ($order as $post_id) {
				$weight++;
				if ($post_id > 0) {
					$wpdb->query($wpdb->prepare(
						"UPDATE $table SET weight = %d WHERE post_id = %d AND category_id = %s",
						$weight, $post_id, $category
					));
					$exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE post_id = %d AND category_id = %s", $post_id, $category));

					if ($exists) {
						// UPDATE if found
						$wpdb->query(
							$wpdb->prepare(
								"UPDATE $table SET weight = %d  WHERE post_id = %d AND category_id = %s",
								$weight,
								$post_id,
								$category
							)
						);
					} else {
						// INSERT if not found
						$wpdb->query(
							$wpdb->prepare(
								"INSERT INTO $table (post_id, category_id, weight, incl) VALUES (%d, %s, %d, %d)",
								$post_id,
								$category,
								$weight,
								1
							)
						);
					}

				}
			}
		}

		wp_send_json_success('Order updated');
	}


	

	function user_ordering() {
		global $wpdb;

		// Verify nonce (security field must be named 'nonce' in JS)
		check_ajax_referer('ccpo_user_ordering_nonce', 'nonce');
		if (!current_user_can( 'ccpo_sort_posts' )) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'custom-category-post-order' ) );
		}
		// Allow category to be either string or integer (e.g., post type or category ID)
		$category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
		$checked = isset($_POST['checked']) ? trim($_POST['checked']) : '';

		// Only proceed if category is not empty
		if (!empty($category)) {
			$option_key = "ccpo_category_ordering_" . sanitize_key($category);
			if ($checked === 'true') {
				update_option($option_key, 'checked');
			} else {
				update_option($option_key, '');
			}
		}

		wp_send_json_success();
	}


	function ccpo_update_post_order($post_id) {
		global $wpdb;

		if (!current_user_can( 'ccpo_sort_posts' )) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'custom-category-post-order' ) );
		}

		if (!wp_is_post_revision($post_id)) {
			$cats = get_the_category($post_id);
			foreach ($cats as $cat) {
				$cat_id = intval($cat->term_id);
				$total = $wpdb->get_var($wpdb->prepare(
					"SELECT COUNT(*) FROM {$wpdb->prefix}ccpo_post_order_rel WHERE category_id = %d AND post_id = %d",
					$cat_id, $post_id
				));

				if ($total == 0 && $post_id > 0) {
					$wpdb->query($wpdb->prepare(
						"INSERT INTO {$wpdb->prefix}ccpo_post_order_rel (category_id, post_id) VALUES (%d, %d)",
						$cat_id, $post_id
					));
				}
			}
		}
	}




	function ccpo_install() {
		global $wpdb;
		global $ccpo_db_version;

		$table_name = $wpdb->prefix . "ccpo_post_order_rel";

		$sql = "CREATE TABLE $table_name (
			id INT(11) NOT NULL AUTO_INCREMENT,
			category_id INT(11) NOT NULL,
			post_id INT(11) NOT NULL,
			incl TINYINT(1) NOT NULL DEFAULT 1,
			weight INT(11) NOT NULL DEFAULT 0,
			PRIMARY KEY (id),
			INDEX category_idx (category_id),
			INDEX post_idx (post_id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta($sql);
		$this->ccpo_add_capability();
		add_option('ccpo_db_version', $ccpo_db_version);
	}


	function ccpo_add_capability() {
		// Add capability to Administrator
		$admin = get_role( 'administrator' );
		if ( $admin && !$admin->has_cap( 'ccpo_sort_posts' ) ) {
			$admin->add_cap( 'ccpo_sort_posts' );
		}

		// Optionally add to Editor too
		$editor = get_role( 'editor' );
		if ( $editor && !$editor->has_cap( 'ccpo_sort_posts' ) ) {
			$editor->add_cap( 'ccpo_sort_posts' );
		}
	}


	function ccpo_remove_capability() {
		$admin = get_role( 'administrator' );
		if ( $admin ) {
			$admin->remove_cap( 'ccpo_sort_posts' );
		}

		$editor = get_role( 'editor' );
		if ( $editor ) {
			$editor->remove_cap( 'ccpo_sort_posts' );
		}
	}

	function ccpo_uninstall() {
		global $wpdb;

		$table_name = $wpdb->prefix . "ccpo_post_order_rel";

		$this->ccpo_remove_capability();

		// Drop the custom table
		$wpdb->query("DROP TABLE IF EXISTS $table_name");

		// Delete plugin-specific options
		delete_option('ccpo_db_version');

		// Delete all options starting with 'ccpo_'
		$wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE 'ccpo_%'");
	}


		//new funciton 

	public function post_order_category() {
		if (!current_user_can( 'ccpo_sort_posts' )) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'custom-category-post-order' ) );
		}
		$term = $this->sanitize_category_input();
		// $categories = $this->get_all_categories();
		// $post_types = $this->ccpo_get_post_type();
		
		$post_types_options = $this->generate_post_type_options();

		// $taxonomy_options = $this->generate_category_posttype_options($categories, $category);

		$order_data = $this->get_post_order_data($term);
		
		$checked = get_option("ccpo_category_ordering_" . $term);

		
		echo $this->render_admin_page($post_types_options,  $term, $order_data, $checked);
	}

	private function render_admin_page($post_types_options, $term, $order_data, $checked) {
		ob_start();
		include plugin_dir_path(__FILE__) . 'admin-post-order-page.php';
		return ob_get_clean();
	}

	private function sanitize_category_input() {
		return isset($_POST['term']) ? sanitize_text_field($_POST['term']) : '';
	}

	private function get_all_categories() {
		return get_categories([
			'type' => 'post',
			'orderby' => 'name',
			'order' => 'ASC',
			'hide_empty' => true,
			'exclude' => [0],
			'hierarchical' => true,
			'taxonomy' => 'category',
			'pad_counts' => true
		]);
	}

	
	private function generate_category_posttype_options($categories, $selected_category) {
		$options = ['<option value="" selected>' . esc_html__('Select Category / Post Type', 'custom-category-post-order') . '</option>'];

		// Add category options
		foreach ($categories as $cat) {
			$options[] = sprintf(
				'<option value="%s" %s>%s</option>',
				esc_attr($cat->term_id),
				selected($cat->term_id, $selected_category, false),
				esc_html($cat->name)
			);
		}

		// Get all public post types with taxonomies (excluding built-in ones if needed)
		$all_post_types = get_post_types(['public' => true], 'objects');
		foreach ($all_post_types as $post_type) {
			// Skip posts and pages if you don't want them
			if (in_array($post_type->name, ['post', 'page'])) {
				continue;
			}

			$taxonomies = get_object_taxonomies($post_type->name);
			if (!empty($taxonomies)) {
				$options[] = sprintf(
					'<option value="%s" %s>%s</option>',
					esc_attr($post_type->name),
					selected($post_type->name, $selected_category, false),
					esc_html($post_type->label)
				);
			}
		}

		return $options;
	}

	private function generate_post_type_options($selected_post_type = '') {
		$options = ['<option value="" selected>' . esc_html__('Select Post Type', 'custom-category-post-order') . '</option>'];

		// Get all public post types

		$options[] = sprintf(
			'<option value="home" disabled  %s>%s</option>',
			selected('home', $selected_post_type, false),
			esc_html__('Home Page (Pro)', 'custom-category-post-order')
		);
		
		$all_post_types = get_post_types(['public' => true], 'objects');

		foreach ($all_post_types as $post_type) {
			// Skip default system types
			if (in_array($post_type->name, ['attachment', 'revision', 'nav_menu_item'])) {
				continue;
			}

			// Get taxonomies for the post type
			$taxonomies = get_object_taxonomies($post_type->name);

			// Skip if no taxonomies
			if (empty($taxonomies)) {
				continue;
			}

			// Check if at least one taxonomy has terms
			$has_terms = false;
			foreach ($taxonomies as $taxonomy) {
				$terms = get_terms([
					'taxonomy' => $taxonomy,
					'hide_empty' => false,
					'number' => 1, // We only need to check if *any* term exists
				]);

				if (!empty($terms) && !is_wp_error($terms)) {
					$has_terms = true;
					break;
				}
			}

			if (!$has_terms) {
				continue; // Skip this post type if no taxonomy has any term
			}

			// Add to select options
			$options[] = sprintf(
				'<option value="%s" %s>%s</option>',
				esc_attr($post_type->name),
				selected($post_type->name, $selected_post_type, false),
				esc_html($post_type->labels->singular_name)
			);
		}

		return $options;
	}


	private function get_post_order_data($term) {
		global $wpdb;

		if (empty($term)) {
			return [
				'order_result' => [],
				'order_result_incl' => [],
				'posts' => []
			];
		}

		$is_numeric_cat = ctype_digit($term);
		$term_id = $is_numeric_cat ? absint($term) : sanitize_key($term);

		// Validate category or post type
		if ($is_numeric_cat) {
			$term = get_term($term_id, 'category');
			if (!$term || is_wp_error($term)) {
				return [
					'order_result' => [],
					'order_result_incl' => [],
					'posts' => []
				];
			}
		} else {
			// Validate post type
			$post_types = get_post_types([], 'names');
			if (!in_array($term_id, $post_types)) {
				return [
					'order_result' => [],
					'order_result_incl' => [],
					'posts' => []
				];
			}
		}

		$table = $wpdb->prefix . 'ccpo_post_order_rel';

		$sql = $wpdb->prepare(
			"SELECT * FROM $table WHERE category_id = %s ORDER BY weight",
			$term_id
		);
		$order_result = $wpdb->get_results($sql);

		$order_result_incl = [];
		foreach ($order_result as $row) {
			$order_result_incl[$row->post_id] = $row->incl;
		}

		$args = [
			'posts_per_page' => -1,
			'orderby' => 'title',
			'post_status' => 'publish',
			'order' => 'DESC'
		];

		if ($is_numeric_cat) {
			$args['category__in'] = [$term_id];
			$args['post_type'] = 'post';
		} else {
			$args['post_type'] = $term_id;
		}

		$query = new WP_Query($args);
		$posts = $query->posts;

		$temp_order = [];
		foreach ($posts as $post) {
			$temp_order[$post->ID] = $post;
		}

		return [
			'order_result' => $order_result,
			'order_result_incl' => $order_result_incl,
			'posts' => $temp_order
		];
	}

	public function ajax_ccpo_get_meta_keys() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ccpo_get_meta_key_nonce' ) ) {
			wp_send_json_error( 'Bad nonce' );
		}
	
		if (!current_user_can( 'ccpo_sort_posts' )) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'custom-category-post-order' ) );
		}

		$post_type = sanitize_text_field( $_POST['post_type'] ?? 'post' );

		global $wpdb;
		// Pull distinct keys (limit to 100 to keep it light)
		$keys = $wpdb->get_col( $wpdb->prepare(
			"SELECT DISTINCT pm.meta_key
			FROM {$wpdb->postmeta} pm
			JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			WHERE p.post_type = %s
			AND pm.meta_key NOT LIKE '\_%' -- exclude internal meta keys
			ORDER BY pm.meta_key
			LIMIT 100",
			$post_type
		));

		wp_send_json_success( [ 'keys' => $keys ] );
	}

}

new customcategorypostorder();





///new 
