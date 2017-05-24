<?php

	/**
	 *  
	 * helper functions containing dynamic std sections of website
	 * 
	 * @author Mikael Holmbom: miho1202
	 * @version 1.0
	 */

	require_once "./config/pageref.php";
	require_once "./sections/format/display.php";
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
	 */
	function getMainHeadContent(){
		return
			'<meta charset="utf-8">'
			. getScript("searchsidepanel.js")
			. getScript("searchdatabase.js")
			
			. getStylesheet("main.css")
			. getStylesheet("msg.css")
			. getStylesheet("search.css")
			. getStylesheet("widgets.css")
			. '<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">';
			
	}

	/**
	 * get the main header content
	 * displays navigation content
	 */
	function getMainHeaderContent(){
		return 
			'<h1 id="main_header">Webbprogrammering-projekt</h1>'
			. getNavContent()
			. '<hr>'
			. getSubNavContent()
			. getSidePanel();
			
	}

	/**
	 * get the main footer content\n
	 * displaying:
	 * * copyright
	 */
	function getMainFooterContent(){
		return
			'<hr>'
			. getFooterLinks()
			. getScript("dropdownlist.js")
			. '<p>&copy; Mikael Holmbom ' . date("Y") . '</p>'
			. '<p>Webbprogrammering, 7.5hp, Mittuniversitetet</p>';
	}
	function getFooterLinks(){
		return '<ul id="footerlinks">'
			. '<li>' . '<a href="'.$GLOBALS['about_page'].'?d=about">about</a>' . '</li>'
			. '<li>' . '<a href="'. $GLOBALS['contact_page'] .'">contact us</a>' . '</li>'
		 . '</ul>';
	}
	/**
	 * get the navigation content
	 */
	function getNavContent(){
		$user = getAuthorizedUser();
		$authorizedUserId = $user != NULL ? $user->getPrimaryKey() : "";
		
		$listitems = 
			 	listitem(getNavButton($GLOBALS['index_page'] , 'start'))
			. 	listitem(getNavButton($GLOBALS['forum_page'] , 'forum'))
			. 	listitem(getNavButton($GLOBALS['user_page'] .'?u=' . $authorizedUserId, 'my page'));
		
		$user = getAuthorizedUser();
		if($user != NULL 
			&& $user->isAdmin()){
			$listitems .= getNavButton($GLOBALS['admin_page'] , "admin");
		}
		
		return '<nav>'
			. 	'<ul id="page_nav">'
			.		$listitems
			. 	'</ul>'
			. '</nav>';
	}

	/**
	 * get meta navigation content
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
	 */
	function getSubNavContent(){
		$listitems = '';
		$user = getAuthorizedUser();
		if($user != NULL){
			logoutListener();
			$listitems .=
				  listitem(getLogoutForm())
				. listitem("<div id='username_label'>you're logged in as <a href='".$GLOBALS['user_page'] ."?u=".$user->getPrimaryKey()."'>" . $user->getName() . "</a></div>");
		}
		$listitems .= "<li id='metanav_li'>" . getMetaNav() . "</li>";
		
		return '<div id="subnav">'
			. '<ul>'
			.	$listitems
			. '</ul>'
			. '</div>';
		
	}
	/**
	 * generate breadcrum links to current subject and thread
	 * @param subject the current subject
	 * @param thread the current thread
	 */
	function getBreadcrum(ForumSubject $subject = NULL, ForumThread $thread = NULL){
		function getBreadcrumLink($link, $title){
			return 
				'<div class="parallellogram">'
					. '<a class="clickable breadcrum_link" href="'.$link.'">'.$title.'</a>'
				.'</div>';
		}
		
		$cont = '<div id="breadcrum">'
				. getBreadcrumLink($GLOBALS['forum_page'], "main");
		if($subject != NULL){
			$cont .= getBreadcrumLink($GLOBALS['forum_page'] ."?s=".$subject->getPrimaryKey()."&p=1", $subject->getTopic());
		}
		if($thread != NULL){
			$cont .= getBreadcrumLink($GLOBALS['forum_page'] ."?t=".$thread->getPrimaryKey()."&p=1", $thread->getTopic());
		}
		$cont .= '</div>';
		return $cont;
	}	
	
	
?>