<ul><?php

foreach ($this as $correlation){
	echo "\n\t<li>";
	$correlation->view("", true, $args);
	echo "</li>";
}

?>
</ul>
