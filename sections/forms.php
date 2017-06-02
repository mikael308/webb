<?php
	/**
	 * forms
	 * @author Mikael Holmbom
	 * @version 1.0
	 */

	/**
	 * get form for client searching in database
	 *
	 */
	function getSearchForm(){

		return
		"<form method='GET' id='searchform'>"
			. "<input type='text' id='searchbar' name='search_value' onkeyup='search(this.value)' autocomplete='off' >"
			. "<input id='searchform_btn' type='submit' value='search' class='clickable material-icons'>"
			. "<br>"
			. "<label for='post'>post</label>"
			. "<input type='radio' value='post' name='search_type' onclick='search(search_value.value)' checked >"
			. "<label for='user'>user</label>"
			. "<input type='radio' value='user' name='search_type' onclick='search(search_value.value)'><br>"

		. "</form>";
	}

?>