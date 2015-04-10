<?php

/* @var $this PlasmidPosition */

echo $this->html($this->start . " ... " . $this->end);

if ($this->score < 1){
	echo sprintf(" (score %0.2f)", $this->score);
}

?>
