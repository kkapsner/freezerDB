<?php

/* @var $this Bacterium */
#echo '<span title="' . $this->html(get_class($this)) . ' #' . $this->DBid . '">';
if ($this->strain){
	$this->strain->view("singleLine", true);
	if ($this->competent !== "none"){
		echo " - " . $this->html($this->competent) . " competent";
	}
	if (count($this->plasmids)){
		echo " - ";
		foreach ($this->plasmids as $idx => $plasmid){
			if ($idx !== 0){
				echo ",";
			}
			echo " ";
			$plasmid->view("singleLine", true);
		}
	}
}
else {
	echo $this->html(get_class($this)) . ' #' . $this->DBid;
}
#echo '</span>';

?>