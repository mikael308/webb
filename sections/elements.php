<?php
	/**
	 * AUTHORIZATION helper functions
	 *
	 * @author Mikael Holmbom
	 * @version 1.0
	 */

	/**
	 * get a icon button
	 * @param icon the icon title
	 * @param attrs extra attributes added to icon element
	 * @return icon button as html
	 */
	function getIconButton($icon, $attrs = ""){
		return '<i id="'.$icon.'_btn" class="clickable material-icons" '.$attrs.'>'.$icon.'</i>';
	}
	/**
	 * get a navigation button
	 * @param href reference link
	 * @param text the text of the button
	 */
	function getNavButton($href, $text){
		return '<a class="button btn_nav" href="'.$href.'">'.$text.'</a>';
	}

	/**
	 * get a dropdownlist<br>
	 * dropdown reacts on target click<br>
	 * to activate dropdown add js/dropdownlist.js
	 * @param unknown $target the target element to react as dropdown button 
	 * @param unknown $listitems 
	 * @return string
	 */
	function dropDownList($target, $listitems){
		$ddlist = "";
		foreach($listitems as $label => $link){
			$ddlist .= '<a href="' . $link . '">' . $label . '</a>';
		}
		
		return '<div class="dropdown">'
			. $target
			.	'<div class="dropdown-content">'
			.		$ddlist
			.	'</div>'
			. '</div>';
	}
	function getSidePanel(){
		return searchSidePanel();
	}
	
	/**
	 * get searchsidepanel as hidden. to open: see searchsidepanel.js
	 * @return search sidepanel as html
	 */
	function searchSidePanel(){
		$res_cont = getAuthorizedUser() != NULL ? 
					'<div id="searchres"></div>' :
					'<div><a href="'.$GLOBALS['register_page'] .'">register to search forum</a></div>';
		
		return '<div id="search_sidepanel" class="sidepanel">'
				. '<a href="javascript:void(0)" class="clickable closebtn" onclick="closeSearchPanel()">&times;</a>'
				. '<h2>search</h2>'
				. '<div class="sidepanel_content">'
				. 	getSearchForm()
				. 	$res_cont
				. '</div>'
			. '</div>';
	}
	/**
	 * generate a element that shows tooltip when hover
	 * @param target the target element to display a tooltip
	 * @param tooltip the tooltip text
	 * @return tooltip element as html
	 */
	function toolTip($target, $tooltip){
		return '<div class="tooltip">'
			. 	'<span class="tooltiptext">' . $tooltip . '</span>'
			.	$target
			. '</div>';
		
	}
	/**
	 * generate a switchbutton form input
	 * @param unknown $name name of input
	 * @param unknown $checked set the preset state of switchbutton
	 * @return string html
	 */
	function switchButton($name, $checked){
		return "<label class='switch'>"
  				. "<input type='checkbox' ".($checked?"checked":"unchecked")." name='".$name."'>"
  				. "<div class='slider'></div>"
			. 	"</label>";
	}

	

?>