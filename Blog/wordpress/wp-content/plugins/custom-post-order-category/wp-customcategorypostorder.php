<?php
/**
 * @package Custom Post
 * @author Faaiq Ahmed
 * @version 1.5.9
 */
/*
Plugin Name: Custom Category Post Order
Description: Arrange your posts by category or post type with a simple drag n drop interface. 
Author: Faaiq Ahmed, Technical Architect PHP, nfaaiq@gmail.com
Version: 1.5.9
*/

global $ccpo_db_version;
$ccpo_db_version = "2.5";

class customcategorypostorder
{
	function __construct()
	{
		add_action('admin_menu', array($this, 'ccpo_menu'));
		add_action('wp_ajax_rmppost', array($this, 'rmppost'));
		add_action('wp_head', array($this, 'add_slideshowjs'));
		add_action('init', array($this, 'process_post'));
		add_action('wp_ajax_build_order', array($this, 'build_order_callback'));
		add_action('save_post', array($this, 'ccpo_update_post_order'));
		add_action('admin_head', array($this, 'admin_load_js'));

		add_action('wp_ajax_user_ordering', array($this, 'user_ordering'));
		if (substr(basename($_SERVER['REQUEST_URI']), 0, 8) != 'edit.php') {
			add_filter('posts_join', array($this, 'ccpo_query_join'), 1, 2);
			add_filter('posts_where', array($this, 'ccpo_query_where'));
			add_filter('posts_orderby', array($this, 'ccpo_query_orderby'));
		}
		register_activation_hook(__FILE__, array($this, 'ccpo_install'));
		register_deactivation_hook(__FILE__, array($this, 'ccpo_uninstall'));
	}

	function admin_load_js()
	{
		$url = plugins_url();
	}

	function ccpo_menu()
	{
		global $current_user, $wpdb;
		$role = $wpdb->prefix . 'capabilities';
		$current_user->role = array_keys($current_user->$role);
		$current_role = $current_user->role[0];
		$role = get_option('ccpo_order_manager', 'administrator');
		add_menu_page('Post Orders', 'Post Order', 'administrator', 'ccpo', array($this, 'post_order_category'));
		add_submenu_page("ccpo", "Order Permission", "Permission", 'administrator', "subccpo", array($this, "ccpo_admin_right"));

		if ($current_role != 'administrator') {
			add_submenu_page("ccpo", "Post Order", "Post Order", $role, "subccpo1", array($this, "post_order_category"));
		}
	}



	function ccpo_admin_right()
	{
		global $wp_roles;

		$role = trim($_POST['role']);

		$roles = $wp_roles->get_names();


		$tmp_roles = array();

		if (isset($_POST) and $role != "") {
			foreach ($roles as $key => $label) {
				$tmp_roles[] = $key;
			}
			//to check user posted valid role
			if (!in_array($role, $tmp_roles)) {
				die('invalide data');
			}

			update_option("ccpo_order_manager", $role);
			print "Role Updated";

		}
		$role = get_option('ccpo_order_manager', 'administrator');

		$select = "";
		foreach ($roles as $key => $label) {
			if ($key == $role) {
				$select .= '<option value="' . $key . '" selected>' . $label . '</option>';
			} else {
				$select .= '<option value="' . $key . '">' . $label . '</option>';
			}

		}

		print '<div class="wrap">
			<h2>Who Can Arrange Post</h2>
			<form method="post">';
		wp_nonce_field('update-options');

		print '<table class="form-table">
			<tr valign="top">
			<th scope="row">Select Role:</th>
			<td><select name="role" id="row">' . $select . '</select></td>
			</tr>';
		print '<tr valign="top"><td>
			<input type="submit" class="button" value="Submit" />
			</td></tr>
			</table>';
	}

	function ccpo_get_post_type()
	{
		global $wpdb;
		$results = $wpdb->get_results("select post_type from " . $wpdb->prefix . "posts where post_type not in ('attachment','revision') group by post_type ");
		$arr = array();
		for ($i = 0; $i < count($results); ++$i) {
			$arr[$results[$i]->post_type] = $results[$i]->post_type;
		}

		return $arr;
	}

	function post_order_category()
	{
		global $wpdb, $custom_cat, $stop_join;

		$category = trim($_POST['category']);

		$args = array(
			'type' => 'post',
			'child_of' => '',
			'parent' => '',
			'orderby' => 'name',
			'order' => 'ASC',
			'hide_empty' => true,
			'exclude' => array(0),
			'hierarchical' => true,
			'taxonomy' => 'category',
			'pad_counts' => true
		);

		$categories = get_categories($args);

		$opt = array();
		$opt[] = '<option value="" selected>Selected</option>';

		foreach ($categories as $id => $cat) {
			if ($cat->term_id == $category) {
				$opt[] = '<option value="' . $cat->term_id . '" selected>' . $cat->name . '</option>';
			} else {
				$opt[] = '<option value="' . $cat->term_id . '">' . $cat->name . '</option>';
			}
		}

		$post_types = $this->ccpo_get_post_type();

		foreach ($post_types as $k => $v) {
			if ($k == $category) {
				$opt[] = '<option value="' . $k . '" selected>' . $v . '</option>';
			} else {
				$opt[] = '<option value="' . $k . '" >' . $v . '</option>';
			}
		}

		$temp_order = array();

		if ($category != '') {
			//get the order 	
			$sql = $wpdb->prepare("select * from " . $wpdb->prefix . "ccpo_post_order_rel where category_id = '%s' order by weight", $category);

			$order_result = $wpdb->get_results($sql);

			for ($k = 0; $k < count($order_result); ++$k) {
				$order_result_incl[$order_result[$k]->post_id] = $order_result[$k]->incl;
			}

			if (is_numeric($category) == true) {
				$args = array(
					'category__in' => array($category),
					'posts_per_page' => -1,
					'post_type' => 'post',
					'orderby' => 'title',
					'post_status' => 'publish',
					'order' => 'DESC'
				);
			} else {
				$args = array(
					'posts_per_page' => -1,
					'post_type' => $category,
					'orderby' => 'title',
					'post_status' => 'publish',
					'order' => 'DESC'
				);
			}

			$stop_join = true;
			$custom_cat = $category;

			$query = new WP_Query($args);
			$stop_join = false;
			$custom_cat = 0;
			$posts_array = $query->posts;

			for ($j = 0; $j < count($posts_array); ++$j) {
				$temp_order[$posts_array[$j]->ID] = $posts_array[$j];
			}

		}


		$checked = get_option("ccpo_category_ordering_" . $category);

		print '<div class="wrap">
		<h2>Post order by category or post type</h2>
		<div>
		<table width="100%" cellspacing="0" cellpadding="2">
		<tr>
		<td><h3>Help us to promote this plugin, Give us five star rating <a href="https://wordpress.org/support/plugin/custom-post-order-category/reviews/"  target="new">click here</a></h3></td>
		<td><strong>See Premium Plugin with more features <a href="https://scriptut.com/wordpress/advanced-custom-category-post-type-post-order/" target="new">Click here</a></strong></td>
		</tr>
		</table>
		</div>
		<form method="post">';
		wp_nonce_field('update-options');

		print '<table cellspacing="4" cellpadding="10" style="background:#98AFC7;width:100%;border: 1px solid #6D7B8D; border-radius: 5px;" >
			<tr valign="top">
			<td><strong>Select category or post type:</strong>&nbsp;<select name="category" id="category">' . implode("", $opt) . '</select>
			</td>';


		if ($category != '') {
			print '<td><strong>Enable Ordering:&nbsp;&nbsp;<input type="checkbox" name="category_ordering" rel="' . $category . '" id="user_ordering_category" value="1" ' . $checked . '></strong></td>';
		}

		print '<td>
				<input type="submit" class="button button-primary" value="Load Posts" id="Load_Posts"/>
			</td></tr>
			</table>';

		print '<small>Note: Initially some post may display without remove or add link it.</small>';
		$html = '<div id="sortablewrapper">';
		$html .= '<ul id="sortable" class="sortableul">';

		if ($order_result) {


			for ($i = 0; $i < count($order_result); ++$i) {
				$post_id = $order_result[$i]->post_id;
				$post = $temp_order[$post_id];

				unset($temp_order[$post_id]);

				$total = $this->check_order_table($post->ID, $category);

				$od = $order_result_incl[$post->ID];

				if ($od == 1) {
					$edit = '<small><a href="javascript:void(0);" onclick="rempst(' . $post->ID . ',\'' . $category . '\')">Remove</a></small>';
				} else {
					$edit = '<small><a href="javascript:void(0);" onclick="rempst(' . $post->ID . ',\'' . $category . '\')">Add</a></small>';
				}

				if ($checked == "checked") {
					if ($total > 0) {
						$html .= '<li class="sortable" id="' . $post->ID . '" rel="' . $post->ID . '" post_title="' . $post->post_title . '">';
						$html .= '<div id="post" class="drag_post">' . $post->post_title . '<div class="ar_link" id="id_' . $post->ID . '">' . $edit . '</div></div>';
					}
				} else {
					$html .= '<li class="sortable" id="' . $post->ID . '" rel="' . $post->ID . '" post_title="' . $post->post_title . '">';
					$html .= '<div id="post" class="drag_post">' . $post->post_title . '<div class="ar_link"   id="id_' . $post->ID . '">' . $edit . '</div></div>';
				}
				$html .= '</li>';
			}
		}


		foreach ($temp_order as $temp_order_id => $temp_order_post) {
			$post_id = $temp_order_id;
			$post = $temp_order_post;
			$total = $this->check_order_table($post->ID, $category);
			if (trim($post->post_title) != '') {
				$html .= '<li class="sortable" id="' . $post->ID . '" rel="' . $post->ID . '" post_title="' . $post->post_title . '">';
				$html .= '<div id="post" class="drag_post">' . $post->post_title . '<div class="ar_link" ></div></div>';
				$html .= '</li>';
			}

		}

		$html .= '</ul>';
		$html .= '</div>';
		print $html;



		print '<input type="hidden" name="action" value="update" />
			</form>
			</div>';
		print '<style>
			.update-nag{
				display:none;
			}
			 #sortablewrapper {
			    width:99%;
				border:0px solid #c1e2b3;
				padding-top:20px;
					border-radius:5px;
			 }
	         .sortableul {
			  width:100% !important;
			  }
			  .ar_link {
					float:right;
					width:50px;
					text-decoration:none;
					color:#a94442;
				}
				
				.ar_link a {
					text-decoration:none;
					color:#a94442;
					font-size:12px;
				}
				
				.drag_post {
					 border:1px dashed #245269;
					 background:#F1F1F1;
					 padding:5px;
					 padding-right:15px;
					 width:100%;
					 font-size:14px;
				}
				.drag_post:hover {
					 cursor:crosshair;
				}
	      #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
	      #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1em; font-size: 1.4em; height: 18px;font-weight: bold; }
	      #sortable li span { position: absolute; margin-left: -1em; }
	      </style>
	      <script>
	      jQuery(document).ready(function($) {
				
			
				// var relValue = $("#user_ordering_category").prop("rel");
				// console.log(relValue)
				// console.log(jQuery("#user_ordering_category").prop("rel"));
				// var relValue = checkbox.getAttribute("rel");
				
				jQuery("#user_ordering_category").click(function() {
					var checkbox = document.getElementById("user_ordering_category");
					
					 var category = checkbox.getAttribute("rel");
					 var checked = checkbox.checked;
		
					 jQuery.post(\'admin-ajax.php\', {checked:checked,category:category,action:\'user_ordering\'});
				});

	     		jQuery( "#sortable" ).sortable({
	            start: function (event, ui) {},
	            sort: function (event, ui) {},
				stop: function (event, ui) {},						
	            change:  function (event, ui) {},
				update: function(event, ui) {
					var newOrder = jQuery(this).sortable(\'toArray\').toString();
					var category = jQuery("#category").val();
					jQuery.post(\'admin-ajax.php\', {order:newOrder,category:category,action:\'build_order\'});
				}
	       });
					 //jQuery( "#sortable" ).disableSelection();
	       });
			function rempst(post_id,cat_id) {
				jQuery.post(\'admin-ajax.php\', {post_id:post_id,category:cat_id,action:\'rmppost\'},
				function success(data) {
					jQuery("#id_"+post_id).html(data);
				});
			}
	      </script>';

		?>

		<?php
	}



	function rmppost()
	{
		global $wpdb; // this is how you get access to the database
		$category = $_POST['category'];
		$post_id = intval($_POST['post_id']);

		$incl = $wpdb->get_var($wpdb->prepare("select incl from " . $wpdb->prefix . "ccpo_post_order_rel where category_id = '%s' and post_id = '%d'", $category, $post_id));

		$new_incl = ($incl == 1) ? 0 : 1;
		$wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "ccpo_post_order_rel set incl = '%d' where category_id = '%s' and post_id = '%d'", $new_incl, $category, $post_id));

		if ($new_incl == 1) {
			$edit = '<small><a href="javascript:void(0);" onclick="rempst(' . $post_id . ',\'' . $category . '\')">Remove</a></small>';
		} else {
			$edit = '<small><a href="javascript:void(0);" onclick="rempst(' . $post_id . ',\'' . $category . '\')">Add</a></small>';
		}
		print $edit;
		die(); // this is required to return a proper result
	}


	function add_slideshowjs()
	{

	}

	function check_order_table($post, $cat)
	{
		global $wpdb; // this is how you get access to the database
		$total = $wpdb->get_var($wpdb->prepare("select count(*) as total from   " . $wpdb->prefix . "ccpo_post_order_rel where category_id = '%s' and post_id = '%d'", $cat, $post));
		return $total;
	}


	function process_post()
	{
		global $wp_query;
		wp_enqueue_script('jquery-ui-sortable', '/wp-includes/js/jquery/ui/jquery.ui.sortable.min.js', array('jquery-ui-core', 'jquery-ui-mouse'), '1.8.20', 1);

	}


	function build_order_callback()
	{
		global $wpdb; // this is how you get access to the database

		$order = explode(",", $_POST['order']);
		$category = ($_POST['category']);

		
		//$wpdb->query("delete from ".$wpdb->prefix."ccpo_post_order_rel where category_id = '$category'");

		$total = $wpdb->get_var($wpdb->prepare("select count(*) as total from " . $wpdb->prefix . "ccpo_post_order_rel where category_id = '%s'", $category));

		if ($total == 0) { //executes when there is not date for selected category
			foreach ($order as $post_id) {
				++$weight;
				$safe_post_id = intval($post_id);
				if ($safe_post_id > 0) {
					$value[] = "('$category', '$safe_post_id','$weight')";
				}

			}
			$sql = "insert into " . $wpdb->prefix . "ccpo_post_order_rel (category_id,post_id,weight)  values " . implode(",", $value);

			$wpdb->query($sql);
		} else {
			$weight = 0;
			foreach ($order as $post_id) {
				++$weight;
				$safe_post_id = intval($post_id);
				//$sql = "update ".$wpdb->prefix."ccpo_post_order_rel set weight='$weight' where post_id = '$post_id' and category_id = '$category'";
				$wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "ccpo_post_order_rel set weight='%d' where post_id = '%d' and category_id = '%s'", $weight, $safe_post_id, $category));
			}

			$results = $wpdb->get_results($wpdb->prepare("select * from " . $wpdb->prefix . "ccpo_post_order_rel where category_id = '%s' order by weight", $category));

			foreach ($results as $index => $result_row) {
				$result_arr[$result_row->post_id] = $result_row;
			}

			$start = 0;
			foreach ($order as $post_id) {
				$safe_post_id = intval($post_id);
				$inc_row = $result_arr[$safe_post_id];
				$incl = $inc_row->incl;
				$row = $results[$start];
				++$start;
				$id = $row->id;

				$exists = $wpdb->get_var($wpdb->prepare("select count(*) as total from " . $wpdb->prefix . "ccpo_post_order_rel  where post_id = '%d' and category_id = '%s'", $safe_post_id, $category));

				if ($exists > 0) {
					$sql = $wpdb->prepare("update " . $wpdb->prefix . "ccpo_post_order_rel set post_id = '%d',incl = '%d' where id = '%d'", $safe_post_id, $incl, $id);
					$wpdb->query($sql);
				} else {
					$sql = $wpdb->prepare("insert into " . $wpdb->prefix . "ccpo_post_order_rel set category_id = '%s' ,post_id = '%d', incl = '0'", $category, $safe_post_id);
					$wpdb->query($sql);
				}
			}
		}
		die(); // this is required to return a proper result
	}


	function ccpo_query_join($args, $x)
	{
		global $wpdb, $custom_cat, $stop_join;

		$category_id = intval(get_query_var("cat"));

		$post_types_arr = $this->ccpo_get_post_type();
		foreach ($post_types_arr as $post_type_key => $post_type_value) {
			$tmp_post_types_arr[] = $post_type_value;
		}

		if (!$category_id) {
			$category_id = trim(get_query_var("post_type"));
			if ($category_id != '') {
				if (!in_array($category_id, $tmp_post_types_arr)) {
					$category_id = 0;
				}
			}

		}
		if (!$category_id) {
			$category_id = $custom_cat;
		}

		if (get_option("ccpo_category_ordering_" . $category_id) == "checked" && $stop_join == false) {
			$args .= " INNER JOIN " . $wpdb->prefix . "ccpo_post_order_rel ON " . $wpdb->posts . ".ID = " . $wpdb->prefix . "ccpo_post_order_rel.post_id and incl = 1  ";
		}
		return $args;
	}



	function ccpo_query_where($args)
	{
		global $wpdb, $custom_cat, $stop_join;
		$category_id = intval(get_query_var("cat"));
		if (!$category_id) {
			$category_id = get_query_var("post_type");
		}

		if (!$category_id) {
			$category_id = $custom_cat;
		}
		if (get_option("ccpo_category_ordering_" . $category_id) == "checked" && $stop_join == false) {
			$args .= " AND " . $wpdb->prefix . "ccpo_post_order_rel.category_id = '" . $category_id . "'";
		}
		return $args;
	}


	function ccpo_query_orderby($args)
	{
		global $wpdb, $custom_cat, $stop_join;
		$category_id = intval(get_query_var("cat"));

		if (!$category_id) {
			$category_id = get_query_var("post_type");
		}

		if (!$category_id) {
			$category_id = $custom_cat;
		}
		
		

		if (get_option("ccpo_category_ordering_" . $category_id) == "checked" && $stop_join == false) {
			$args = $wpdb->prefix . "ccpo_post_order_rel.weight ASC";
		
		}
				
		return $args;
	}





	function user_ordering()
	{
		global $wpdb; // this is how you get access to the database
		$category = $_POST['category'];
		$checked = trim($_POST['checked']);
		

		if ($checked == 'true') {
			update_option("ccpo_category_ordering_" . $category, "checked");
		} else {
			update_option("ccpo_category_ordering_" . $category, "");
		}

		die(); // this is required to return a proper result
	}


	function ccpo_update_post_order($post_id)
	{
		global $wpdb;
		if (!wp_is_post_revision($post_id)) {
			$post = get_post($post_id, $output);
			$cats = get_the_category($post_id);
			foreach ($cats as $key => $cat) {
				$cat_id = $cat->term_id;
				$total = $wpdb->get_var($wpdb->prepare("select count(*) as total from  " . $wpdb->prefix . "ccpo_post_order_rel where category_id = '%d' and post_id = '%d'", $cat_id, $post_id));

				if ($total == 0 && $post_id > 0) {
					$sql = $wpdb->prepare("insert into " . $wpdb->prefix . "ccpo_post_order_rel (category_id,post_id) values ('%s','%d')", $cat_id, $post_id);
					$wpdb->query($sql);
				}
			}
		}
	}



	function ccpo_install()
	{
		global $wpdb;
		global $ccpo_db_version;
		$table_name = $wpdb->prefix . "ccpo_post_order_rel";

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`category_id` varchar(250) NOT NULL,
					`post_id` int(11) NOT NULL,
					`incl` tinyint(1) NOT NULL DEFAULT '1',
					`weight` int(11) NOT NULL DEFAULT '0',
					PRIMARY KEY (`id`)
		 ) ;";

		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		add_option('ccpo_db_version', $ccpo_db_version);

	}



	function ccpo_uninstall()
	{
		global $wpdb;
		global $ccpo_db_version;
		$table_name = $wpdb->prefix . "ccpo_post_order_rel";

		$sql = "DROP TABLE IF EXISTS $table_name";
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta($sql);

		delete_option('ccpo_db_version');

		$table = $wpdb->prefix . "options";
		$where = array('option_name like' => 'ccpo%');
		$wpdb->delete($table, $where);
	}

}

new customcategorypostorder();
