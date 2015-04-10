<?php
if ($this->content === "BacteriumWrapper"){
?><tr>
	<td>iGEM&nbsp;Distribution, partsregistry</td>
	<td>1</td>
	<td><?php
	if ($this->bacterium){
		$this->bacterium->view(
			"link",
			true,
			$this->html(
				$this->bacterium->strain->species .
				" " .
				$this->bacterium->strain->name
			)
		);
	}
	?></td>
	<td><?php
	if ($this->bacterium){
		echo $this->html($this->bacterium->risk);
	}
	?></td>
	<td><?php
	if ($this->bacterium){
		foreach ($this->bacterium->plasmids as $idx => $plasmid){
			if ($idx !== 0){
				echo ", ";
			}
			$plasmid->vector->view("link|singleLine", true);
		}
	}
	?></td>
	<td><?php
	if ($this->bacterium){
		$first = true;
		foreach ($this->bacterium->plasmids as $plasmid){
			foreach ($plasmid->inserts as $insert){
				if (!$first){
					echo ", ";
				}
				$first = false;
				$insert->view(
					"link",
					true,
					$this->html($insert->name)
				);
			}
		}
	}
	?></td>
	<td><?php
		if ($this->bacterium && count($this->bacterium->plasmids) && count($this->bacterium->plasmids[0]->inserts)){ 
	?>1)&nbsp;gering <br> 2)&nbsp;PCR-Produkt<?php
		}
	?></td>
	<?php
	$name = "";
	if ($this->bacterium){
		$name = $this->bacterium->strain->stem;
		foreach ($this->bacterium->plasmids as $plasmid){
			$name .=  "_" . $plasmid->vector->name;
			if ($plasmid->inserts){
				foreach ($plasmid->inserts as $insert){
					$name .= "|" . $insert->name;
				}
			}
		}
	}
	?><td data-value="<?php echo $this->html($name);?>"><?php echo $this->html($name);?></td>
	<td>1</td>
	<td data-value="<?php
	echo $this->html($this->createDate);
	?>"><?php
	echo $this->html($this->createDate);
	?></td>
	<td></td>
	<?php
		$creator = "";
		if ($this->creator){
			$uidNumber = $this->creator;
			global $ldap;
			if ($ldap->isBound()){
				$user = LDAPUser::getById($uidNumber);
				if ($user){
					$creator = $user->view("singleLine", false);
				}
			}
			else {
				$creator = "LDAP server not available";
			}
		}
	?><td data-value="<?php echo $creator;?>"><?php echo $creator;?></td>
	<td><?php
	if ($this->bacterium){
		$first = true;
		foreach ($this->bacterium->plasmids as $plasmid){
			if (!$first){
				echo ", ";
			}
			$first = false;
			$plasmid->view("link", true, "open");
		}
	}
	?></td>
	<?php
	$resistances = "";
	if ($this->bacterium){
		$resistances =implode(", ", $this->bacterium->resistances);
	}
	?><td data-value="<?php echo $this->html($resistances)?>"><?php echo $this->html($resistances)?></td>
	<?php
	$ori = "";
	if ($this->bacterium){
		$oris = array();
		foreach ($this->bacterium->plasmids as $idx => $plasmid){
			if ($plasmid->vector->oriR){
				$oris = array_merge($oris, $plasmid->vector->oriR);
			}
		}
		$ori = implode(", ", array_unique($oris));
	}
	?><td data-value="<?php echo $this->html($ori);?>"><?php echo $this->html($ori);?></td>
	<td data-value="<?php echo $this->html($this->barcode);?>"><?php echo $this->html($this->barcode);?></td>
	<td data-value="<?php
	if ($this->box){
		if ($this->box->rack){
			echo $this->html($this->box->rack->name);
		}
	}
	?>"><?php
	if ($this->box){
		if ($this->box->rack){
			$this->box->rack->view("link", true);
			echo $this->html(
				" " .
				$this->box->column .
				$this->box->row
			);
		}
	}
	?></td>
	<td data-value="<?php
	if ($this->box){
		echo $this->html($this->box->name);
	}
	?>"><?php
	if ($this->box){
		$this->box->view("link", true);
	}
	?></td>
	<?php
	$eppiPosition = "";
	if ($this->box){
		$eppiPosition = $this->column . $this->row;
	}
	?><td data-value="<?php echo $this->html($eppiPosition);?>"><?php echo $this->html($eppiPosition);?></td>
	<td class="noPrint"><?php $this->view("link.edit", true, "edit"); ?></td>
</tr>
<?php
}
?>