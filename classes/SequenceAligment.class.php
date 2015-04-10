<?php

/**
 * SequenceAligment definition file
 */

/**
 * Description of SequenceAligment
 *
 * @author kkapsner
 */
class SequenceAligment{
	/**
	 *
	 * @var string
	 */
	private $sequence1;
	
	/**
	 *
	 * @var string
	 */
	private $sequence2;
	
	/**
	 *
	 * @var int
	 */
	private $length1;
	
	/**
	 *
	 * @var int
	 */
	private $length2;
	
	/**
	 *
	 * @var int[][]
	 */
	private $tracking;
	
	/**
	 *
	 * @var double[][]
	 */
	private $scores;
	
	const DOWN = -1;
	const DIAG = 0;
	const LEFT = 1;
	const MATCH = 2;
	const REPLACE = 3;
	
	static public $standardTrackingMatching = array(
		-1 => "|",
		0 => "",
		1 => "-",
		2 => "=",
		3 => "X"
	);


	public function __construct($sequence1, $sequence2){
		$this->sequence1 = $sequence1;
		$this->sequence2 = $sequence2;
		$this->length1 = strlen($sequence1);
		$this->length2 = strlen($sequence2);
		$this->createMatrices();
		$this->initialise();
		$this->run();
	}
	
	/**
	 * Get the score of the aligment.
	 * @return double
	 */
	public function getScore(){
		return $this->scores[$this->length1][$this->length2];
	}
	
	public function getTracking($characterMatching = null){
		$tracking = array();
		$x = $this->length1;
		$y = $this->length2;
		while ($x > 0 && $y > 0){
			$currentTracking = $this->tracking[$x][$y];
			$tracking[] = $currentTracking;
			switch ($currentTracking){
				case self::DOWN:
					$y -= 1;
					break;
				case self::LEFT:
					$x -= 1;
					break;
				case self::DIAG:
				case self::MATCH:
				case self::REPLACE:
					$x -= 1;
					$y -= 1;
					break;
				default:
					throw new LogicException("Invalid tracking.");
			}
		}
		array_reverse($tracking);
		if ($characterMatching){
			$str = "";
			foreach ($tracking as $tr){
				$str .= $characterMatching[$tr];
			}
			return $str;
		}
		return $tracking;
	}
	
	private function weighting($c1, $c2, $x, $y){
		if ($c1 === $c2){
			return 1;
		}
		else {
			if (
				$c1 !== "" &&
				$c2 === "" &&
				(
					(
						$y === 0 &&
						$x < $this->length1 - $this->length2
					) ||
					(
						$y === $this->length2 &&
						$x > $this->length1
					)
				)
			){
				return 0;
			}
			if (
				$c1 === "" &&
				$c2 !== "" &&
				(
					(
						$x === 0 &&
						$y < $this->length2 - $this->length1
					) ||
					(
						$x === $this->length1 &&
						$y > $this->length2
					)
				)
			){
				return 0;
			}
			return -1;
		}
	}
	
	private function createMatrices(){
		$this->tracking = array();
		$this->scores = array();
		for ($x = 0; $x <= $this->length1; $x += 1){
			$this->tracking[$x] = array();
			$this->scores[$x] = array();
			for ($y = 0; $y <= $this->length2; $y += 1){
				$this->tracking[$x][$y] = 0;
				$this->scores[$x][$y] = 0;
			}
		}
	}
	
	private function initialise(){
		$lastScore = $this->scores[0][0];
		for ($x = 1; $x <= $this->length1; $x += 1){
			$lastScore = $lastScore + $this->weighting(
				$this->sequence1[$x - 1], "", $x, 0
			);
			$this->scores[$x][0] = $lastScore;
			$this->tracking[$x][0] = self::LEFT;
		}
		$lastScore = $this->scores[0][0];
		for ($y = 1; $y <= $this->length2; $y += 1){
			$lastScore = $lastScore + $this->weighting(
				"", $this->sequence2[$y - 1], 0, $y
			);
			$this->scores[0][$y] = $lastScore;
			$this->tracking[0][$y] = self::DOWN;
		}
	}
	
	private function run(){
		for ($x = 1; $x <= $this->length1; $x += 1){
			for ($y = 1; $y <= $this->length2; $y += 1){
				$c1 = $this->sequence1[$x - 1];
				$c2 = $this->sequence2[$y - 1];
				
				$directions = array(
					"DOWN" => $this->scores[$x][$y - 1] + $this->weighting("", $c2, $x, $y),
					"DIAG" => $this->scores[$x - 1][$y - 1] + $this->weighting($c1, $c2, $x, $y),
					"LEFT" => $this->scores[$x - 1][$y] + $this->weighting($c1, "", $x, $y)
				);
				asort($directions, SORT_NUMERIC);
				$keys = array_keys($directions);
				$maximalKey = $keys[2];
				$this->scores[$x][$y] = $directions[$maximalKey];
				if ($maximalKey === "DIAG"){
					if ($this->scores[$x][$y] > $this->scores[$x - 1][$y - 1]){
						$maximalKey = "MATCH";
					}
					else {
						$maximalKey = "REPLACE";
					}
				}
				$this->tracking[$x][$y] = constant("SequenceAligment::" . $maximalKey);
			}
		}
	}
}

?>