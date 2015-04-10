<?php

/* @var $this PlasmidInsertCorrelation */

echo $this->html($this->insert->name) . ": ";

if (!count($this->positions)){
	echo "not found";
}
else {
	foreach ($this->positions as $i => $position){
		if ($i !== 0){
			echo ", ";
		}
		$position->view($context, true, $args);
	}
}
?>