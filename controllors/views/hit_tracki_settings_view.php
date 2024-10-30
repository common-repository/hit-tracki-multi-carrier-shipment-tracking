<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

wp_enqueue_script("jquery");

global $woocommerce, $wp_roles;
$error = $success =  '';
$carrier_data = array(
					"DHL" => array("key_2" => "Site Password", "key_1" => "Site ID" ),
					"UPS" => array("key_3" => "Access Key", "key_2" => "Site Password", "key_1" => "Site ID" ),
					"FEDEX" => array( "key_4" => "Meter No", "key_3" => "Acc No", "key_2" => "Site Password", "key_1" => "Site ID", ),
					"CP" => array(  "key_2" => "Site Password", "key_1" => "Site ID", ),
					"PUROLATOR" => array(  "key_3" => "Token", "key_2" => "Site Password", "key_1" => "Site ID", )
				);
$carriers = array("DHL" => "DHL Express",
				"UPS" => "UPS",
				"FEDEX" => "Fedex",
				"CP" => "Canada Post",
				"PUROLATOR" => "Purolator"
			);

$countires =  array(
									'AF' => 'Afghanistan',
									'AL' => 'Albania',
									'DZ' => 'Algeria',
									'AS' => 'American Samoa',
									'AD' => 'Andorra',
									'AO' => 'Angola',
									'AI' => 'Anguilla',
									'AG' => 'Antigua and Barbuda',
									'AR' => 'Argentina',
									'AM' => 'Armenia',
									'AW' => 'Aruba',
									'AU' => 'Australia',
									'AT' => 'Austria',
									'AZ' => 'Azerbaijan',
									'BS' => 'Bahamas',
									'BH' => 'Bahrain',
									'BD' => 'Bangladesh',
									'BB' => 'Barbados',
									'BY' => 'Belarus',
									'BE' => 'Belgium',
									'BZ' => 'Belize',
									'BJ' => 'Benin',
									'BM' => 'Bermuda',
									'BT' => 'Bhutan',
									'BO' => 'Bolivia',
									'BA' => 'Bosnia and Herzegovina',
									'BW' => 'Botswana',
									'BR' => 'Brazil',
									'VG' => 'British Virgin Islands',
									'BN' => 'Brunei',
									'BG' => 'Bulgaria',
									'BF' => 'Burkina Faso',
									'BI' => 'Burundi',
									'KH' => 'Cambodia',
									'CM' => 'Cameroon',
									'CA' => 'Canada',
									'CV' => 'Cape Verde',
									'KY' => 'Cayman Islands',
									'CF' => 'Central African Republic',
									'TD' => 'Chad',
									'CL' => 'Chile',
									'CN' => 'China',
									'CO' => 'Colombia',
									'KM' => 'Comoros',
									'CK' => 'Cook Islands',
									'CR' => 'Costa Rica',
									'HR' => 'Croatia',
									'CU' => 'Cuba',
									'CY' => 'Cyprus',
									'CZ' => 'Czech Republic',
									'DK' => 'Denmark',
									'DJ' => 'Djibouti',
									'DM' => 'Dominica',
									'DO' => 'Dominican Republic',
									'TL' => 'East Timor',
									'EC' => 'Ecuador',
									'EG' => 'Egypt',
									'SV' => 'El Salvador',
									'GQ' => 'Equatorial Guinea',
									'ER' => 'Eritrea',
									'EE' => 'Estonia',
									'ET' => 'Ethiopia',
									'FK' => 'Falkland Islands',
									'FO' => 'Faroe Islands',
									'FJ' => 'Fiji',
									'FI' => 'Finland',
									'FR' => 'France',
									'GF' => 'French Guiana',
									'PF' => 'French Polynesia',
									'GA' => 'Gabon',
									'GM' => 'Gambia',
									'GE' => 'Georgia',
									'DE' => 'Germany',
									'GH' => 'Ghana',
									'GI' => 'Gibraltar',
									'GR' => 'Greece',
									'GL' => 'Greenland',
									'GD' => 'Grenada',
									'GP' => 'Guadeloupe',
									'GU' => 'Guam',
									'GT' => 'Guatemala',
									'GG' => 'Guernsey',
									'GN' => 'Guinea',
									'GW' => 'Guinea-Bissau',
									'GY' => 'Guyana',
									'HT' => 'Haiti',
									'HN' => 'Honduras',
									'HK' => 'Hong Kong',
									'HU' => 'Hungary',
									'IS' => 'Iceland',
									'IN' => 'India',
									'ID' => 'Indonesia',
									'IR' => 'Iran',
									'IQ' => 'Iraq',
									'IE' => 'Ireland',
									'IL' => 'Israel',
									'IT' => 'Italy',
									'CI' => 'Ivory Coast',
									'JM' => 'Jamaica',
									'JP' => 'Japan',
									'JE' => 'Jersey',
									'JO' => 'Jordan',
									'KZ' => 'Kazakhstan',
									'KE' => 'Kenya',
									'KI' => 'Kiribati',
									'KW' => 'Kuwait',
									'KG' => 'Kyrgyzstan',
									'LA' => 'Laos',
									'LV' => 'Latvia',
									'LB' => 'Lebanon',
									'LS' => 'Lesotho',
									'LR' => 'Liberia',
									'LY' => 'Libya',
									'LI' => 'Liechtenstein',
									'LT' => 'Lithuania',
									'LU' => 'Luxembourg',
									'MO' => 'Macao',
									'MK' => 'Macedonia',
									'MG' => 'Madagascar',
									'MW' => 'Malawi',
									'MY' => 'Malaysia',
									'MV' => 'Maldives',
									'ML' => 'Mali',
									'MT' => 'Malta',
									'MH' => 'Marshall Islands',
									'MQ' => 'Martinique',
									'MR' => 'Mauritania',
									'MU' => 'Mauritius',
									'YT' => 'Mayotte',
									'MX' => 'Mexico',
									'FM' => 'Micronesia',
									'MD' => 'Moldova',
									'MC' => 'Monaco',
									'MN' => 'Mongolia',
									'ME' => 'Montenegro',
									'MS' => 'Montserrat',
									'MA' => 'Morocco',
									'MZ' => 'Mozambique',
									'MM' => 'Myanmar',
									'NA' => 'Namibia',
									'NR' => 'Nauru',
									'NP' => 'Nepal',
									'NL' => 'Netherlands',
									'NC' => 'New Caledonia',
									'NZ' => 'New Zealand',
									'NI' => 'Nicaragua',
									'NE' => 'Niger',
									'NG' => 'Nigeria',
									'NU' => 'Niue',
									'KP' => 'North Korea',
									'MP' => 'Northern Mariana Islands',
									'NO' => 'Norway',
									'OM' => 'Oman',
									'PK' => 'Pakistan',
									'PW' => 'Palau',
									'PA' => 'Panama',
									'PG' => 'Papua New Guinea',
									'PY' => 'Paraguay',
									'PE' => 'Peru',
									'PH' => 'Philippines',
									'PL' => 'Poland',
									'PT' => 'Portugal',
									'PR' => 'Puerto Rico',
									'QA' => 'Qatar',
									'CG' => 'Republic of the Congo',
									'RE' => 'Reunion',
									'RO' => 'Romania',
									'RU' => 'Russia',
									'RW' => 'Rwanda',
									'SH' => 'Saint Helena',
									'KN' => 'Saint Kitts and Nevis',
									'LC' => 'Saint Lucia',
									'VC' => 'Saint Vincent and the Grenadines',
									'WS' => 'Samoa',
									'SM' => 'San Marino',
									'ST' => 'Sao Tome and Principe',
									'SA' => 'Saudi Arabia',
									'SN' => 'Senegal',
									'RS' => 'Serbia',
									'SC' => 'Seychelles',
									'SL' => 'Sierra Leone',
									'SG' => 'Singapore',
									'SK' => 'Slovakia',
									'SI' => 'Slovenia',
									'SB' => 'Solomon Islands',
									'SO' => 'Somalia',
									'ZA' => 'South Africa',
									'KR' => 'South Korea',
									'SS' => 'South Sudan',
									'ES' => 'Spain',
									'LK' => 'Sri Lanka',
									'SD' => 'Sudan',
									'SR' => 'Suriname',
									'SZ' => 'Swaziland',
									'SE' => 'Sweden',
									'CH' => 'Switzerland',
									'SY' => 'Syria',
									'TW' => 'Taiwan',
									'TJ' => 'Tajikistan',
									'TZ' => 'Tanzania',
									'TH' => 'Thailand',
									'TG' => 'Togo',
									'TO' => 'Tonga',
									'TT' => 'Trinidad and Tobago',
									'TN' => 'Tunisia',
									'TR' => 'Turkey',
									'TC' => 'Turks and Caicos Islands',
									'TV' => 'Tuvalu',
									'VI' => 'U.S. Virgin Islands',
									'UG' => 'Uganda',
									'UA' => 'Ukraine',
									'AE' => 'United Arab Emirates',
									'GB' => 'United Kingdom',
									'US' => 'United States',
									'UY' => 'Uruguay',
									'UZ' => 'Uzbekistan',
									'VU' => 'Vanuatu',
									'VE' => 'Venezuela',
									'VN' => 'Vietnam',
									'YE' => 'Yemen',
									'ZM' => 'Zambia',
									'ZW' => 'Zimbabwe',
								);
	
	$general_settings = get_option('hit_tracki_main_settings');
	$general_settings = empty($general_settings) ? array() : $general_settings;
	
	function hitshipo_sanitize_array($arr_to_san = []){
		$sanitized_data = [];
		if (!empty($arr_to_san) && is_array($arr_to_san)) {
			foreach ($arr_to_san as $key => $value) {
				$sanitized_data[$key] = sanitize_text_field($value);
			}
		}
		return $sanitized_data;
	}

	if(isset($_POST['save']))
	{
		$general_settings = [];
		$save_c_data = [];
		
		$general_settings['hit_tracki_debug'] = sanitize_text_field(isset($_POST['hit_tracki_debug']) ? 'yes' :'no');
		$general_settings['hit_tracki_for_cus'] = sanitize_text_field(isset($_POST['hit_tracki_for_cus']) ? 'yes' :'no');
		$general_settings['hit_tracki_service'] = sanitize_text_field(isset($_POST['hit_tracki_service']) ? 'yes' :'no');
		$general_settings['hit_tracki_integration_key'] = sanitize_text_field(isset($_POST['hit_tracki_integration_key']) ? $_POST['hit_tracki_integration_key'] :'');

		if (isset($_POST['c_type']) && !empty($_POST['c_type'])) {
			foreach ($_POST['c_type'] as $key => $carrier) {
				if ($carrier != "NONE") {
					if (isset($_POST[$carrier.'_enabled']) && $_POST[$carrier.'_enabled'] == "yes") {
						$save_c_data[$carrier]['enabled'] = sanitize_text_field($_POST[$carrier.'_enabled']);
					}
					if (isset($_POST[$carrier.'_key_1'])) {
						$save_c_data[$carrier]['key_1'] = sanitize_text_field($_POST[$carrier.'_key_1']);
					}
					if (isset($_POST[$carrier.'_key_2'])) {
						$save_c_data[$carrier]['key_2'] = sanitize_text_field($_POST[$carrier.'_key_2']);
					}
					if (isset($_POST[$carrier.'_key_3'])) {
						$save_c_data[$carrier]['key_3'] = sanitize_text_field($_POST[$carrier.'_key_3']);
					}
					if (isset($_POST[$carrier.'_key_4'])) {
						$save_c_data[$carrier]['key_4'] = sanitize_text_field($_POST[$carrier.'_key_4']);
					}
				}
			}
		}
		// boxes
		update_option('hit_tracki_main_settings', $general_settings);
		update_option('hit_tracki_carrier_settings', $save_c_data);
		$success = 'Settings Saved Successfully.';
		
	}

?>
<style>
.notice{display:none;}
#multistepsform {
  width: 80%;
  margin: 50px auto;
  text-align: center;
  position: relative;
}
#multistepsform fieldset {
  background: white;
  text-align:left;
  border: 0 none;
  border-radius: 5px;
  box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
  padding: 20px 30px;
  box-sizing: border-box;
  position: relative;
}
#multistepsform fieldset:not(:first-of-type) {
  display: none;
}
#multistepsform input[type=text], #multistepsform input[type=password], #multistepsform input[type=number], #multistepsform input[type=email], 
#multistepsform textarea {
  padding: 5px;
  width: 95%;
}
#multistepsform input:focus,
#multistepsform textarea:focus {
  border-color: #679b9b;
  outline: none;
  color: #637373;
}
#multistepsform .action-button {
  width: 100px;
  background: #00a0d6;
  font-weight: bold;
  color: #fff;
  transition: 150ms;
  border: 0 none;
  float:right;
  border-radius: 1px;
  cursor: pointer;
  padding: 10px 5px;
  margin: 10px 5px;
}
#multistepsform .action-button:hover,
#multistepsform .action-button:focus {
  box-shadow: 0 0 0 2px #f08a5d, 0 0 0 3px #ff976;
  color: #fff;
}
#multistepsform .fs-title {
  font-size: 15px;
  text-transform: uppercase;
  color: #2c3e50;
  margin-bottom: 10px;
}
#multistepsform .fs-subtitle {
  font-weight: normal;
  font-size: 13px;
  color: #666;
  margin-bottom: 20px;
}
#multistepsform #progressbar {
  margin-bottom: 30px;
  overflow: hidden;
  counter-reset: step;
}
#multistepsform #progressbar li {
  list-style-type: none;
  color: #7e8e93;
  text-transform: uppercase;
  font-size: 9px;
  width: 16.5%;
  float: left;
  position: relative;
}
#multistepsform #progressbar li:before {
  content: counter(step);
  counter-increment: step;
  width: 20px;
  line-height: 20px;
  display: block;
  font-size: 10px;
  color: #fff;
  background: #7e8e93;
  border-radius: 3px;
  margin: 0 auto 5px auto;
}
#multistepsform #progressbar li:after {
  content: "";
  width: 100%;
  height: 2px;
  background: #7e8e93;
  position: absolute;
  left: -50%;
  top: 9px;
  z-index: -1;
}
#multistepsform #progressbar li:first-child:after {
  content: none;
}
#multistepsform #progressbar li.active {
  color: #00a0d6;
}
#multistepsform #progressbar li.active:before, #multistepsform #progressbar li.active:after {
  background: #00a0d6;
  color: white;
}
.insetbox{
	/*box-shadow: inset 2px 2px 15px 10px #f4f4f4;*/
	padding: 10px;
	height: 300px;
	overflow: scroll;
}
.progressbarcenter{
  justify-content: center;
  display: flex;
}
		</style>
<div style="text-align:center;margin-top:20px;"><img src="<?php echo plugin_dir_url(__FILE__); ?>hittracki.png" style="width:150px;"></div>

<?php if($success != ''){
	echo '<form id="multistepsform" method="post"><fieldset>
    <center><h2 class="fs-title" style="line-height:27px;">'. $success .'</h2>
	</center></form>';
}else{
	?>
<!-- multistep form -->
<form id="multistepsform" method="post">
	
  <!-- progressbar -->
  <!-- <ul id="progressbar" class="progressbarcenter"> -->
    <!-- <li class="active">Integration</li> -->
    <!-- <li>Setup</li> -->
    <!-- <li>HITShipo</li> -->

  <!-- </ul> -->
  <?php if($error == ''){

  ?>
  <!-- fieldsets -->
 <fieldset>
    <center><h2 class="fs-title">Setup Tracking and API Information</h2>
	</center>
	<table style="width:100%">
		<tr><td style="padding:10px;"><hr></td></tr>
	</table>
	<div class="insetbox">
	<center>
		<div style="padding:5px;">
			<label style="color:gray;font-size: 14px;">HITShipo - Integration Key</label>
			<br>
			<input class="input-text" type="text" name="hit_tracki_integration_key" style="width: 300px;height: 10px;text-align: center;margin-top: 3px;" value="<?php echo isset($general_settings['hit_tracki_integration_key']) ? $general_settings['hit_tracki_integration_key'] : '' ?>">
		</div>
	<table style="padding-left:10px;padding-right:10px;">
		<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hit_tracki_service" <?php echo (isset($general_settings['hit_tracki_service']) && $general_settings['hit_tracki_service'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray">Enable tracking service</small></span></td>
		<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hit_tracki_for_cus" <?php echo (isset($general_settings['hit_tracki_for_cus']) && $general_settings['hit_tracki_for_cus'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray">Enable tracking for customers</small></span></td>
		<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hit_tracki_debug" <?php echo (isset($general_settings['hit_tracki_debug']) && $general_settings['hit_tracki_debug'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Enable Debug Mode</small></span></td>
	</table>
	</center>
	<table style="width:100%;">
		<tr><td colspan="3" style="padding:10px;"><hr></td></tr>
		<?php
			$saved_api_configs = get_option('hit_tracki_carrier_settings');
			if (empty($saved_api_configs)) {
				echo '<tr><td colspan="3"><center><b style="color:red;"> Please add tracking API configuration(s)</b></center><br></td></tr>';
			}
		?>
		<tr>
			<th style="padding:3px;width:20px;"></th>
			<th style="padding:3px;width:150px;"><?php _e('Carrier','hit_tracki') ?><font style="color:red;">*</font></th>
			<th style="padding:3px;width:600px;" colspan="3"><?php _e('API Credentials','hit_tracki') ?><font style="color:red;">*</font></th>
			<th style="padding:3px;width:20px;"></th>
		</tr>
		<table id="trk_t">
			<tbody id="trk_tbody">
				<?php
					if (!empty($saved_api_configs)) {
						$temp_key = 0;
						foreach ($saved_api_configs as $key => $c_config) {
							if (isset($carriers[$key])) {
								$carrier_type_options = '<option value="' .$key. '" selected>' .$carriers[$key]. '</option>';
							} else {
								$carrier_type_options = '<option value="NONE" selected>None</option>';
							}
									
				?>
							<tr id="c_tr_id_<?php _e($temp_key, 'hit_tracki') ?>" style="vertical-align:bottom;text-align:center;">
								<td style="padding:3px;width:20px;vertical-align:middle;" class="check-column"><input type="checkbox"/></td>
								<input type="hidden" size="1" name="c_id[<?php _e($temp_key, 'hit_tracki') ?>]" value="c_id<?php _e($temp_key, 'hit_tracki') ?>"/>
								<td style="padding:3px;" id="c_td_id_<?php _e($temp_key, 'hit_tracki') ?>"><label>Choose carrier</label><br/><select class="wc-enhanced-select" style="width:150px;height:10px;" onchange="changepacktype(this)" name="c_type[<?php _e($temp_key, 'hit_tracki') ?>]" > <?php _e($carrier_type_options, 'hit_tracki') ?></select></td>
		        	
		    <?php 
		    			if (isset($carrier_data[$key])) {
		    				$chkd = (isset($c_config['enabled']) && $c_config['enabled'] == "yes") ? 'checked = "true"' : '';
		    ?>
		    				<td class="c_tr_id_<?php _e($temp_key, 'hit_tracki') ?>" style="padding:3px;width:20px;vertical-align:top;"><label>Enabled</label><br/><input type="checkbox" name="<?php _e($key, 'hit_tracki') ?>_enabled" value="yes" <?php _e($chkd, 'hit_tracki') ?>></td>
		    <?php
		    				foreach (array_reverse($carrier_data[$key]) as $c_api_d_key => $c_api_d) {
		    					if (isset($c_config[$c_api_d_key])) {
		    ?>
		    						<td class="c_tr_id_<?php _e($temp_key, 'hit_tracki') ?>" style="padding:3px;"><label><?php _e($c_api_d, 'hit_tracki') ?></label><br/><input type="text" name="<?php _e($key.'_'.$c_api_d_key, 'hit_tracki') ?>" style="width:150px;height:10px;" value="<?php _e($c_config[$c_api_d_key], 'hit_tracki') ?>"></td>
		    <?php 
		    					} else {
		    ?>
		    						<td class="c_tr_id_<?php _e($temp_key, 'hit_tracki') ?>" style="padding:3px;"><label><?php _e($c_api_d, 'hit_tracki') ?></label><br/><input type="text" name="<?php _e($key.'_'.$c_api_d_key, 'hit_tracki') ?>" style="width:150px;height:10px;"></td>
		    <?php
		    					}
		    				}
		    			}
		     ?>
							</tr>
				<?php
							$temp_key ++;
						}
					}
				?>
			</tbody>
		</table>
		<tfoot>
			<tr>
				<th colspan="6">
					<br>
					<a href="#" class="button button-secondary" id="add_trk_config"><?php _e('Add Track Config','hit_tracki') ?></a>
					<a href="#" class="button button-secondary" id="remove_trk_config"><?php _e('Remove Track Config(s)','hit_tracki') ?></a>
				</th>
			</tr>
		</tfoot>
	</table>
	</div>
	<table style="width:100%">
		<tr><td style="padding:10px;"><hr></td></tr>
	</table>
	<input type="submit" name="save" class="action-button" style="width:auto;" value="Save Changes" />
  </fieldset>


 <?php
  }
  ?>
	
</form>
<center><a href="https://app.hitshipo.com/support" target="_blank" style="width:auto;">Trouble in configuration? / not working? Contact us.</a></center>
<?php } ?>
		<script>
			var current_fs, next_fs, previous_fs;
var left, opacity, scale;
var animating;
jQuery(".next").click(function () {
  if (animating) return false;
  animating = true;

  current_fs = jQuery(this).parent();
  next_fs = jQuery(this).parent().next();
  jQuery("#progressbar li").eq(jQuery("fieldset").index(next_fs)).addClass("active");
  next_fs.show();
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; 
  current_fs.animate(
    { opacity: 0 },
    {
      step: function (now, mx) {
        scale = 1 - (1 - now) * 0.2;
        left = now * 50 + "%";
        opacity = 1 - now;
        current_fs.css({
          transform: "scale(" + scale + ")"});
        next_fs.css({ left: left, opacity: opacity });
      },
      duration: 0,
      complete: function () {
        current_fs.hide();
        animating = false;
      },
      //easing: "easeInOutBack"
    }
  );
});

jQuery(".previous").click(function () {
  if (animating) return false;
  animating = true;

  current_fs = jQuery(this).parent();
  previous_fs = jQuery(this).parent().prev();
  jQuery("#progressbar li")
    .eq(jQuery("fieldset").index(current_fs))
    .removeClass("active");

  previous_fs.show();
  current_fs.animate(
    { opacity: 0 },
    {
      step: function (now, mx) {
        scale = 0.8 + (1 - now) * 0.2;
        left = (1 - now) * 50 + "%";
        opacity = 1 - now;
        current_fs.css({ left: left });
        previous_fs.css({
          transform: "scale(" + scale + ")",
          opacity: opacity
        });
      },
      duration: 0,
      complete: function () {
        current_fs.hide();
        animating = false;
      },
      //easing: "easeInOutBack"
    }
  );
});

	jQuery(".submit").click(function () {
	  return false;
	});

	jQuery(document).ready(function(){
		var carriers = <?php echo json_encode($carriers); ?>;
		jQuery('#add_trk_config').click( function() {
			var carrier_type_options = '<option value="NONE" selected="selected">None</option>';
			jQuery.each(carriers, function(key, val) {
				carrier_type_options = carrier_type_options + '<option value="' +key+ '">'+val+'</option>';
			});
			var tbody = jQuery('#trk_t').find('#trk_tbody');
			var size = tbody.find('tr').size();
			var code = '<tr id="c_tr_id_' + size + '" style="vertical-align:bottom;text-align:center;">\
				<td style="padding:3px;width:20px;vertical-align:middle;" class="check-column"><input type="checkbox"/></td>\
				<input type="hidden" size="1" name="c_id[' + size + ']" value="c_id' + size + '"/>\
				<td style="padding:3px;" id="c_td_id_' + size + '"><label>Choose carrier</label><br/><select class="wc-enhanced-select" style="width:150px;height:10px;" onchange="changepacktype(this)" name="c_type[' + size + ']" >' + carrier_type_options + '</select></td>\
		        </tr>';
			tbody.append( code );
			return false;
		});

		jQuery('#remove_trk_config').click(function() {
			var tbody = jQuery('#trk_t').find('#trk_tbody');
			tbody.find('.check-column input:checked').each(function() {
				jQuery(this).closest('tr').remove().find('input').val('');
			});
			return false;
		});

	});

	function changepacktype(selectbox){
		//get table id of tr, td and carrier type
		var t_r = jQuery(selectbox).closest('tr').attr('id');
		var t_d = jQuery(selectbox).closest('td').attr('id');
		var carrier_type = selectbox.value;

		//Remove previous td added for carrier if available with its class
		jQuery('.' + t_r ).remove();
		//Add td based on carrier type
		var carrier_data = <?php echo json_encode($carrier_data); ?>;
		var c_type_data = carrier_data[carrier_type] ? carrier_data[carrier_type] : [];
		
		jQuery.each(c_type_data, function(key, val) {
			jQuery('#' + t_d ).after('<td class="' + t_r + '" style="padding:3px;"><label>' +val+ '</label><br/><input type="text" name="' +carrier_type+ '_' +key+ '" style="width:150px;height:10px;"></td>');
		});
		jQuery('#' + t_d ).after('<td class="' + t_r + '" style="padding:3px;width:20px;vertical-align:top;"><label>Enabled</label><br/><input type="checkbox" name="' +carrier_type+ '_enabled"></td>');

	}
</script>