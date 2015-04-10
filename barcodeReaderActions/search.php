<?php

$temp->content .=
	'<form method="POST">Barcode: <input name="barcode" size="6"><button type="submit" name="action" value="search">search</button></form>' .
	'<script type="text/javascript">document.getElementsByTagName("input")[0].select();</script>';

if (array_key_exists("action", $_POST) && $_POST["action"] === "search" && array_key_exists("barcode", $_POST)){
	$db = DB::getInstance();
	$searchResult = DBItem::getByConditionCLASS("Eppi", "`barcode` = " . $db->quote($_POST["barcode"], DB::PARAM_STR));

	if (count($searchResult)){
		$temp->content .= $searchResult[0]->view(false, false);
	}
	else {		
		foreach (DBItemField::parseClass("Eppi") as $field){
			if ($field->name === "barcode"){
				$field->default = $_POST["barcode"];
			}
			
		}

		if (include("login.php")){
			include("barcodeReaderActions/editMask.php");
			include("barcodeReaderActions/createMask.php");
		}
	}
}
?>
