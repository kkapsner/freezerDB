<?php
$temp->content = '<h1>Choose item type</h1><ul>';
foreach ($config->DB_ITEMS as $c){
	$temp->content .= '<li><a href="?action=' . $action . '&amp;class=' . $c . '">' . $c . '</a></li>';
}
$temp->content .= '</ul>';
?>
