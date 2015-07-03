<?php

if (include("login.php")){
	if (array_key_exists("id", $_POST) && array_key_exists("action", $_POST)){
		switch ($_POST["action"]){
			case "save":
				$item = DBItem::getCLASS($class, $_POST["id"]);
				$data = DBItemField::parseClass($class)->translateRequestData($_POST[$class][$item->DBid]);

				foreach ($data as $name => $value){
					$item->{$name} = $value;
				}

				$temp->content .= $item->view(false, false);
				break;
			case "delete":
				$item = DBItem::getCLASS($class, $_POST["id"]);
				$line = $item->view("singleLine");
				$item->delete();
				$temp->content = '<h1>' . $line . ' deleted</h1>';
				break;
		}
	}
	else if (array_key_exists("id", $_GET)){
		$item = DBItem::getCLASS($class, $_GET["id"]);
		$temp->content = '<h1>Edit ' . $item->view("singleLine") . '</h1><form method="POST" enctype="multipart/form-data">';
		$temp->content .= $item->view("edit", false);
		$temp->content .= '<button type="submit" name="action" value="save">save</button>' .
			'<button type="submit" name="action" value="delete">delete</button>' .
			'</form>';
	}
	else {
		$temp->content = '<h1>Choose  ' . $class . '</h1><ul>';
		foreach (DBItem::getByConditionCLASS($class, false) as $item){
			$temp->content .= '<li>' .
				$item->view("link.edit", false) .
				'</li>';
		}
		$temp->content .= '</ul>';
	}
}
?>