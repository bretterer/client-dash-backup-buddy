<?php
/*
Plugin Name: Client Dash - Backup Buddy
Description: Backup buddy plugin for Client Dash
Version: 1.0.0
Author: Brian Retterer
Author URI: http://brianretterer.com
License: GPLv2
*/

class BackbuddyForClientDash {

	/*
	* These variables you can change
	*/
	// Define the plugin name
	private $plugin = 'Client Dash - Backup Buddy';
	// Setup your prefix
	private $pre = 'cdbb';
	// Set this to be name of your content block
	private $block_name = 'Backup Buddy';
	// Set the tab slug and name
	private $tab = 'Backups';
	// Set this to the page you want your tab to appear on (account, help and reports exist in Client Dash)
	private $page = 'help';

	

	/*
	* This constructor function sets up what happens when the plugin
	* is activated. It is where you'll place all your actions, filters
	* and other setup components.
	*/
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'notices' ) );
		add_action( 'plugins_loaded', array( $this, 'content_block' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_styles' ) );
	}

	public function content_block() {
		cd_content_block( $this->block_name, $this->page, $this->tab, array( $this, 'tab_contents' ) );
	}

	

	public function register_styles() {
		wp_register_style( $this->pre , plugins_url('client-dash-backup-buddy/style.css') );
		wp_register_script( $this->pre . '-script', plugins_url('client-dash-backup-buddy/script.js') );


		wp_enqueue_style( $this->pre );
		wp_enqueue_script( $this->pre . '-script');
	}

	// Notices for if CD is not active (no need to change)
	public function notices() {
		if ( !is_plugin_active( 'client-dash/client-dash.php' ) ) { ?>
		<div class="error">
			<p><?php echo $this->plugin; ?> requires <a href="http://w.org/plugins/client-dash">Client Dash</a>.
			Please install and activate <b>Client Dash</b> to continue using.</p>
		</div>
		<?php
		}

		if ( !is_plugin_active( 'backupbuddy/backupbuddy.php' ) ) { 
			deactivate_plugins( 'client-dash-backup-buddy/client-dash-backup-buddy.php' );?>
		<div class="error">
			<p><?php echo $this->plugin; ?> requires <a href="http://ithemes.com/purchase/backupbuddy/">Backup Buddy</a>.
			Please install and activate <b>Backup Buddy</b> to continue using.</p>
		</div>
		<?php
		}
	}

	// Insert the tab contents
	public function tab_contents() {
		include(  __DIR__ . '/tab_content.php' );
	}
}
// Instantiate the class
$backbuddyForClientDash = new BackbuddyForClientDash;




add_action( 'wp_ajax_my_action', 'cd_backup_callback' );

function cd_backup_callback() {
	global $wpdb; // this is how you get access to the database
	require( __DIR__ . '/backup.php');

	$profile_id = $_POST['profile'];

	echo backupbuddy_cd::backup($profile_id);

	die(); // this is required to return a proper result
}