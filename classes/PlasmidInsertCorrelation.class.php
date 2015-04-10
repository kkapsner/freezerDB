<?php

class PlasmidInsertCorrelation extends ViewableHTML {
	/**
	 *
	 * @var Plasmid
	 */
	public $plasmid;
	
	/**
	 *
	 * @var Insert
	 */
	public $insert;
	
	/**
	 *
	 * @var PlasmidPosition[]
	 */
	public $positions;
	/**
	 * 
	 * @param Plasmid $plasmid
	 * @param Insert $insert
	 */
	function __construct(Plasmid $plasmid, Insert $insert){
		$this->plasmid = $plasmid;
		$this->insert = $insert;
		$this->positions = PlasmidPosition::getPositions($plasmid, $insert);
	}
	
	public function getFirstPosition(){
		if (count($this->positions)){
			return $this->positions[0]->start;
		}
		else {
			return false;
		}
	}
}


?>