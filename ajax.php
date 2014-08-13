<?php

require( __DIR__ . '/../backupbuddy/classes/core.php' );
require( __DIR__ . '/backup.php' );

$profile_id = $_REQUEST['profile'];

return backupbuddy_cd::backup( $profile_id );