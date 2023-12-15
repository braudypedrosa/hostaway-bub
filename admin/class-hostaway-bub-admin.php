<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://www.buildupbookings.com/
 * @since      1.0.0
 *
 * @package    Hostaway_Bub
 * @subpackage Hostaway_Bub/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hostaway_Bub
 * @subpackage Hostaway_Bub/admin
 * @author     Braudy Pedrosa <braudy@buildupbookings.com>
 */
class Hostaway_Bub_Admin {

	const API_ROOT = 'https://api.hostaway.com/v1/';
	const TEST_KEY = 'bfed3b7c5c9c0fcb2e0287ece074b0c8066dc25937de5487c4f4eca50e94ab99';

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the menu items in the admin area
	 */
	 public function menu_init() {
		add_submenu_page(
			'edit.php?post_type=hostaway_bub',
			__( 'Settings', 'textdomain' ),
			'Settings',
			'manage_options',
			'hostaway_settings',
			function(){
				include_once(plugin_dir_path(dirname(__FILE__)) . 'admin/partials/hostaway-bub-admin-display.php');

			},
		); 
	 }

	 public function save_menu() {

		$client_id = isset($_POST['client_id']) ? $_POST['client_id'] : get_option('client_id');
		$client_secret = isset($_POST['client_secret']) ? $_POST['client_secret'] : get_option('client_secret');

		if($client_id != '' || $client_secret != '') {
			update_option('hostaway_client_id', $client_id);
			update_option('hostaway_client_secret', $client_secret);

			$api = new Hostaway_API($client_id, $client_secret);
			$response = $api->generateToken();
		} else {
			$response = array(
				'status' => 'fail',
				'message' => 'Please fill in client ID and client secret to generate token!'
			);
		}

		

		header("Location: " . get_bloginfo("url") . "/wp-admin/admin.php?page=hostaway_settings&status=".$response['status']."&msg=".$response['message']);
		exit;
	 }

	 public function sync_properties() {
		
		$token = get_option('hostaway_token');

		if(!empty($token)) {
			$api = new Hostaway_API();
			$response = $api->syncProperties();

			header("Location: " . get_bloginfo("url") . "/wp-admin/admin.php?page=hostaway_settings&status=".$response['status']."&msg=".$response['message']);
			exit;
			
		} else {
			$errorMsg = "Invalid token! Please try saving the settings and try syncing again.";
			header("Location: " . get_bloginfo("url") . "/wp-admin/admin.php?page=hostaway_settings&status=fail&msg=".$errorMsg);
			exit;
		}
	 }


	 public function post_type_init() {

		register_post_type( 'hostaway_bub',

			array('labels' => array(
					'name' => __('Hostaway Listings', 'jointswp'), /* This is the Title of the Group */
					'singular_name' => __('Listing', 'jointswp'), /* This is the individual type */
					'all_items' => __('All Listings', 'jointswp'), /* the all items menu item */
					'add_new' => __('Add New Listing', 'jointswp'), /* The add new menu item */
					'add_new_item' => __('Add New Listing', 'jointswp'), /* Add New Display Title */
					'edit' => __( 'Edit Listing', 'jointswp' ), /* Edit Dialog */
					'edit_item' => __('Edit Listing', 'jointswp'), /* Edit Display Title */
					'new_item' => __('New Listing', 'jointswp'), /* New Display Title */
					'view_item' => __('View Listing', 'jointswp'), /* View Display Title */
					'search_items' => __('Search', 'jointswp'), /* Search Custom Type Title */
					'not_found' =>  __('Nothing found in the Database.', 'jointswp'), /* This displays if there are no entries yet */
					'not_found_in_trash' => __('Nothing found in Trash', 'jointswp'), /* This displays if there is nothing in the trash */
					'parent_item_colon' => ''
				), /* end of arrays */
				'public' => true,
				'publicly_queryable' => true,
				'exclude_from_search' => false,
				'show_ui' => true,
				'query_var' => true,
				'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */
				'menu_icon' => 'dashicons-store', /* the icon for the custom post type menu. uses built-in dashicons (CSS class name) */
				'rewrite'	=> array( 'slug' => 'property', 'with_front' => true ), /* you can specify its url slug */
				'has_archive' => 'properties', /* you can rename the slug here */
				'capability_type' => 'post',
				'hierarchical' => false,
				/* the next one is important, it tells what's enabled in the post editor */
				'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields'),
				'taxonomies' => array('post-tags')	

			) /* end of options */

		); /* end of register post type */
	 }

	public function taxonomy_init() {

		$amenityLabels = array(
			'name' => _x( 'Amenities', 'taxonomy general name' ),
			'singular_name' => _x( 'Amenity', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Amenities' ),
			'all_items' => __( 'All Amenities' ),
			'parent_item' => __( 'Parent Amenity' ),
			'parent_item_colon' => __( 'Parent Amenity:' ),
			'edit_item' => __( 'Edit Amenity' ), 
			'update_item' => __( 'Update Amenity' ),
			'add_new_item' => __( 'Add New Amenity' ),
			'new_item_name' => __( 'New Amenity Name' ),
			'menu_name' => __( '	Amenities' ),
		);  
		
		register_taxonomy('amenities', array('hostaway_bub'), array(
			'hierarchical' => true,
			'labels' => $amenityLabels,
			'show_ui' => true,
			'show_in_rest' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'amenities' ),
		));

		$groupLabels = array(
			'name' => _x( 'Groups', 'taxonomy general name' ),
			'singular_name' => _x( 'Group', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Groups' ),
			'all_items' => __( 'All Groups' ),
			'parent_item' => __( 'Parent Group' ),
			'parent_item_colon' => __( 'Parent Group:' ),
			'edit_item' => __( 'Edit Group' ), 
			'update_item' => __( 'Update Group' ),
			'add_new_item' => __( 'Add New Group' ),
			'new_item_name' => __( 'New Group Name' ),
			'menu_name' => __( '	Groups' ),
		);  
		
		register_taxonomy('groups', array('hostaway_bub'), array(
			'hierarchical' => true,
			'labels' => $groupLabels,
			'show_ui' => true,
			'show_in_rest' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'groups' ),
		));

	}

	public function meta_boxes_init() {
        add_meta_box(
            'hostaway_bub_metabox',          
            'Listing Images',              
            function() {
				global $post;

				$images = get_post_meta($post->ID, 'stored_images');

				if(!empty($images)) {
					echo '<div class="admin-property-images">';
					foreach($images[0] as $image) {
						echo '<img width="150" style="margin-right: 10px;" src="'.$image['url'].'" />';
					}
					echo '</div>';
				}
				

				
			},
            'hostaway_bub'         
        );
    }

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hostaway-bub-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hostaway-bub-admin.js', array( 'jquery' ), $this->version, false );
	}

	public function add_plugin_links($links)
	{
			$url = esc_url( add_query_arg('page', 'hostaway_settings', get_admin_url() . 'admin.php') );

			// Create the link.
			$settings_link = "<a href='$url'>" . __( 'Settings' ) . "</a>";

			// Adds the link to the end of the array.
			array_push(
					$links,
					$settings_link
			);

			return $links;
	}

}
