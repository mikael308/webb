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

	function getPageNav(){
		$user = getAuthorizedUser();
		$pagenavitems =
			listitem(
				getNavButton(
					$GLOBALS["index_page"],
			 		"start")
			)
			. listitem(
				getNavButton(
					$GLOBALS["forum_page"],
					"forum")
			)
			. listitem(
				getNavButton(
					getDisplayUserLink($user->getPrimaryKey()),
					"my page")
			);

		if($user != NULL && $user->isAdmin()){
			$pagenavitems .=
				getNavButton(
					$GLOBALS["admin_page"],
					"admin");
		}

		return
			"<ul id='pag_nav'>"
			. 	$pagenavitems
			. "</ul>";
	}
	/**
	 * get user navigation list
	 * if user is null empty string is returned
	 * @return as html
	 */
	function getUserNav($user){
		if($user == NULL)
			return "";

		$dropdown_btn =
			"<div class='dropbtn clickable'>"
			.		$user->getName()
			.		"<i class='dropbtn material-icons clickable'>expand_more</i>"
			.	"</div>";
		$dropdown_list =
			array(
				"my page" => getDisplayUserLink($user->getPrimaryKey()),
				"logout" => $GLOBALS["about_page"] . "?d=about"
			);

		return
			dropDownList(
				$dropdown_btn,
				$dropdown_list,
				"user_dropdown"
			);
	}

	/**
	 * get meta navigation content
	 * contains navigation commands:
	 * * about
	 * * search
	 * @return as html
	 */
	function getMetaNav(){
		$dropdown_btn_about =
			"<i class='dropbtn material-icons clickable'>info_outline</i>";
		$dropdown_list_about =
			array(
				"faq" => $GLOBALS['about_page'] . "?d=faq",
				"about" => $GLOBALS['about_page'] . "?d=about"
			);

		return
			"<ul id='meta_nav'>"
			. listitem(
					toolTip(
						dropDownList(
							$dropdown_btn_about,
							$dropdown_list_about
						),
						"about"
					)
				)
			. listitem(
					toolTip(
						getIconButton(
							"search",
							"onclick='openSearchPanel()'"
						),
						"search"
					)
				)
			. "</ul>";
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
