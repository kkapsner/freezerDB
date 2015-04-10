<?php

/**
 * Class for raw sequencen treatmen
 *
 * @author kkapsner
 */
class SequenceItem extends DBItemWrapper{
	public function getUnformatedSequence(){
		$seq = $this->sequence;
		$seq = preg_replace("/(^|\\n|\\r)\\s*>[^\\n\\r]*(?:\\n|\\r|$)/", "$1", $seq);
		$seq = preg_replace("/\\s+/", "", $seq);
		return strToUpper($seq);
	}
	
	public function getSequenceLength(){
		return strlen($this->getUnformatedSequence());
	}
}

?>
