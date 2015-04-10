<article class="box">
	<h1><?php
		echo $this->html($this->name . " @");
		$this->rack->view("link", true);
		echo $this->html(" - " . $this->column . $this->row);
	?></h1>
	<?php
	if ($this->description){
		echo "<section class=\"description\">" . $this->html($this->description) . "</section>";
	}
	?>
	<table>
		<thead>
			<tr>
				<th></th>
				<th>a</th>
				<th>b</th>
				<th>c</th>
				<th>d</th>
				<th>e</th>
				<th>f</th>
				<th>g</th>
				<th>h</th>
				<th>i</th>
				<th>j</th>
			</tr>
		</thead><?php

		/* @var $this Rack */
		/* @var $eppi Eppi */
		for ($row = 1; $row <= 10; $row += 1){
			echo "<tr>";
			echo "<th>" . $row . "</th>";
			for ($column = 0; $column < 10; $column += 1){
					echo "<td>";
					$eppi = $this->getEppiByPosition($column, $row);
					if ($eppi){
						$eppi->view("link", true);
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
	<?php
	if ($this->image){
		$this->image->view("", true, DBItemField::parseClass("Box")->getFieldByName("image"));
	}
	?>
</article>