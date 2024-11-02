<?php
/**
 * @package ClickBank Sale Notification
 * @version 0.120508
 */
/*
Plugin Name: ClickBank Sale Notification
Plugin URI: http://exclusivewp.com/clickbank-sale-notification
Author: Purwedi Kurniawan
Author URI: http://exclusivewp.com
Version: 0.120508
Description:  ClickBank Sale Notification plugin will automatically send you an email notification every time there is a transaction in your clickBank account. 
*/
add_action('admin_notices', 'pk_cbsn_admin_notices');
/**
 * copy notify.php to root folder on plugin activation
 **/
function pk_cbsn_activation(){
	add_option ( 'onlist_status', 0 );
	$file = dirname(__FILE__) . '/notify.php';
	$file = plugin_dir_path( $file ).'/notify.php';
	$root = substr($file, 0, strpos($file,'wp-content')-1);
	$success = copy($file, $root.'/notify.php');	
	add_option('cbns_filepath',$root.'/notify.php');
	if ($success) {
		chmod($root.'/notify.php',0755);
		add_option('cbns_filecopy','1');		
	} else {
		add_option('cbns_filecopy','0');
	}
}
register_activation_hook( __FILE__, 'pk_cbsn_activation' );

/**
 * delete copy og notify.php in the root folder on plugin uninstall/delete
 **/
function pk_cbsn_uninstall(){
	$file = dirname(__FILE__) . '/notify.php';
	$file = plugin_dir_path( $file ).'/notify.php';
	$root = substr($file, 0, strpos($file,'wp-content')-1);
	@unlink($file, $root.'/notify.php');	
}
register_uninstall_hook( __FILE__, 'pk_cbsn_uninstall'  );

/**
 * add new menu into WordPress admin menu
**/ 
add_action('admin_menu','pk_cbsn_admin_menu_hook');
function pk_cbsn_admin_menu_hook(){
    if (function_exists('add_options_page')) {
		add_options_page(
			'ClickBank Sale Notification',
			'ClickBank Sale',
			'manage_options',
			'clickbank-sale-notification',
			'pk_cbsn_admin_menu'
		);
	}
}

/**
 * Init plugin options to white list our options
 **/
add_action('admin_init', 'pk_cbsn_options_init' );
function pk_cbsn_options_init(){
	register_setting( 'cbsn', 'cbsn_email' ); 
	register_setting( 'cbsn', 'cbsn_secret_key', 'pk_cbsn_uppercase' ); 
}
function pk_cbsn_uppercase($input){
	$input = strtoupper($input);
	return $input;
}
/**
 * admin options, set default values if empty
 **/
function pk_cbsn_get_admin_options(){
	$email = get_option('cbsn_email');
	$secret_key = strtoupper(get_option('cbsn_secret_key'));
	if (empty($email)) {
		global $current_user;
		get_currentuserinfo();
		$email = $current_user->user_email;
	}
	update_option('cbsn_email', $email);
	$options = array( 'email' => $email, 'secret_key' => $secret_key );
	return $options;
}
/*
* display admin notice to register the plugin
*/
function pk_cbsn_admin_notices() {
	if ( get_option('cbsn_onlist') < 1 ){		
		echo "<div class='updated'><p>" . sprintf(__('<a href="%s">ClickBank Sale Notification</a> plugin need to be registered before you can use it.'), "options-general.php?page=".basename(plugin_basename(__FILE__))). "</p></div>";
	}
}
/**
 * framework to handle the admin form
**/ 
function pk_cbsn_admin_menu(){
	pk_cbsn_admin_print_header();
	if ( 1 == trim($_GET['onlist']) ){
		update_option('cbsn_onlist', 1);
		echo '<div id="message" class="updated fade"><p><strong>Thank you for registering the plugin.</strong></p></div>';
		pk_cbsn_admin_print_settings();
	} elseif ( 1 == trim($_GET['nothankyou']) || 1 == get_option('cbsn_onlist') ){
		update_option('cbsn_onlist', 1);
		pk_cbsn_admin_print_settings();
	} else {
		include('aweber.php');
	}	
	pk_cbsn_admin_print_footer();	
}
/**
 * print admin header
 **/
function pk_cbsn_admin_print_header(){
?>
<div class = "wrap">
  <div class="icon32" id="icon-options-general"> <br>
  </div>
  <h2>
    <?php _e('ClickBank Sale Notification','cbsn'); ?>
  </h2>
  
<?php
}
/**
 * print admin footer
 **/
function pk_cbsn_admin_print_footer(){
?>
</div>
<?php }
/**
 * print admin page content
**/
function pk_cbsn_admin_print_settings(){
?>
  <div>
    <p> If you think this plugin useful, please consider making a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FYSSQ2JUWTNNG" target="_blank">donation</a> or write a review about it or at least give it a good rating on <a href="http://wordpress.org/extend/plugins/clickbank-sale-notification/" target="_blank">WordPress.org</a>.</p>
  </div>
  
  <?php /* <div>
	<p><?php echo 'File copy status: '. get_option('cbns_filecopy'); ?></p>
	<p><?php echo 'File path: '. get_option('cbns_filepath'); ?></p>
  </div> */ ?>
  
  <div class="postbox-container" style="width: 100%;">
    <div class="metabox-holder">
        <form id="cbsn-options" method="post" action="options.php">
		<?php 
			settings_fields('cbsn'); 
			$options = pk_cbsn_get_admin_options(); 
		?>
          <div id="cbsn_settings" class="postbox">
            <div class="handlediv"> <br />
            </div>
            <h3 class="hndle"> <span> Settings </span></h3>
            <div class="inside">
              <div class="frame">
                <table class="form-table">
                  <tbody>                                               
                    <tr valign="top">
                      <th scope="row"><label for="cbsn_secret_key">
                          <?php _e('ClickBank Secret Key: ','cbsn'); ?>
                        </label></th>
                      <td><input type="text" size="30" name="cbsn_secret_key" id="cbsn_secret_key" value="<?php echo stripslashes($options['secret_key']); ?>"></td>
                    </tr>
                    <tr valign="top">
                      <th scope="row"><label for="cbsn_email">
                          <?php _e('Your Email Address: ','cbsn'); ?>
                        </label></th>
                      <td><input type="text" size="30" name="cbsn_email" id="cbsn_email" value="<?php echo stripslashes($options['email']); ?>"></td>
                    </tr>					

                  </tbody>
                </table>
                <p>
                  <input class="button-primary" type="submit" name="cbsn_save" id="cbsn_save" value="Save Options" />
                </p>
              </div>
            </div>
          </div>
        </form>
    </div>
  </div>
  
  <div><h3>Setup Guide</h3>
  <ol>
  <li>Go to your <a href="http://www.clickbank.com/index.html" target="_blank">ClickBank</a> <strong>Account Settings &raquo; My Site &raquo; Advanced Tools Editor</strong>.</li>
  <li>Click the <i>Edit</i> link in the top right corner of Advanced Tools Editor.</li>
  <li>Create your <i>Secret Key</i> - up to 16 letters/digits ALL CAPS.</li>
  <li>Fill in the <i>Instant Notification URL</i> with: <span style="color:#2e78a5;"><strong><?php echo get_bloginfo("siteurl")."/notify.php"; ?></strong></span></li>
  <li>Use the <i>Secret Key</i> you created before to fill the above setting.</li>
  </ol>
  </div>
<?php 
}
/**
 * check onlist status
 */
function pk_cbsn_onlist(){
	$pk_cbsn_onlist = get_option('cbsn_onlist');
	if ( trim($_GET['onlist']) == 1 ) { 			
		$pk_cbsn_onlist = 1; update_option('cbsn_onlist', $pk_cbsn_onlist);
	} 
	return $pk_cbsn_onlist;
}
?>