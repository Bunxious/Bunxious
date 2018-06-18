//Create snapshots directory in order to
//compensate for permissions on some servers
if (!file_exists(DUPLICATOR_SSDIR_NAME)) {
	mkdir(DUPLICATOR_SSDIR_NAME, 0755);
	DUPX_Log::info("- Created directory ". DUPLICATOR_SSDIR_NAME);
}
$fp = fopen(DUPLICATOR_SSDIR_NAME . '/index.php', 'w');
fclose($fp);
DUPX_Log::info("- Created file ". DUPLICATOR_SSDIR_NAME . '/index.php');



//===============================================
//NOTICES TESTS
//===============================================
DUPX_Log::info("\n====================================");
DUPX_Log::info("NOTICES");
DUPX_Log::info("====================================\n");
$config_vars = array('WPCACHEHOME', 'COOKIE_DOMAIN', 'WP_SITEURL', 'WP_HOME', 'WP_TEMP_DIR');
$config_found = DUPX_U::getListValues($config_vars, $config_file);

//Config File:
if (! empty($config_found)) {
	$msg  = "NOTICE: The wp-config.php has the following values set [" . implode(", ", $config_found) . "]. \n";
	$msg .= 'Please validate these values are correct in your wp-config.php file.  See the codex link for more details: https://codex.wordpress.org/Editing_wp-config.php';
	$JSON['step3']['warnlist'][] = $msg;
	DUPX_Log::info($msg);
}

//Database: 
$result = @mysqli_query($dbh, "SELECT option_value FROM `{$GLOBALS['FW_TABLEPREFIX']}options` WHERE option_name IN ('upload_url_path','upload_path')");
if ($result) {
	while ($row = mysqli_fetch_row($result)) {
		if (strlen($row[0])) {
			$msg  = "NOTICE: The media settings values in the table '{$GLOBALS['FW_TABLEPREFIX']}options' has at least one the following values ['upload_url_path','upload_path'] set. \n";
			$msg .= "Please validate these settings by logging into your wp-admin and going to Settings->Media area and validating the 'Uploading Files' section";
			$JSON['step3']['warnlist'][] = $msg;
			DUPX_Log::info($msg);
			break;
		}
	}
}

if (empty($JSON['step3']['warnlist'])) {
	DUPX_Log::info("No Notices Found\n");
}

$JSON['step3']['warn_all'] = empty($JSON['step3']['warnlist']) ? 0 : count($JSON['step3']['warnlist']);

mysqli_close($dbh);



$ajax2_end = DUPX_U::getMicrotime();
$ajax2_sum = DUPX_U::elapsedTime($ajax2_end, $ajax2_start);
DUPX_Log::info("\nSTEP 3 COMPLETE @ " . @date('h:i:s') . " - RUNTIME: {$ajax2_sum}\n\n");

$JSON['step3']['pass'] = 1;
error_reporting($ajax2_error_level);
die(json_encode($JSON));
?><?php break;

	endswitch;

    @fclose($GLOBALS["LOG_FILE_HANDLE"]);
    die("");

endif;
?>