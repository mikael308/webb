<?php
	/**
	 *
	 * helper functions containing dynamic std sections of website
	 *
	 * @author Mikael Holmbom: miho1202
	 * @version 1.0
	 */

	require_once "./config/pageref.php";
	require_once "./sections/authorization.php";
	require_once "./sections/elements.php";
	require_once "./sections/forms.php";
	require_once "./sections/navigation.php";
	require_once "./sections/views.php";


	/**
	 * get main head content
	 * defines:
	 * * javascript
	 * * meta charset
	 * * link
	 * * title
	 * @return as html
	 */
	function getMainHeadContent(){
		return
			"<meta charset='utf-8'>"
			. getScript("searchsidepanel.js")
			. getScript("searchdatabase.js")
			. getStylesheet("authorization.css")
			. getStylesheet("elements.css")
			. getStylesheet("main.css")
			. getStylesheet("message.css")
			. getStylesheet("navigation.css")
			. getStylesheet("search.css")
			. getStylesheet("widgets.css")
			. "<link rel='stylesheet' href='https://fonts.googleapis.com/icon?family=Material+Icons'>";
	}

	/**
	 * get the main header content
	 * displays navigation content
	 * @return as html
	 */
	function getMainHeaderContent(){
		# forum website logo
		$logo =
			"<div id='logo'>"
			.		"<a href='".$GLOBALS["pagelink"]["index"]."'>"
			.			"<h1 id='main_header'>"
			.				"Webb"
			.			"</h1>"
			.		"</a>"
			. "</div>";

		return
			"<div id='banner'>"
			.	"<div id='header_content'>"
			. 		$logo
			. 		getNavContent()
			. 	"</div>"
			. "</div>"
			. "<div id='subheader_content'>"
			. 	getSubNavContent()
			. "</div>"
			. getSidePanel();

	}

	/**
	 * get the main footer content\n
	 * displaying:
	 * * copyright
	 * @return as html
	 */
	function getMainFooterContent(){
		return
			"<div id='footer_content'>"
			. 	getFooterLinks()
			. 	"<hr/>"
			. 	getScript("dropdownlist.js")
			. 	"<p>&copy; Mikael Holmbom " . date("Y") . "</p>"
			. "</div>";
	}
	/**
	 * get list of footer links
	 * @return as html
	 */
	function getFooterLinks(){
		function asFooterCol($listitems){
			return "<ul class='footer_col'>$listitems</ul>";
		}

		return
			"<div id='footerlinks'>"
				# left footer column
				. asFooterCol(
					listitem("<a href='".$GLOBALS["pagelink"]["about_about"]."'>about</a>")
					. listitem("<a href='".$GLOBALS["pagelink"]["contact"]."'>contact us</a>")
				)
			# right footer column
			. asFooterCol(
				listitem("<a href='". $GLOBALS["pagelink"]["index"] ."'>footbook link</a>")
				. listitem("<a href='". $GLOBALS["pagelink"]["index"] ."'>tritter link</a>")
		 	)
		 . "</div>";
	}

?>
