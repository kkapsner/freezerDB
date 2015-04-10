<?php
$unassignedEppis = DBItem::getByConditionCLASS("Eppi", "`barcode` IS NULL");
if (count($unassignedEppis)){
	$temp->content .= '<h1>Choose existing ' . $class . '</h1><form method="POST" enctype="multipart/form-data">';
	$temp->content .= '<select name="id">';
	foreach ($unassignedEppis as $eppi){
		$temp->content .= '<option value="' . $eppi->DBid . '">' . $eppi->view("singleLine", false) . '</option>';
	}
	$temp->content .= "</select>";
	$temp->content .= '<input type="hidden" name="barcode" value="' . htmlentities($_POST["barcode"], ENT_QUOTES, "UTF-8") . '">';
	$temp->content .= '<button type="submit" name="action" value="edit">edit</button></form>';
}
?>