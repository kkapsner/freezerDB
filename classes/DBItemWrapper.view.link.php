<?php

/* @var $this DBItemWrapper */
echo '<a href="index.php?action=show&amp;class=' . get_class($this) . '&amp;id=' . $this->DBid . '">';

if (is_string($args)){
	echo $args;
}
else {
	$this->view("singleLine", true, $args);
}

echo '</a>';

?>