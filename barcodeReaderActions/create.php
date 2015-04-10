<?php
if (array_key_exists("id", $_POST) && array_key_exists("action", $_POST) && $_POST["action"] === "save"){
	if (include("login.php")){
		$item = DBItem::createCLASS($class, DBItemField::parseClass($class)->translateRequestData($_POST[$class][0]));
		if ($item->hasField("creator")){
			$item->creator = $_SESSION["userID"];
		}
		$_POST["action"] = "search";
		$_POST["barcode"] = $item->barcode;
	}
}
?>