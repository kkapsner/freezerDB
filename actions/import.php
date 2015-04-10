<?php

if (include("login.php")){
	$importBase = array_read_key("importBase", $_GET, false);

	if (
		in_array($importBase, $config->IMPORT_BASES) &&
		file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . "import" . DIRECTORY_SEPARATOR . $importBase . ".php")
	){
		include(dirname(__FILE__) . DIRECTORY_SEPARATOR . "import" . DIRECTORY_SEPARATOR . $importBase . ".php");
	}
	else {
		$temp->content .= "<h1>Choose import base</h1><ul>";

		foreach ($config->IMPORT_BASES as $importName => $importBase){
			$temp->content .= "<li><a href=\"?action=import&importBase=" .
				$temp->html($importBase) .
				"\">" .
				$temp->html($importName) .
				"</a></li>";
		}

	}
}

?>
