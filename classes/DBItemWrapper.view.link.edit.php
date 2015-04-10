<?php

/* @var $this DBItemWrapper */
echo '<a class="editLink" href="index.php?action=edit&amp;class=' . get_class($this) . '&amp;id=' . $this->DBid . '">';

if (is_string($args)){
	echo $args;
}
else {
	$this->view("singleLine", true);
}

echo '</a>';

?>