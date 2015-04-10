<?php
function importInsertFile($path){
	if (!file_exists($path)){
		throw new Exception("File not found.");
	}
	$name = trim(basename($path, ".txt"));
	
	$insert = DBItem::getByConditionCLASS("insert", "`name` = " . DB::getInstance()->quote($name));
	if (count($insert)){
		throw new Exception("Insert already in DB: " . $insert[0]->view("link|singleLine", false));
	}
	
	$insert = DBItem::createCLASS(
		"Insert",
		array(
			"name" => $name,
			"sequence" => file_get_contents($path)
		),
		true
	);
	$insert->save();
	return $insert;
}

function importPlasmidFile($path){
	if (!file_exists($path)){
		throw new Exception("File not found.");
	}
	$name = trim(basename($path, ".txt"));
	
	$plasmid = DBItem::getByConditionCLASS("Plasmid", "`name` = " . DB::getInstance()->quote($name));
	if (count($plasmid)){
		throw new Exception("Plasmid already in DB: " . $plasmid[0]->view("link|singleLine", false));
	}
	
	$parts = explode("_", $name, 2);
	$vector = DBItem::getByConditionCLASS("Vector", "`name` = " . DB::getInstance()->quote($parts[0]));
	if (count($vector)){
		$vector = $vector[0];
		$inserts = new DBItemCollection("Insert");
		if (count($parts) > 1 && trim($parts[1])){
			$insertParts = explode("+", $parts[1]);
			foreach ($insertParts as $insertName){
				$insert = DBItem::getByConditionCLASS("Insert", "`name` = " . DB::getInstance()->quote($insertName));
				if (count($insert)){
					$inserts[] = $insert[0];
				}
				else {
					$insert = Insert::getBioBrick($insertName);
					if ($insert){
						$inserts[] = $insert;
					}
					else {
						throw new Exception("Insert " . $insertName .  " missing");
					}
				}
			}
		}
		
		$plasmid = DBItem::createCLASS(
			"Plasmid",
			array(
				"name" => $name,
				"sequence" => file_get_contents($path),
				"vector" => $vector,
				"inserts" => $inserts
			),
			true
		);
		$plasmid->save();
		
		return $plasmid;
	}
	else {
		throw new Exception("Vector not found.");
	}
}

$temp->content .= "<h1>Import insert files</h1>";
foreach (new DirectoryIterator($config->IMPORT_INSERT_DIRECTORY) as $file){
	if ($file->isFile() && substr($file->getFilename(), 0, 1) !== "."){
		$temp->content .= $temp->html($file->getFilename()) . ": ";
		try {
			$plasmid = importInsertFile($file->getPathname());
			$temp->content .= ": " . $plasmid->view("link|singleLine", false);
			rename($file->getPathname(), $file->getPath() . DIRECTORY_SEPARATOR . "imported" . DIRECTORY_SEPARATOR . $file->getFilename());
		}
		catch (Exception $e){
			$temp->content .= $e->getMessage();
		}
		$temp->content .= "<br>";
	}
}

$temp->content .= "<h1>Import plasmid files</h1>";
foreach (new DirectoryIterator($config->IMPORT_PLASMID_DIRECTORY) as $file){
	if ($file->isFile() && substr($file->getFilename(), 0, 1) !== "."){
		$temp->content .= $temp->html($file->getFilename()) . ": ";
		try {
			$plasmid = importPlasmidFile($file->getPathname());
			$temp->content .= $plasmid->view("link|singleLine", false);
			rename($file->getPathname(), $file->getPath() . DIRECTORY_SEPARATOR . "imported" . DIRECTORY_SEPARATOR . $file->getFilename());
		}
		catch (Exception $e){
			$temp->content .= $e->getMessage();
		}
		$temp->content .= "<br>";
	}
}

?>
