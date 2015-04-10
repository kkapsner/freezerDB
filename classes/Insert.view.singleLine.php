<?php

/* @var $this Insert */

if ($context === "singleLine" && $this->biobrickNumber){
	echo $this->html($this->biobrickNumber) . " - ";
}
$this->viewByName(get_parent_class($this), $context, true, $args);
?>
