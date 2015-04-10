<?php

/* @var $this DBItemWrapper */
#echo '<span title="' . $this->html(get_class($this)) . ' #' . $this->DBid . '">';
if ($this->hasField("name") && strlen($this->name)){
	echo $this->html($this->name);
}
else {
	echo $this->html(get_class($this)) . ' #' . $this->DBid;
}
#echo '</span>';

?>