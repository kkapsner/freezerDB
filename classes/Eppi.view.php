<?php
/* @var $this Eppi */

$this->viewByName(get_parent_class($this), $context, true, $args);
if ($this->content === "BacteriumWrapper"){
	if ($this->bacterium){
		$plasmids = $this->bacterium->plasmids;
		if (count($plasmids)){
			echo "<h2>Plasmids</h2>";
			foreach ($plasmids as $plasmid){
				$plasmid->view("link|singleLine", true);
			}
		}
	}
}
?>
