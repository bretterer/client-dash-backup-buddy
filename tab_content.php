<?php

echo '<h2>Create New Backup</h2>';
echo '<div>';
echo '<div id="server-response"></div>';
foreach ( pb_backupbuddy::$options['profiles'] as $profile_id => $profile ) {
	if ( $profile['type'] == 'defaults' ) {
		continue;
	} // Skip showing defaults here...
	?>
	<div class="profile_item">
		<a class="profile_item_select" data-profile="<?php echo $profile_id; ?>" href="#"
		   title="Create this <?php echo $profile['type']; ?> backup.">

			<span class="profile_text"
			      id="profile_title_<?php echo $profile_id; ?>"><?php echo htmlentities( $profile['title'] ); ?></span>
		</a>
	</div>
<?php
}

echo '</div>';
echo '<div style="clear:both;"></div>';

echo '<h2>Download Backups</h2>';

echo '<p><a id="pb_backupbuddy_downloadimportbuddy" href="' . pb_backupbuddy::ajax_url( 'importbuddy' ) . '" class="button button-primary pb_backupbuddy_get_importbuddy">Download importbuddy.php</a>
This will use the default password set by your website administrator.  If you dont know what the password is, please contact them.</p>';

$backups = backupbuddy_core::backups_list();

include( __DIR__ . '/../backupbuddy/views/_backup_listing.php' );