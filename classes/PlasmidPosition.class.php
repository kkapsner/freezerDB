<?php

/**
 * PlasmidPosition definition file
 */

/**
 * Description of PlasmidPosition
 *
 * @author kkapsner
 */
class PlasmidPosition extends ViewableHTML{
	/**
	 *
	 * @var int
	 */
	public $start;
	
	/**
	 *
	 * @var int
	 */
	public $end;
	
	/**
	 *
	 * @var double
	 */
	public $score;
	
	public function __construct($start, $end, $score = 1){
		$this->start = $start;
		$this->end = $end;
		$this->score = $score;
	}
	
	public static function getPositions(Plasmid $plasmid, Insert $insert){
		$positions = new Collection("PlasmidPosition");
		$plasmidSequence = $plasmid->getUnformatedSequence();
		$insertSequence = $insert->getUnformatedSequence();
		if ($insert->biobrickNumber){
			$insertSequence = preg_replace(
				"/gaattcgcggccgcttctag(?:ag)?|tactagtagcggccgctgcag$/",
				"",
				$insertSequence
			);
		}
		$insertLength = strlen($insertSequence);
		if ($insertLength === 0){
			return $positions;
		}
		$extendedPlasmidSequence = $plasmidSequence . substr($plasmidSequence, 0, $insertLength - 1);
		$pos = -1;
		while (($pos = strpos($extendedPlasmidSequence, $insertSequence, $pos + 1)) !== false){
			$positions[] = new PlasmidPosition($pos, $pos + $insertLength, 1);
		}
		
		if (!count($positions)){
			$max = 0;
			$maxPos = 0;
			for ($i = strlen($plasmidSequence) - 1; $i >= 0; $i -= 1){
				$c = 0;
				for ($j = 0; $j < $insertLength; $j += 1){
					if ($insertSequence[$j] === $extendedPlasmidSequence[$j + $i]){
						$c += 1;
					}
				}
				if ($c >= $max){
					$max = $c;
					$maxPos = $i;
				}
			}
			$score = $max / $insertLength;
			if ($score > 0.75){
				$positions[] = new PlasmidPosition($maxPos, $maxPos + $insertLength, $score);
			}
		}
		return $positions;
	}
}

?>
