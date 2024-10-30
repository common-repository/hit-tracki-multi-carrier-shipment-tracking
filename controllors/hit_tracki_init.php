<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
use Google\Cloud\Translate\TranslateClient;
if (!class_exists('hit_tracki')) {
	class hit_tracki extends WC_Shipping_Method
	{
		/**
		 * Constructor for your shipping class
		 *
		 * @access public
		 * @return void
		 */
		public function __construct()
		{
			$this->id                 = 'hit_tracki';
			$this->method_title       = __('HITShipo - Automated Shipment Tracking');  // Title shown in admin
			$this->title       = __('HITShipo - Automated Shipment Tracking');
			$this->method_description = __(''); // 
			$this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
			$this->init();
		}

		/**
		 * Init your settings
		 *
		 * @access public
		 * @return void
		 */
		function init()
		{
			// Load the settings API
			$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
			$this->init_settings(); // This is part of the settings API. Loads settings you previously init.

			// Save settings in admin if you have any defined
			add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
		}

		/**
		 * calculate_shipping function.
		 *
		 * @access public
		 * @param mixed $package
		 * @return void
		 */
		public function calculate_shipping($package = array())
		{

		}

		/**
		 * Initialise Gateway Settings Form Fields
		 */
		public function init_form_fields()
		{
			$this->form_fields = array('hit_tracki' => array('type' => 'hit_tracki'));
		}
		public function generate_hit_tracki_html()
		{
			$general_settings = get_option('hit_tracki_main_settings');
			$general_settings = empty($general_settings) ? array() : $general_settings;
			if(!empty($general_settings)){
				wp_redirect(admin_url('options-general.php?page=hit-tracki-configuration'));
			}

			if(isset($_POST['configure_the_plugin'])){
				global $woocommerce;
				$countries_obj   = new WC_Countries();
				$countries   = $countries_obj->__get('countries');
				$default_country = $countries_obj->get_base_country();

				if(!isset($general_settings['hit_tracki_country'])){
					$general_settings['hit_tracki_country'] = $default_country;
					update_option('hit_tracki_main_settings', $general_settings);
				
				}
				wp_redirect(admin_url('options-general.php?page=hit-tracki-configuration'));	
			}
		?>
			<style>

			.card {
				background-color: #fff;
				border-radius: 5px;
				width: 800px;
				max-width: 800px;
				height: auto;
				text-align:center;
				margin: 10px auto 100px auto;
				box-shadow: 0px 1px 20px 1px hsla(213, 33%, 68%, .6);
			}  

			.content {
				padding: 20px 20px;
			}


			h2 {
				text-transform: uppercase;
				color: #000;
				font-weight: bold;
			}


			.boton {
				text-align: center;
			}

			.boton button {
				font-size: 18px;
				border: none;
				outline: none;
				color: #166DB4;
				text-transform: capitalize;
				background-color: #fff;
				cursor: pointer;
				font-weight: bold;
			}

			button:hover {
				text-decoration: underline;
				text-decoration-color: #166DB4;
			}
						</style>
						<!-- Fuente Mulish -->
						

			<div class="card">
				<div class="content">
					<div class="logo">
					<img src="<?php echo plugin_dir_url(__FILE__); ?>views/hittracki.png" style="width:150px;" alt="logo" />
					</div>
					<h2><strong>HITShipo - Automated Shipment Tracking</strong></h2>
					<p style="font-size: 14px;line-height: 27px;">
					<?php _e('Welcome to HITSHIPO! You are at just one-step ahead to configure the Multi-Carrier Tracking with HITSHIPO.','hit_tracki') ?><br>
					<?php _e('We have lot of features that will take your e-commerce store to another level.','hit_tracki') ?><br><br>
					<?php _e('HITSHIPO helps you to save time, reduce errors, and worry less when you automate your tedious, manual tasks. HITSHIPO automated shipment tracking will provide tracking details of shipments for both seller and buyer.','hit_tracki') ?><br><br>
					<?php _e('Make your customers happier by reacting faster and handling their service requests in a timely manner, meaning higher store reviews and more revenue.','hit_tracki') ?><br>
					</p>
						
				</div>
				<div class="boton" style="padding-bottom:10px;">
				<button class="button-primary" name="configure_the_plugin" style="padding:8px;">Configure the plugin</button>
				</div>
				</div>
			<?php
			echo '<style>button.button-primary.woocommerce-save-button{display:none;}</style>';
		}
	}
}
