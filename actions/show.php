<?php

if (array_key_exists("id", $_GET)){
	$item = DBItem::getCLASS($class, $_GET["id"]);
	$item->on(
		"view.fields.start",
		create_function('$ev', '
			$item = $ev->getTarget();
			if ($item->hasField("creator")){
				$uidNumber = $item->creator;
				echo "<tr><td>creator</td><td>";
				global $ldap;
				if ($ldap->isBound()){
					$user = LDAPUser::getById($uidNumber);
					if ($user){
						$user->view("singleLine", true);
					}
					else {
						echo "---";
					}
				}
				else {
					echo "LDAP server not available";
				}
				echo "</td></tr>";
			}')
	);
	$item->on(
		"view.field.sequence",
		create_function(
			'$ev',
			'$item = $ev->getCurrentTarget();
			if ($item instanceOf SequenceItem){
				echo "<tr><td>sequence length</td><td>" . $item->getSequenceLength() . "</td>";
			}
			else {
				echo "<tr><td>sequence length</td><td>" . strlen(preg_replace("/\\\\s/", "", $item->sequence)) . "</td>";
			}')
	);
	$item->on(
		"view.fields.end",
		create_function('$ev', 'echo "<tr><td>edit</td><td>" . $ev->getCurrentTarget()->view("link.edit", false) . "</td>";')
	);
	$temp->content .= $item->view(false, false);
}
else {
	$temp->content = '<h1>Choose ' . $temp->html($class) .'</h1><ul>';
	foreach (DBItem::getByConditionCLASS($class) as $item){
		$temp->content .= '<li>' .
			$item->view("link", false) .
			'</li>';
	}
	$temp->content .= '</ul>';
}
?>