<?php

/**
 * PHP class dummy
 *
 * @author kkapsner
 */
class Plasmid extends SequenceItem{
	public static $defaultOrder = "name";
	
	/**
	 * 
	 * @param Vector $vector
	 * @param Insert[] $inserts
	 * @return null|Plasmid
	 */
	public static function getByVectorAndInserts($vector, $inserts){
		$foundPlasmid = null;
		foreach ($vector->plasmids as $plasmid){
			if (count($plasmid->inserts) === count($inserts)){
				$missedOne = false;
				foreach ($inserts as $insert){
					if (!$plasmid->inserts->contains($insert, true)){
						$missedOne = true;
						break;
					}
				}
				if (!$missedOne){
					foreach ($plasmid->inserts as $plasmidInsert){
						if (!in_array($plasmidInsert, $inserts, true)){
							$missedOne = true;
							break;
						}
					}
				}
				if (!$missedOne){
					$foundPlasmid = $plasmid;
					break;
				}
			}
		}
		
		return $foundPlasmid;
	}
	
	public function getInsertPositions(){
		$inserts = $this->inserts;
		for ($i = 0; $i < count($inserts); $i += 1){
			foreach ($inserts[$i]->subInserts as $insert){
				if (!$inserts->search($insert->insert, true)){
					$inserts[] = $insert->insert;
				}
			}
		}
		$correlations = array();
		
		foreach ($inserts as $i => $insert){
			$correlations[] = new PlasmidInsertCorrelation($this, $insert);
		}
		
		uasort(
			$correlations,
			create_function(
				'$a,$b',
				'$a = $a->getFirstPosition();
				$b = $b->getFirstPosition();
				if ($a === $b){
					return 0;
				}
				if ($a === false){
					return 1;
				}
				if ($b === false){
					return -1;
				}
				if ($a > $b){
					return 1;
				}
				else {
					return -1;
				}
				'
			)
		);
		
		return Collection::fromArray($correlations, "PlasmidInsertCorrelation");
	}
}

?>
