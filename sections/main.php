<?php

	/**
	 *  
	 * helper functions containing dynamic std sections of website
	 * 
	 * @author Mikael Holmbom: miho1202
	 * @version 1.0
	 */

	require_once "./config/pageref.php";
	require_once "./sections/views.php";
	require_once "./sections/elements.php";
	require_once "./sections/helpers.php";
	require_once "./sections/authorization.php";
	require_once "./sections/forms.php";

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
			'<meta charset="utf-8">'
			. getScript("searchsidepanel.js")
			. getScript("searchdatabase.js")
			. getStylesheet("message.css")
			. getStylesheet("search.css")
			. getStylesheet("widgets.css")
			. '<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">';

	}

	/**
	 * get the main header content
	 * displays navigation content
	 * @return as html
	 */
	function getMainHeaderContent(){
		return
			'<div id="topheader">'
			. '<div id="logo">'
			.		'<a href="'.$GLOBALS['index_page'].'"><h1 id="main_header">Webb</h1></a>'
			. '</div>'
			. 	getNavContent()
			. '</div>'
			. getSubNavContent()
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
			'<div id="footer_content">'
			. 	getFooterLinks()
			. 	'<hr/>'
			. 	getScript("dropdownlist.js")
			. 	'<p>&copy; Mikael Holmbom ' . date("Y") . '</p>'
			. '</div>';
	}
	/**
	 * get list of footer links
	 * @return as html
	 */
	function getFooterLinks(){
		return '<ul id="footerlinks">'
			. listitem( '<a href="'.$GLOBALS['about_page'].'?d=about">about</a>' )
			. listitem( '<a href="'. $GLOBALS['contact_page'] .'">contact us</a>' )
		 . '</ul>';
	}
	/**
	 * get the navigation content
	 * @return as html
	 */
	function getNavContent(){
		$user = getAuthorizedUser();
		$authorizedUserId = $user != NULL ?
			$user->getPrimaryKey() : "";

		$listitems =
			listitem(
				getNavButton($GLOBALS['index_page'],
			 	'start'))
			. listitem(
				getNavButton($GLOBALS['forum_page'],
				'forum'))
			. listitem(
				getNavButton($GLOBALS['user_page'] .'?u=' . $authorizedUserId,
				'my page'));

		$user = getAuthorizedUser();
		if($user != NULL && $user->isAdmin()){
			$listitems .= getNavButton(
				$GLOBALS['admin_page'],
				"admin");
		}
		
		return '<nav>'
			. 	'<ul id="page_nav">'
			.		$listitems
			. 	'</ul>'
			. '</nav>';
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
				'my page' => getDisplayUserLink($user->getPrimaryKey()),
				'logout' => $GLOBALS['about_page'] . '?d=about'
			);
		return
			dropDownList(
				$dropdown_btn,
				$dropdown_list,
				"user_nav"
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
		return '<div id="meta_nav">'
			. 	toolTip(dropDownList("<i class='dropbtn material-icons clickable'>info_outline</i>", array(
						"faq" => $GLOBALS['about_page'] . "?d=faq",
						"about" => $GLOBALS['about_page'] . "?d=about"
					)),"about")
			. toolTip(getIconButton('search', 'onclick="openSearchPanel()"'), 'search') 
			. '</div>';
	}
	/**
	 * get the subnavigation content
	 * @return as html
	 */
	function getSubNavContent(){
		$listitems = '';

		return '<div id="subnav">'
			. '<ul>'
			.	$listitems
			. "<li id='metanav_li'>" . getMetaNav() . "</li>"
			. '</ul>'
			. '</div>';

	}


?>
