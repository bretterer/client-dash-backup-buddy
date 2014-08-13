<?php

class backupbuddy_cd {

	/**
	 * Run a BackupBuddy backup. http://getbackupbuddy.com
	 *
	 * ## OPTIONS
	 *
	 * <profile>
	 * : Profile may either specify the profile ID number, "full" to run the first defined Full backup profile, or "db" to run the first defined Database-only backup profile. The first Full and Database-only profiles are always available as they are not user-deletable. To find the profile number, run a backup inside BackupBuddy in WordPress and note the number at the end of the URL (3 in this case): http://...&backupbuddy_backup=3
	 *
	 * [--quiet]
	 * : Suppresses display of status log information from being output to the screen.
	 *
	 * ## EXAMPLES
	 *
	 *     RUN FULL BACKUP:     wp backupbuddy backup full
	 *     RUN PROFILE #3:      wp backupbuddy backup 3
	 *
	 * @synopsis <profile> [--quiet]
	 */
	public static function backup( $profile ) {

		if ( isset( pb_backupbuddy::$options['profiles'][ $profile ] ) ) {
			$profileArray = pb_backupbuddy::$options['profiles'][ $profile ];
		}

		require_once( pb_backupbuddy::plugin_path() . '/classes/backup.php' );
		pb_backupbuddy::$classes['backup'] = new pb_backupbuddy_backup();
		// Set serial ahead of time so can be used by AJAX before backup procedure actually begins.
		$serial = pb_backupbuddy::random_string( 10 );

		$profileArray['backup_mode'] = '1'; // Force classic mode when running under command line.

		if ( ! isset( $assoc_args['quiet'] ) ) {
			define( 'BACKUPBUDDY_WP_CLI', true );
		}

		// Run the backup!
		if ( pb_backupbuddy::$classes['backup']->start_backup_process(
				$profileArray,                      // Profile array.
				'manual',                       // Backup trigger. manual, scheduled
				array(),                        // pre-backup array of steps.
				array(),                        // post-backup array of steps.
				'client-dash',                        // friendly title of schedule that ran this (if applicable).
				$serial,                        // if passed then this serial is used for the backup insteasd of generating one.
				array()                         // Multisite export only: array of plugins to export.
			) !== true
		) {
			return 'Error #435832: Backup failed. See BackupBuddy log for details.';
		}
		return 'Backup completed successfully.  To Download, Please reload this page to refresh the list below.';
	}
}
