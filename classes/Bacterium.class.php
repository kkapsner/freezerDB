<?php

/**
 * PHP class dummy
 *
 * @author kkapsner
 */
class Bacterium extends DBItemWrapper{
	public static $defaultOrder = "strain";
	
	public function getEppis(){
		DB::getInstance()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		return DBItem::getByConditionCLASS("Eppi", "`id` in (SELECT `id` FROM `BacteriumWrapper` WHERE `bacterium` = " . $this->DBid . ")");
	}
	
	public function getResistances(){
		$resistances = $this->strain->resistances? $this->strain->resistances: array();
		foreach ($this->plasmids as $plasmid){
			$resistances = array_merge($resistances, $plasmid->vector->resistances);
		}
		return array_unique($resistances);
	}
	/**
	 * 
	 * @param Strain $strain
	 * @param Plasmid[] $plasmids
	 * @return Bacteria[]
	 */
	public static function getByStrainAndPlasmids($strain, $plasmids){
		$bacteria = new DBItemCollection("Bacterium");
		foreach ($strain->bacteria as $strainBacterium){
			$missedOne = false;
			foreach ($plasmids as $plasmid){
				if (!$plasmid->bacteria->contains($strainBacterium)){
					$missedOne = true;
					break;
				}
			}
			if (!$missedOne){
				foreach ($strainBacterium->plasmids as $plasmid){
					if (!in_array($plasmid, $plasmids, true)){
						$missedOne = true;
						 break;
					}
				}
				if (!$missedOne){
					$bacteria[] = $strainBacterium;
				}
			}
		}
		
		return $bacteria;
	}
}

?>
