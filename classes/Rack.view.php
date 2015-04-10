<article class="rack" data-name="<?php echo $this->html($this->name);?>"><?php
/* @var $this Rack */

if ($this->description){
	echo "<section class=\"description\">" . $this->html($this->description) . "</section>";
}

if ($this->color){
	$color = explode("|", $this->color);
	echo "<style>.rack[data-name=\"" . $this->html($this->name) . "\"] table th {";
	echo "background-color: " . $this->html($color[0]) . ";";
	if (count($color) > 1){
		echo "color: " . $this->html($color[1]) . ";";
	}
	echo "</style>";
}


?>
	<h1><?php echo $this->html($this->name);?></h1>
	<table>
		<thead>
			<tr>
				<th></th>
				<th>A</th>
				<th>B</th>
				<th>C</th>
				<th>D</th>
			</tr>
		</thead><?php

		/* @var $box Box */
		for ($row = 1; $row <= 6; $row += 1){
			echo "<tr>";
			echo "<th>" . $row . "</th>";
			for ($column = 0; $column < 4; $column += 1){
					echo "<td>";
					$box = $this->getBoxByPosition($column, $row);
					if ($box){
						$box->view("link", true);
					}
					else {
						echo "<i>empty</i>";
					}
					echo "</td>";
			}
			echo "</tr>";
		}
		?>
	</table>
</article>