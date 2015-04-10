<?php


/* @var $temp DBItemBasicSetupTemplate */
$temp;
/* @var $config ConfigFile */
$config;

foreach ($config->ACTIONS as $actionName => $a){
	$item = $temp->mainNavigation->addItem($actionName, "?action=" . $a);
	/* @var $item ViewableHTMLNavigationItem */
	$item->active = $a === $action;
	if (in_array($a, $config->CLASS_ACTIONS)){
		$subNav = $item->addNavigation();
		foreach ($config->DB_ITEMS as $cn => $c){
			if (is_numeric($cn)){
				$cn = $c;
			}
			$subItem = $subNav->addItem($cn, $item->url . "&class=" . $c);
			$subItem->active = $item->active && ($c === $class);
		}
	}
}

if (array_key_exists("userID", $_SESSION)){
	$temp->mainNavigation->addItem("Logout", "?action=logout");
}

$item = $temp->mainNavigation->addItem("Import", "?action=import");
$item->active = "import" === $action;
$subNav = $item->addNavigation();
foreach ($config->IMPORT_BASES as $cn => $c){
	if (is_numeric($cn)){
		$cn = $c;
	}
	$subItem = $subNav->addItem($cn, $item->url . "&importBase=" . $c);
	$subItem->active = $item->active && ($c === array_read_key("importBase", $_GET));
}
?>
