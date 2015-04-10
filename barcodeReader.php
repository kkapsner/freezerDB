<?php
include_once("loadFramework.php");
session_start();

$temp = new DBItemBasicSetupTemplate();
$temp->addStyle("css.css");
$temp->addStyle("mainmenu.css");
$temp->addStyle("DBItem.css");

$db = DB::getInstance();
$ldap = new LDAP("bigmac.e14.ph.tum.de");
$ldap->bind();

$class = "Eppi";

include("barcodeReaderActions/create.php");
include("barcodeReaderActions/edit.php");
include("barcodeReaderActions/search.php");

$temp->write();
?>