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
			. getStylesheet("main.css")
			. getStylesheet("message.css")
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
			.		"<a href='".$GLOBALS["index_page"]."'>"
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
		return
			"<ul id='footerlinks'>"
				. listitem(
					"<a href='".$GLOBALS["about_page"]."?d=about'>"
					. 	"about"
					. "</a>"
				)
				. listitem(
					"<a href='". $GLOBALS["contact_page"] ."'>"
					. 	"contact us"
					. "</a>"
				)
		 . "</ul>";
	}
	/**
	 * get the navigation content
	 * @return as html
	 */
	function getNavContent(){
		return
			"<nav>"
			. 	getPageNav()
			.		"<ul id='user_nav'>"
			. 		listitem(getUserNav(getAuthorizedUser()))
			.		"</ul>"
			. "</nav>";
	}

	/**
	 * get the subnavigation content
	 * @return as html
	 */
	function getSubNavContent(){
		return
			"<div id='sub_nav'>"
			. 	getMetaNav()
			.	"</div>";

	}


?>
