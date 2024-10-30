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
		return;
	}

  if ( (!isset($general_settings['hit_tracki_service'])) || (isset($general_settings['hit_tracki_service']) && $general_settings['hit_tracki_service'] != "yes") ) {
    return;
  }

  if ( (!isset($general_settings['hit_tracki_for_cus'])) || (isset($general_settings['hit_tracki_for_cus']) && $general_settings['hit_tracki_for_cus'] != "yes") ) {
    return;
  }

	$carriers = array("DHL" => "DHL Express",
				"UPS" => "UPS",
				"FEDEX" => "Fedex",
				"CP" => "Canada Post",
				"PUROLATOR" => "Purolator"
			);

	$trk_data = get_option('hit_tracki_values_'.$order_id);
	$trk_data = json_decode($trk_data, true);
  $saved_api_configs = get_option('hit_tracki_carrier_settings');

?>

<style type="text/css">
*,
*::after,
*::before {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
}


.hit_tracki_root {
  padding: 3rem 1.5rem;
  border-radius: 5px;
  /*box-shadow: 0 2rem 6rem rgba(0, 0, 0, 0.3);*/
}

.hit_tracki_fig {
  display: flex;
}
.hit_tracki_fig_img {
  width: 4rem;
  height: 4rem;
  border-radius: 15%;
  border: 1px solid #f05a00;
  margin-right: 1.5rem;
  padding: 10px;
}
.hit_tracki_fig_cap {
  display: flex;
  flex-direction: column;
  justify-content: space-evenly;
}
.hit_tracki_fig_cap_h4 {
  font-size: 1.2rem;
  font-weight: 600;
}
.hit_tracki_fig_cap_h6 {
  font-size: 1rem;
  font-weight: 300;
}
.hit_tracki_fig_cap_h2 {
  font-size: 1rem;
  font-weight: 600;

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
  height: 6rem;
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
  width: 1.5rem;
  height: 1.5rem;
  border-radius: 50%;
  background: #f05a00;
}
.order-track-status-line {
  display: block;
  margin: 0 auto;
  width: 2px;
  height: 6rem;
  background: #f05a00;
}
.order-track-text-stat {
  font-size: 1.2rem;
  font-weight: 500;
  margin-bottom: 3px;
}
.order-track-text-sub {
  font-size: 1rem;
  font-weight: 300;
}

.order-track {
  transition: all 0.3s height 0.3s;
  transform-origin: top center;
}
</style>

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
 ?>

  <section class="hit_tracki_root">
    <figure class="hit_tracki_fig">
      <img class="hit_tracki_fig_img" src="<?php echo plugin_dir_url(__FILE__); ?>brands/<?php _e(strtolower($trk_data['trk_carrier']),'hit_tracki') ?>.png" alt="">
      <figcaption class="hit_tracki_fig_cap">
        <b class="hit_tracki_fig_cap_h4">Tracking Details:-</b>
        <b class="hit_tracki_fig_cap_h2">Track No : #<a class="hit_tracki_fig_cap_h6" href="#" style="pointer-events: none;"><?php echo (isset($trk_data['trk_no'])) ? _e($trk_data['trk_no'],'hit_tracki') : '' ?></a></b>
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
          <b class="order-track-text-stat"><?php _e($event['e_status'].' - '.$event['e_loc'],'hit_tracki') ?></b><br>
          <span class="order-track-text-sub"><?php _e($event['e_date'].', '.$event['e_time'],'hit_tracki') ?></span>
        </div>
      </div>
  <?php
      }
    } else {
      echo '<center><p style="color:red">No Tracking data found at this movement...</p><center>';
    }
  ?>
    </div>
  </section>

 <?php 
	}
 ?>