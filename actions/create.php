<?php
if (include("login.php")){
	$temp->content .= '<h1>Enter data for new ' . $class . '</h1><form method="POST" enctype="multipart/form-data">';
	$item = DBItem::getCLASS($class, 0);
	$temp->content .= $item->view("edit", false);
	$temp->content .= '<button type="submit" name="action" value="save">save</button></form>';
	$temp->content .= '<script type="text/javascript">(function(){
		var forms = document.getElementsByTagName("form");
		forms[forms.length - 1].elements[2].select();
	})()</script>';

	if (array_key_exists("id", $_POST) && array_key_exists("action", $_POST) && $_POST["action"] === "save"){
		$item = DBItem::createCLASS($class, DBItemField::parseClass($class)->translateRequestData($_POST[$class][0]));
		if ($item->hasField("creator")){
			$item->creator = $_SESSION["userID"];
		}
		$temp->content .= '<h1>Entry saved.</h1>';
		$temp->content .= $item->view(false, false);
	}
}
?>