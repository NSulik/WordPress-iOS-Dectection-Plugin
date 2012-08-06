<?php
/*
Plugin Name: iOS-SmartBanner-Inserter
Plugin URI: None
Description: The plugin determins whether the user is running iOS 6, iOS 5 or any other operating system. Depending on the result it inserts a smart banner, simulates a smart banner or does nothing respectively.
Version: 1.0
Author: Nicholas Sulik
Author URI: None
License: GPL
*/

/*------------------------------------------------------------------
Action and Hook Registration
------------------------------------------------------------------*/
register_activation_hook(__FILE__,'iOS_SmartBanner_Inserter_install'); 
register_deactivation_hook( __FILE__, 'iOS_SmartBanner_Inserter_remove' );

add_action( 'admin_menu', 'iOS_SmartBanner_Inserter_menu' );
//Action hooked onto 'wp_head' so the banner only appears on the Blog proper an not on the setting or admin pages.
add_action('wp_head','insert_SmartBanner');

/*------------------------------------------------------------------
Plugin Activation and Deactivation scripts (Apple AppStore ID storage for options menu)
------------------------------------------------------------------*/
function iOS_SmartBanner_Inserter_install() {
	//Adds the ad_app_id database field to store the Apple App ID used for the smart Banner
	add_option("ad_app_id", '123456789', '', 'yes');
}

function iOS_SmartBanner_Inserter_remove() {
	//Removed the database field upon plugin deactivation
	delete_option('ad_app_id');
}

/*------------------------------------------------------------------
WordPress Options menu callback
------------------------------------------------------------------*/
function iOS_SmartBanner_Inserter_menu() {
	add_options_page( 'iOS SmartBanner Inserter Options', 'iOS SmartBanner Inserter', 'manage_options', 'iOS-SmartBanner-Inserter', 'iOS_SmartBanner_Inserter_options' );
}

/*------------------------------------------------------------------
Options Menu HTML
Only option available is to change the App ID for the AppStore link.
------------------------------------------------------------------*/
function iOS_SmartBanner_Inserter_options()  {
?>
	<div>
		<h2>iOS SmartBanner Inserter Options</h2>
		<br>

		<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>

		<table>
		<tr valign="center" >
			<td scope="row" align="left" width = "220">
				Enter App Store App ID:
			</th>
			<td>
			<input name="ad_app_id" type="text" id="ad_app_id" value="<?php echo get_option('ad_app_id'); ?>" />
			</td>
		</tr>
		</table>

		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="ad_app_id" />

		<p class="submit">
			<input type="submit" name ="submit" id="submit" class ="button-primary" value="Save Changes" />
		</p>

		</form>
	</div>
	<?php
}

/*------------------------------------------------------------------
iOS Platform and Version Checking Functions
------------------------------------------------------------------*/

//Returns true if site accessed by iOS device
function is_iOS() {

	$isiPod = stripos($_SERVER['HTTP_USER_AGENT'], "ipod");
	$isiPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iphone");
	$isiPad = stripos($_SERVER['HTTP_USER_AGENT'], "ipad");

	if($isiPod || $isiPhone || $isiPad)
		return true;
	else
		return false;
}

//Returns true if user is using an iOS 5.x device
function is_iOS5() {

	if(is_iOS() && stripos($_SERVER['HTTP_USER_AGENT'], "os 5"))
		return true;
	else
		return false;
}

//Returns true if user is using an iOS 6.x device
function is_iOS6() {
	if(is_iOS() && stripos($_SERVER['HTTP_USER_AGENT'], "os 6"))
		return true;
	else
		return false;
}

/*------------------------------------------------------------------
iOS SmartBanner Inserter Callback Function
------------------------------------------------------------------*/

function insert_SmartBanner() {
	if(is_iOS6())
		echo '<meta name="apple-itunes-app" content="app-id=123456789, app-argument=x-sfp:///visit/seal-rocks">';
	else if(is_iOS5())
		echo "iOS 5.x Detected";
}
?>