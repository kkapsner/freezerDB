<?php

/* @var $this Eppi */
if ($this->content === "BacteriumWrapper" && $this->bacterium){
	$this->bacterium->view("singleLine", true);
}
else {
	echo $this->html(get_class($this)) . ' #' . $this->DBid;
}

if (strlen($this->barcode)){
	echo " (" . $this->html($this->barcode) . ")";
}

?>