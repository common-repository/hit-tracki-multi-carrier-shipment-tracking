<?php
/**
 * Plugin Name: HITSHIPO - Automated Shipment Tracking
 * Plugin URI: https://hitstacks.com/
 * Description: Provides tracking informations for different Carriers.
 * Version: 1.0.1
 * Author: HITShipo
 * Author URI: https://hitshipo.com/
 * Developer: hitshipo
 * Developer URI: https://hitshipo.com/
 * Text Domain: hit_tracki
 * Domain Path: /i18n/languages/
 *
 * WC requires at least: 2.6
 * WC tested up to: 5.8
 *
 *
 * @package WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define WC_PLUGIN_FILE.
if ( ! defined( 'HIT_TRACKI_PLUGIN_FILE' ) ) {
	define( 'HIT_TRACKI_PLUGIN_FILE', __FILE__ );
}

function hit_tracki_plugin_activation( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        $setting_value = version_compare(WC()->version, '2.1', '>=') ? "wc-settings" : "woocommerce_settings";
    	// Don't forget to exit() because wp_redirect doesn't exit automatically
    	exit( wp_redirect( admin_url( 'admin.php?page=' . $setting_value  . '&tab=shipping&section=hit_tracki' ) ) );
    }
}
add_action( 'activated_plugin', 'hit_tracki_plugin_activation' );

// Include the main WooCommerce class.
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	
	if( !class_exists('hit_tracki_parent') ){
		Class hit_tracki_parent
		{
			private $errror = '';
			public function __construct() {
				add_action( 'woocommerce_shipping_init', array($this,'hit_tracki_init') );
				add_action( 'init', array($this,'hit_order_status_update') );
				add_filter( 'woocommerce_shipping_methods', array($this,'hit_tracki_method') );
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'hit_tracki_plugin_action_links' ) );
				add_action( 'add_meta_boxes', array($this, 'create_tracki_shipping_meta_box' ));
				add_action( 'save_post', array($this, 'hit_tracki_create_tracking'), 10, 1 );
				add_action( 'admin_menu', array($this, 'hit_tracki_menu_page' ));
				add_action('woocommerce_order_details_after_order_table', array( $this, 'hit_tracki_trk_for_cus' ) );
				add_action('admin_print_styles', array($this, 'hits_admin_scripts'));
			
			}
			public function hits_admin_scripts() {
		        global $wp_scripts;
		        wp_enqueue_script('wc-enhanced-select');
		        wp_enqueue_script('chosen');
		        wp_enqueue_style('woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css');

		    }
			
			function hit_tracki_menu_page() {
				
				add_submenu_page( 'options-general.php', 'HITShipo Tracking Config', 'HITShipo Tracking Config', 'manage_options', 'hit-tracki-configuration', array($this, 'my_admin_page_contents') ); 

			}
			
			function my_admin_page_contents(){
				include_once('controllors/views/hit_tracki_settings_view.php');
			}

			public function hit_tracki_trk_for_cus($order){
				$general_settings = get_option('hit_tracki_main_settings');
				$order_id = $order->get_id();
				include_once('controllors/views/hit_tracki_trk_fo.php');
			}

			public function hit_tracki_init()
			{
				include_once("controllors/hit_tracki_init.php");
			}
			public function hit_order_status_update(){
				global $woocommerce;
				if(isset($_GET['hitshipo_key'])){
					$hitshipo_key = sanitize_text_field($_GET['hitshipo_key']);
					if($hitshipo_key == 'fetch'){
						echo json_encode(array(get_transient('hit_tracki_nonce_temp')));
						die();
					}
				}

				if(isset($_GET['hitshipo_integration_key']) && isset($_GET['hitshipo_action'])){
					$integration_key = sanitize_text_field($_GET['hitshipo_integration_key']);
					$hitshipo_action = sanitize_text_field($_GET['hitshipo_action']);
					$general_settings = get_option('hit_tracki_main_settings');
					$general_settings = empty($general_settings) ? array() : $general_settings;
					if(isset($general_settings['hit_tracki_integration_key']) && $integration_key == $general_settings['hit_tracki_integration_key']){
						if($hitshipo_action == 'stop_working'){
							update_option('hit_tracki_working_status', 'stop_working');
						}else if ($hitshipo_action = 'start_working'){
							update_option('hit_tracki_working_status', 'start_working');
						}
					}
					
				}

			}
			public function hit_tracki_method( $methods )
			{
				if (is_admin() && !is_ajax() || apply_filters('hit_tracki_method_enabled', true)) {
					$methods['hit_tracki'] = 'hit_tracki'; 
				}

				return $methods;
			}
			
			public function hit_tracki_plugin_action_links($links)
			{
				$setting_value = version_compare(WC()->version, '2.1', '>=') ? "wc-settings" : "woocommerce_settings";
				$plugin_links = array(
					'<a href="' . admin_url( 'admin.php?page=' . $setting_value  . '&tab=shipping&section=hit_tracki' ) . '" style="color:green;">' . __( 'Configure', 'hit_tracki' ) . '</a>',
					'<a href="https://app.hitshipo.com/support" target="_blank" >' . __('Support', 'hit_tracki') . '</a>'
					);
				return array_merge( $plugin_links, $links );
			}
			public function create_tracki_shipping_meta_box() {
	       		add_meta_box( 'hit_tracki', __('HITShipo - Automated Shipment Tracking','hit_tracki'), array($this, 'create_hit_tracki_options'), 'shop_order', 'normal', 'core' );
		    }
		    public function create_hit_tracki_options($post){
		    	if($post->post_type != 'shop_order' ){
					return;
				}
				$order = wc_get_order( $post->ID );
				$order_id = $order->get_id();
				include_once('controllors/views/hit_tracki_trk_bo.php');
		    }

		    // Save the data of the Meta field
			public function hit_tracki_create_tracking( $order_id ) {
				
		    	$post = get_post($order_id);
		    	if($post->post_type != 'shop_order' ){
		    		return;
		    	}

		        if (  isset( $_POST['hit_tracki_save']) ) {
		        	$trk_no = isset($_POST['hit_tracki_trk_no']) ? sanitize_text_field($_POST['hit_tracki_trk_no']) : "";
		        	$trk_carrier = isset($_POST['hit_tracki_trk_carrier']) ? sanitize_text_field($_POST['hit_tracki_trk_carrier']) : "";
		        	$trk_data = array("trk_no" => $trk_no, "trk_carrier" => $trk_carrier);

		        	update_option('hit_tracki_values_'.$order_id, json_encode($trk_data));
		        }
		    }

		    // Save the data of the Meta field
			
		}
		
	}
	$hit_tracki = new hit_tracki_parent();
}