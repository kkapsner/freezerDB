<?php
$requiredColumns = array("Barcode", "Box", "Position", "Strain", "Vector", "Insert");

$temp->content .= "
	<h1>Import eppi file</h1>
	<form method=\"POST\" enctype=\"multipart/form-data\">
		<input
			class=\"csvFile\"
			data-header=\"" . $temp->html(json_encode($requiredColumns)) . "\"
			type=\"file\"
			name=\"eppiFile\"
		>
		<br>
		<button type=\"submit\">
			import
		</button>
	</form>
";

if (
	array_key_exists("eppiFile", $_POST) &&
	array_key_exists("eppiFile", $_FILES) &&
	$_FILES["eppiFile"]["error"] === UPLOAD_ERR_OK &&
	preg_match("/\\.csv$/", $_FILES["eppiFile"]["name"])
){
	$temp->content .= "<h2>importing " . $temp->html($_FILES["eppiFile"]["name"]) . "</h2><ol>";
	
	
	$csvReader = new CSVReader();
	$map = array();
	foreach ($requiredColumns as $col){
		$map[$col] = $col;
	}
	if (array_key_exists("eppiFile", $_POST) && is_array($_POST["eppiFile"])){
		$csvReader->separator = array_read_key("separator", $_POST["eppiFile"], ",");
		
		if (array_key_exists("columnNames", $_POST["eppiFile"]) && is_array($_POST["eppiFile"]["columnNames"])){
			foreach ($requiredColumns as $col){
				$map[$col] = array_read_key($col, $_POST["eppiFile"]["columnNames"], $col);
			}
		}
	}
	
	foreach ($csvReader->parse(file_get_contents($_FILES["eppiFile"]["tmp_name"]), true) as $eppi){
		$temp->content .= "<li>";
		if (
			$eppi[$map["Barcode"]] &&
			preg_match("/^\s*([a-z]+)\s*(\d+)\s*$/i", $eppi[$map["Position"]], $positionMatches)
		){
			$eppis = DBItem::getByValueCLASS("Eppi", "barcode", $eppi[$map["Barcode"]]);
			if (count($eppis)){
				$temp->content .= "Eppi  already in DB: " . $eppis[0]->view("link|singleLine", false) . "<br>";
				continue;
			}
			/* @var $box Box */
			
			$box = DBItem::getByValueCLASS("Box", "name", $eppi[$map["Box"]]);
			if (count($box)){
				$box = $box[0];
	//			$temp->content .= "Box " . $box->view("link|singleLine", false);
				if ($box->getEppiByPosition(
					strToLower($positionMatches[1]),
					$positionMatches[2]
				)){
					$box = null;
					$temp->content .= "Box position  " . $temp->html($eppi[$map["Position"]]) . " already occupied.<br>";
				}
			}
			else {
				$box = null;
				$temp->content .= "Box " . $eppi[$map["Box"]] . " not found.<br>";
			}
			
			$vectors = array();
			$vectorError = false;
			foreach (explode(",", $eppi[$map["Vector"]]) as $vectorName){
				$vectorName = trim($vectorName);
				if (strlen($vectorName)){
					$vector = DBItem::getByValueCLASS("Vector", "name", $vectorName);
					if (count($vector)){
						$vectors[] = $vector[0];
					//	$temp->content .= "Vector " . $vector->view("link|singleLine", false);
					}
					else {
						$vectorError = true;
						$temp->content .= "Vector " . $vectorName . " not found<br>";
					}
				}
			}

			$strain = DBItem::getByValueCLASS("Strain", "name", $eppi[$map["Strain"]]);
			if (count($strain)){
				$strain = $strain[0];
	//			$temp->content .= " - Strain " . $strain->view("link|singleLine", false);
			}
			else {
				$strain = null;
				$temp->content .= "Strain " . $eppi[$map["Strain"]] . " not found<br>";
			}

			$insertSets = array();
			$insertError = false;
			foreach (explode(",", preg_replace("/\\(\\d+\\)\\s*/", "", $eppi[$map["Insert"]])) as $insertNames){
				$insertNames = trim($insertNames);
				$inserts = array();
				foreach (explode("+", $insertNames) as $insertName){
					$insertName = trim($insertName);
					if (strlen(trim($insertName))){
						$insert = DBItem::getByValueCLASS("Insert", "name", trim($insertName));
						if (count($insert)){
							$insert = $insert[0];
						}
						else {
							$insert = Insert::getBioBrick($insertName);
						}
						if ($insert){
							$inserts[] = $insert;
			//				$temp->content .= " . Insert " . $insert->view("link|singleLine", false);
						}
						else {
							$insertError = true;
							$temp->content .= "Insert " . $insertName . " not found.<br>";
						}
					}
				}
				$insertSets[] = $inserts;
			}
			if (count($vectors) === 0 && count($insertSets) === 1 && count($insertSets[0]) === 0){
				$insertSets = array();
			}
			if (count($vectors) !== count($insertSets)){
				$vectorError = $insertError = true;
				$temp->content .= "Number of vectors not equal to number of insert sets.<br>";
			}

			if (!$vectorError && !$insertError){
				$plasmids = array();
				$plasmidError = false;
				foreach ($vectors as $idx => $vector){
					$plasmid = Plasmid::getByVectorAndInserts($vector, $insertSets[$idx]);
					if ($plasmid){
						$plasmids[] = $plasmid;
					}
					else {
						$temp->content .=  "Plasmid not found.<br>";
						$plasmidError = true;
					}
				}
				
				if ($strain && !$plasmidError){
					$bacteria = Bacterium::getByStrainAndPlasmids($strain, $plasmids);

					$bacterium = null;
					if (count($bacteria)){
						if (count($bacteria) === 1){
							$bacterium = $bacteria[0];
	//						$temp->content .= " - bacterium in DB " . $bacterium->view("link|singleLine", false);
						}
						else {
							$temp->content .= "Multiple bacteria in DB:" . $bacteria->view("link|singleLine", false);
						}
					}
					else {
						if (count($plasmids)){
							$bacterium = DBItem::createCLASS(
								"Bacterium",
								array(
									"comment" => "created by eppi import",
									"strain" => $strain,
									"plasmids" => DBItemCollection::fromArray($plasmids)
								),
								true
							);
							$bacterium->save();

							$temp->content .= "Bacterium created " . $bacterium->view("link|singleLine", false) . ".<br>";
						}
						else {
							$temp->content .= "Bacterium without plasmid can not be created.<br>";
						}
					}

					if ($box && $bacterium){
						$newEppi = DBItem::createCLASS(
							"Eppi",
							array(
								"barcode" => $eppi[$map["Barcode"]],
								"creator" => $_SESSION["userID"],
								"box" => $box,
								"column" => strToLower($positionMatches[1]),
								"row" => $positionMatches[2],
								"content" => "BacteriumWrapper",
								"bacterium" => $bacterium
							),
							true
						);
						$newEppi->save();
						$temp->content .= "Imported Eppi " . $newEppi->view("link|singleLine", false) . ".<br>";
					}
				}
			}
		}
		else {
			$temp->content .= "<i>ignored</i>";
		}
		$temp->content .= "</li>";
	}
	$temp->content .= "</ol>";
}
?>
