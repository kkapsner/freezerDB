<?php

class DBItemBasicSetupTemplate extends Template{
	public 
		$stylePlace = "./style/",
		$scriptPlace = "./script/",
		$favicon = "",
		$content, $headContent, $footContent,
		/**
		 * @var ViewableHTMLNavigation
		 */
		$mainNavigation,
		$headActive = false,
		$contentIndent = 0,
		$pureContent = false,
		$bodyClass = ""
	;
	
	public function __construct(){
		$this->mainNavigation = new ViewableHTMLNavigation();
		$this->mainNavigation->setHTMLAttribute("id", "mainNavigation");
	}
}
?>