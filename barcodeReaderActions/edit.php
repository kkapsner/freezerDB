<?php
if (array_key_exists("id", $_POST) && array_key_exists("action", $_POST) && $_POST["action"] === "edit"){
	if (include("login.php")){
			$item = DBItem::getCLASS($class, $_POST["id"]);
			$item->barcode = array_read_key("barcode", $_POST, NULL);
			$item->save();

			$_POST["action"] = "search";
	}
}
?>