<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

wp_enqueue_script("jquery");

global $woocommerce, $wp_roles, $post;

if (empty($order_id)) {
	return;
}

$general_settings = get_option('hit_tracki_main_settings',array());
if (empty($general_settings)) {
	echo '<center><span style="color:red">Save plugin configurations</span></center>';
	return;
}
if ( (!isset($general_settings['hit_tracki_service'])) || (isset($general_settings['hit_tracki_service']) && $general_settings['hit_tracki_service'] != "yes") ) {
	echo '<center><span style="color:red">Tracking service didn\'t enabled</span></center>';
	return;
}
$carriers = array("DHL" => "DHL Express",
									"UPS" => "UPS",
									"FEDEX" => "Fedex",
									"CP" => "Canada Post",
									"PUROLATOR" => "Purolator"
								);


?>

<style type="text/css">
	*, *::after, *::before {
	  padding: 0;
	  margin: 0;
	  box-sizing: border-box;
	}

	.hit_tracki_fig_cap_h4, .hit_tracki_fig_cap_h6, .hit_tracki_fig_cap_h2 {
	  margin: 0;
	  padding: 0;
	  display: inline-block;
	}

	.hit_tracki_root {
	  padding: 1rem;
	  border-radius: 5px;
	  box-shadow: 0 2rem 6rem rgba(0, 0, 0, 0.3);
	  margin: 2rem;
	}

	.hit_tracki_fig {
	  display: flex;
	  margin-top: 10px;
	}
	.hit_tracki_img {
	  width: 4rem;
	  height: 4rem;
	  border-radius: 15%;
	  border: 1.5px solid #f05a00;
	  margin-right: 1.5rem;
	  padding:10px;
	}
	.hit_tracki_fig_cap {
	  display: flex;
	  flex-direction: column;
	  justify-content: space-evenly;
	}
	.hit_tracki_fig_cap_h4 {
	  font-size: 1.4rem;
	  font-weight: 500;
	}
	.hit_tracki_fig_cap_h6 {
	  font-size: 1rem;
	  font-weight: 300;
	}
	.hit_tracki_fig_cap_h2 {
	  font-size: 1.6rem;
	  font-weight: 500;
	}

	.order-track {
	  margin-top: 2rem;
	  padding: 0 1rem;
	  border-top: 1px dashed #2c3e50;
	  padding-top: 2.5rem;
	  display: flex;
	  flex-direction: column;
	}
	.order-track-step {
	  display: flex;
	  height: 4rem;
	}
	.order-track-step:last-child {
	  overflow: hidden;
	  height: 4rem;
	}
	.order-track-step:last-child .order-track-status span:last-of-type {
	  display: none;
	}
	.order-track-status {
	  margin-right: 1.5rem;
	  position: relative;
	}
	.order-track-status-dot {
	  display: block;
	  width: 1.1rem;
	  height: 1.1rem;
	  border-radius: 50%;
	  background: #f05a00;
	}
	.order-track-status-line {
	  display: block;
	  margin: 0 auto;
	  width: 2px;
	  height: 4rem;
	  background: #f05a00;
	}
	.order-track-text-stat {
	  font-size: 1rem;
	  font-weight: 500;
	  margin-bottom: 3px;
	  overflow: hidden;
	}
	.order-track-text-sub {
	  font-size: 1rem;
	  font-weight: 300;
	}
	.order-track {
	  transition: all .3s height 0.3s;
	  transform-origin: top center;
	}
	.td_pad {
		padding:5px;
	}
</style>

<section class="hit_tracki_root">
	<?php 
		$trk_data = get_option('hit_tracki_values_'.$order_id);
		$trk_data = json_decode($trk_data, true);
		$saved_api_configs = get_option('hit_tracki_carrier_settings');
	?>
			<table id="hit_tracki_head" style="padding:5px;<?php echo (!empty($trk_data)) ? 'display: none;' : '' ?>">
				<tr>
					<td class="td_pad">
						<label class=""><b>Enter Tracking No : </b></label>
						<input type="text" name="hit_tracki_trk_no" style="width:150px;" value="<?php echo (isset($trk_data['trk_no'])) ? _e($trk_data['trk_no'],'hit_tracki') : '' ?>" required>
					</td>
					<td class="td_pad">
						<label class=""><b>Choose Carrier : </b></label>
						<select name="hit_tracki_trk_carrier" class="wc-enhanced-select" style="width:150px;">
							<?php 
								foreach ($carriers as $key => $value) {
									if (isset($trk_data['trk_carrier']) && ($trk_data['trk_carrier'] == $key) ) {
										_e('<option value="'.$key.'" selected>'.$value.'</option>','hit_tracki');
									} elseif ( isset($saved_api_configs[$key]) ) {
										_e('<option value="'.$key.'" >'.$value.'</option>','hit_tracki');
									}
								}
							 ?>
						</select>
					</td>
					<td class="td_pad">
						<button class="button button-primary" type="submit" name="hit_tracki_save" style="height: 40px;">Save To Track</button>
					</td>
				</tr>
			</table>
		  
	<?php 
		  if (!empty($trk_data)) {
		  	if (isset($trk_data['trk_carrier']) && !isset($saved_api_configs[$trk_data['trk_carrier']])) {
		  		echo '<center><span style="color:red">Please enable selected carrier in plugin configurations</span></center>';
					return;
		  	}
		  	$input_data = array("integrated_key" => isset($general_settings['hit_tracki_integration_key']) ? $general_settings['hit_tracki_integration_key'] : "",
		  											"type" => isset($trk_data['trk_carrier']) ? strtolower($trk_data['trk_carrier']) : "",
		  											"trk_num" => isset($trk_data['trk_no']) ? $trk_data['trk_no'] : "",
		  											"id" => isset($saved_api_configs[$trk_data['trk_carrier']]['key_1']) ? $saved_api_configs[$trk_data['trk_carrier']]['key_1'] : "",
														"pwd" => isset($saved_api_configs[$trk_data['trk_carrier']]['key_2']) ? $saved_api_configs[$trk_data['trk_carrier']]['key_2'] : "",
		  								);
		  	if (isset($trk_data['trk_carrier']) && $trk_data['trk_carrier'] == "UPS") {
		  		$input_data["access"] = isset($saved_api_configs[$trk_data['trk_carrier']]['key_3']) ? $saved_api_configs[$trk_data['trk_carrier']]['key_3'] : "";
		  	}

		  	if (isset($trk_data['trk_carrier']) && $trk_data['trk_carrier'] == "FEDEX") {
		  		$input_data["acc_no"] = isset($saved_api_configs[$trk_data['trk_carrier']]['key_3']) ? $saved_api_configs[$trk_data['trk_carrier']]['key_3'] : "";
		  		$input_data["meter_no"] = isset($saved_api_configs[$trk_data['trk_carrier']]['key_4']) ? $saved_api_configs[$trk_data['trk_carrier']]['key_4'] : "";
		  	}

		  	if (isset($trk_data['trk_carrier']) && $trk_data['trk_carrier'] == "PUROLATOR") {
		  		$input_data["token"] = isset($saved_api_configs[$trk_data['trk_carrier']]['key_3']) ? $saved_api_configs[$trk_data['trk_carrier']]['key_3'] : "";
		  	}

		  	$tracki_url = "https://app.hitshipo.com/tracking_api/hit_tracki.php";
				$response = wp_remote_post( $tracki_url , array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
					'body'        => json_encode($input_data),
					)
				);
				$output = isset($response['body']) ? json_decode($response['body'],true) : [];

				if (isset($general_settings['hit_tracki_debug']) && $general_settings['hit_tracki_debug'] == "yes") {
					echo "<pre>";
					echo "<h3>Request - Json</h3>";
					print_r(json_encode($input_data));
					echo "<h3>Request - Array</h3>";
					print_r($input_data);
					echo "<h3>Response</h3>";
					print_r($output);
					die();
				}

	?>
						<figure class="hit_tracki_fig">
					    <img class="hit_tracki_img" src="<?php echo plugin_dir_url(__FILE__); ?>brands/<?php _e(strtolower($trk_data['trk_carrier']),'hit_tracki') ?>.png" alt="">
					    <figcaption class="hit_tracki_fig_cap">
					      <span class="hit_tracki_fig_cap_h4">Tracking Details:-</span>
					      <span class="hit_tracki_fig_cap_h6">Tracking Number : # <?php _e($trk_data['trk_no'],'hit_tracki') ?> <a href="#" class="edit_address" id="hit_tracki_edit">Edit</a></span>
					    </figcaption>
					  </figure>
					  <div class="order-track">
	<?php
				if (!empty($output) && $output['status'] == "success") {
					foreach ($output['events'] as $event) {
	?>
					  
					    <div class="order-track-step">
					      <div class="order-track-status">
					        <span class="order-track-status-dot"></span>
					        <span class="order-track-status-line"></span>
					      </div>
					      <div class="order-track-text">
					        <span class="order-track-text-stat"><?php _e($event['e_status'].' - '.$event['e_loc'],'hit_tracki')  ?></span><br>
					        <span class="order-track-text-sub"><?php _e($event['e_date'].', '.$event['e_time'],'hit_tracki')  ?></span>
					      </div>
					    </div>
					  
	<?php
					}
				} else {
					$err = isset($output["msg"]) ? $output["msg"] : "No data found. Check whether you have live/production api access and valid tracking number.";
					_e('<center><p style="color:red">'.$err.'</p><center>','hit_tracki');
				}
				echo "</div>";
			}
  ?>
</section>

<script type="text/javascript">
	jQuery("#hit_tracki_edit").click(function(event) {
		jQuery("#hit_tracki_head").show();
	});
</script>