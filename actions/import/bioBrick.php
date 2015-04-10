<?php
$brickNumber = trim(array_read_key("brickNumber", $_POST, ""));
if ($brickNumber){
	foreach (explode(",", $brickNumber) as $brickNumber){
		$insert = Insert::getBioBrick(trim($brickNumber));
		if ($insert){
			$temp->content .= $insert->view("link|singleLine", false);
		}
		else {
			$temp->content .= "BioBrick " . $temp->html($brickNumber) . " not found.<br>";
		}
	}
}

$temp->content .= '
<form method="POST">
	BioBrick numbers to import: <input name="brickNumber">
	<button type="submit">import</button>
</form>';
?>