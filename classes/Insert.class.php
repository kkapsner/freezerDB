<?php

/**
 * PHP class dummy
 *
 * @author kkapsner
 */
class Insert extends SequenceItem{
	public static $defaultOrder = "ISNULL(`biobrickNumber`), `biobrickNumber` ASC, `name` ASC";
	
	public static function getBioBrick($brickNumber){
		$insert = DBItem::getByConditionCLASS("Insert", "`biobrickNumber` = " . DB::getInstance()->quote($brickNumber));
		if (count($insert)){
			return $insert[0];
		}
		else {
			try {
				$xmlDoc = @new SimpleXMLElement("http://parts.igem.org/xml/part.BBa_" . $brickNumber, null, true);
			}
			catch (Exception $e){
				return null;
			}
			if ($xmlDoc->part_list[0]->ERROR){
				return null;
			}
			else {
				$part = $xmlDoc->part_list[0]->part[0];
				$insert = DBItem::createCLASS(
					"Insert",
					array(
						"biobrickNumber" => $part->part_short_name,
						"name" => $part->part_short_desc,
						"sequence" => $part->sequences[0]->seq_data
					),
					true
				);
				$insert->save();
				return $insert;
			}
		}
	}
}

?>
