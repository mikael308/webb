<?php
	/**
	 * elements helper functions
	 *
	 * @author Mikael Holmbom
	 * @version 1.0
	 */

	require_once "./pageref.php";

	/**
	 * get a icon button
	 * @param icon the icon title
	 * @param attrs extra attributes added to icon element
	 * @return icon button as html
	 */
	function getIconButton(
		$icon,
		$attrs = ""
	) {
		return "<i id='".$icon."_btn' class='clickable material-icons' $attrs>"
			. 	$icon
			. "</i>";
	}
	/**
	 * TODO rm
	 * get a navigation button
	 * @param $href reference link
	 * @param $text the text of the button
	 */
	function getNavButton(
		$href,
		$text
	) {
		return "<a class='button btn_nav' href='$href'>$text</a>";
	}

	/**
	 * get a dropdownlist<br>
	 * dropdown reacts on target click<br>
	 * to activate dropdown add js/dropdownlist.js
	 * @param $target the target element to react as dropdown button
	 * @param $listitems
	 * @param $id optional id of main element
	 * @return string
	 */
	function dropDownList(
		$target, 
		$listitems, 
		$id=""
	) {
		$ddlist = "";
		foreach($listitems as $label => $link){
			$ddlist .=
				"<a href='$link'>$label</a>";
		}

		return "<div id='$id' class='dropdown'>"
			. 	$target
			.		"<div class='dropdown-content'>"
			.			$ddlist
			.		"</div>"
			. "</div>";
	}
	/**
	 * generate a element that shows tooltip when hover
	 * @param target target element to display a tooltip
	 * @param tooltip string tooltip text
	 * @return string tooltip element as html
	 */
	function toolTip(
		$target, 
		$tooltip
	) {
		return "<div class='tooltip'>"
			. 	"<span class='tooltiptext'>$tooltip</span>"
			.	$target
			. "</div>";

	}
	/**
	 * generate a switchbutton form input
	 * @param unknown $name name of input
	 * @param unknown $checked set the preset state of switchbutton
	 * @return string html
	 */
	function switchButton(
		$name, 
		$checked
	) {
		return "<label class='switch'>"
  				. "<input type='checkbox' ".($checked?"checked":"unchecked")." name='".$name."'>"
  				. "<div class='slider'></div>"
			. 	"</label>";
	}

	/**
	 * TODO rm
	 * @param $data
	 * @param string $class
	 * @param string $id
	 * @return string
	 */
	function table(
		$data, 
		$class="", 
		$id=""
	) {
		return "<table id='$id' class='$class'>"
			. $data
			. "</table>";
	}
	/**
     * TODO rm
	 * @return data formated as tr html element
	 */
	function tr(
		$data, 
		$class="", 
		$id=""
	) {
		return "<tr id='$id' class='$class'>$data</tr>";
	}
	/**
	 * @return data formated as td html element
	 */
	function td(
		$data, 
		$class="", 
		$id=""
	){
		return "<td id='$id' class='$class'>$data</td>";
	}

	function getStylesheets(
		... $filenames
	) {
		#TODO not opimized, search for php optimizing for better performance
		$stylesheets = "";
		foreach ($filenames as $fn) {
			$stylesheets .= getStylesheet($fn);
		}
		return $stylesheets;
	}
	
	/**
	 * get stylesheet link to file
	 * @param filename name of stylesheet
	 * @return stylesheet link as html
	 */
	function getStylesheet(
		$filename
	) {
		return "<link rel='stylesheet' href='".$GLOBALS["src-root"]."css/".$filename."' >";
	}

	function getScripts(
		... $filenames
	) {
		#TODO not opimized, search for php optimizing for better performance
		$scripts = "";
		foreach($filenames as $fn){
			$scripts .= getScript($fn);
		}
		return $scripts;
	}
	/**
	 * get script link to file
	 * @param filename name of script
	 * @return script link as html
	 */
	function getScript(
		$filename
	) {
		return "<script type='text/javascript' src='".$GLOBALS["src-root"]."js/".$filename."' ></script>";
	}
	/**
     * TODO rm
	 * generate listitem
	 * @param listitem the listitem element
	 * @return string listitem as html
	 */
	function listitem(
		$listitem
	) {
		return "<li>" . $listitem . "</li>";
	}
	/**
	 * set the title of current page
	 * title in form of: [subtitle] | maintitle
	 * @param string $subtitle subtitle of page
	 * @return string title as html
	 */
	function setTitle(
		$subtitle = null
	){
		$s = "";
		if($subtitle != null && $subtitle != ""){
			$s .= $subtitle . " | ";
		}
		$s .= "webbprogrammering-projekt";

		return
			"<title>"
			. 	$s
			. "</title>";
	}


?>
