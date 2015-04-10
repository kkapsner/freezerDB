<?php
include_once("loadFramework.php");
session_start();

$temp = new DBItemBasicSetupTemplate();
$temp->addStyle("css.css");
$temp->addStyle("mainmenu.css");
$temp->addStyle("DBItem.css");
$temp->addStyle("collection.css");
$temp->addStyle("rack.css");
$temp->addStyle("box.css");
$printCSS = new ViewableHTMLTagStyle();
$printCSS->setHTMLAttribute("src", $temp->stylePlace . "print.css");
$printCSS->setHTMLAttribute("media", "print");
$temp->addScript("/kkjs/modules/kkjs.load.js?modules=parser,date,table", false, true);
$temp->addScript("position.js", true, true);
$temp->addScript("currentTimestamp.js", true, true);
$temp->addScript("csvFile.js", true, true);
$temp->addScript("enableTableColumnModifications.js", true, true);

$db = DB::getInstance();

$ldap = LDAP::createFromConfigFile(new ConfigFile("ldapConfig.ini", true));

$config = new ConfigFile("config.ini", true);

include_once("actions/dailyBackup.php");

$class = array_read_key("class", $_POST, false);
if ($class === false){
	$class = array_read_key("class", $_GET, false);
}
if (!in_array($class, $config->DB_ITEMS)){
	$class = false;
}

$action = array_read_key("action", $_GET, false);
if (!in_array($action, $config->ACTIONS) && !in_array($action, $config->HIDDEN_ACTIONS)){
	$action = $config->DEFAULT_ACTION;
	if (!$class){
		$class = $config->DEFAULT_CLASS;
	}
}


if (in_array($action, $config->CLASS_ACTIONS) && $class === false){
	include ("./actions/chooseClass.php");
}
else if (is_file("./actions/" . $action . ".php")){
	include("./actions/" . $action . ".php");
}

include("./navigation.php");

$temp->write();
?>