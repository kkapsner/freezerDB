<?php

/* @var $config ConfigFile */

date_default_timezone_set("Europe/Berlin");
$fileName = strftime($config->BACKUP_FILE_FORMAT);

if (array_key_exists("forceBackup", $_GET) || !file_exists($fileName)){
	$dbConfig = new ConfigFile("dbConfig.ini");
	$dbConfig->load();
	exec(
		$config->PATH_TO_MYSQL_DUMP . "mysqldump" .
		" --user " . escapeshellarg($dbConfig->username) .
		" --password=" . escapeshellarg($dbConfig->password) . 
		" freezerDB > " . escapeshellarg($fileName)
	);
}

?>
