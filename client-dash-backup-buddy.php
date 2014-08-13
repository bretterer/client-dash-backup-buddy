<?php
/*
Plugin Name: Client Dash - Backup Buddy
Description: Backup buddy plugin for Client Dash
Version: 1.1
Author: Brian Retterer
Author URI: http://brianretterer.com
License: GPLv2
*/

/**
 * The main callback for our plugin. This ensures that Client Dash
 * has been loaded first.
 *
 * @since Client Dash - Backup Buddy 1.1
 */
function cd_backupbuddy() {

	if ( ! class_exists( 'ClientDash' ) ) {
		add_action( 'admin_notices', 'cdbb_notice' );

		return;
	}

	/**
	 * Class BackbuddyForClientDash
	 *
	 * Main class for the plugin.
	 *
	 * @since Client Dash - Backup Buddy 1.1
	 *
	 * @package WordPress
	 * @subpackage Client Dash - Backup Buddy
	 */
	class BackbuddyForClientDash extends ClientDash {

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
		private $page = 'Help';

		/**
		 * Constructs the plugin.
		 *
		 * @since Client Dash - Backup Buddy 1.0
		 */
		public function __construct() {

			add_action( 'admin_notices', array( $this, 'notices' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_styles' ) );
			add_action( 'wp_ajax_cd_backup_buddy', array( $this, 'backup_callback' ) );

			$this->add_content_section(
				array(
					'name'     => $this->block_name,
					'page'     => $this->page,
					'tab'      => $this->tab,
					'callback' => array( $this, 'tab_output' )
				)
			);
		}

		/**
		 * Registers all styles.
		 *
		 * @since Client Dash - Backup Buddy 1.0
		 */
		public function register_styles() {

			wp_register_style( $this->pre, plugins_url( 'client-dash-backup-buddy/style.css' ) );
			wp_register_script( $this->pre . '-script', plugins_url( 'client-dash-backup-buddy/script.js' ) );


			wp_enqueue_style( $this->pre );
			wp_enqueue_script( $this->pre . '-script' );
		}

		/**
		 * Displays admin notices.
		 *
		 * @since Client Dash - Backup Buddy 1.0
		 */
		public function notices() {

			if ( ! is_plugin_active( 'backupbuddy/backupbuddy.php' ) ) {
				deactivate_plugins( 'client-dash-backup-buddy/client-dash-backup-buddy.php' );?>
				<div class="error">
					<p><?php echo $this->plugin; ?> requires <a href="http://ithemes.com/purchase/backupbuddy/">Backup
							Buddy</a>.
						Please install and activate <b>Backup Buddy</b> to continue using.</p>
				</div>
			<?php
			}
		}

		/**
		 * Tab output.
		 *
		 * @since Client Dash - Backup Buddy 1.0
		 */
		public function tab_output() {

			require_once( plugin_dir_path( __FILE__ ) . 'tab_content.php' );
		}

		/**
		 * AJAX call for plugin.
		 *
		 * @since Client Dash - Backup Buddy 1.0
		 */
		function backup_callback() {

			global $wpdb; // this is how you get access to the database
			require( __DIR__ . '/backup.php' );

			$profile_id = $_POST['profile'];

			echo backupbuddy_cd::backup( $profile_id );

			die(); // this is required to return a proper result
		}
	}

	// Instantiate the class
	new BackbuddyForClientDash;
}

add_action( 'plugins_loaded', 'cd_backupbuddy' );

/**
 * Notices for if CD is not active
 *
 * @since Client Dash - Backup Buddy 1.1
 */
function cdbb_notice() {

	?>
	<div class="error">
		<p>You have activated a plugin that requires <a href="http://w.org/plugins/client-dash">Client Dash</a>
			version 1.5 or greater.
			Please install and activate <b>Client Dash</b> to continue using.</p>
	</div>
<?php
}