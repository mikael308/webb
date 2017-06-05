<?php
	/**
	 * helper functions
	 * @author Mikael Holmbom
	 * @version 1.0
	 */

	/**
	 * get stylesheet link to file
	 * @param filename name of stylesheet 
	 * @return stylesheet link as html
	 */
	function getStylesheet($filename){
		return "<link rel='stylesheet' href='./css/".$filename."' >";
	}
	/**
	 * get script link to file
	 * @param filename name of script 
	 * @return script link as html
	 */
	function getScript($filename){
		return "<script type='text/javascript' src='./js/".$filename."' ></script>";
	}
	/**
	 * generate listitem 
	 * @param listitem the listitem element
	 * @return listitem as html
	 */
	function listitem($listitem){
		return "<li>" . $listitem . "</li>";
	}
	/**
	 * set the title of current page
	 * title in form of: [subtitle] | maintitle
	 * @param subtitle the subtitle of page
	 * @return title as html
	 */
	function setTitle($subtitle = NULL){
		$s = "";
		if($subtitle != NULL && $subtitle != ""){
			$s .= $subtitle . " | ";
		}
		$s .= "webbprogrammering-projekt";

		return
			"<title>"
			. 	$s
			. "</title>";
	}
	

?>