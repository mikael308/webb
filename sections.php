<?php

/**
 *  
 * helper fucntions containing dynamic std sections of website
 * 
 * @author Mikael Holmbom: miho1202
 * @version 1.0
 */

	require_once "pageref.php";
 	require_once "display_format.php";

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
	function setTitle($subtitle = NULL){
		$s = '<title>';
		if($subtitle != NULL && $subtitle != ""){
			$s .= $subtitle . " | ";
		}
		$s .='webbprogrammering-projekt';
		
		return $s .'</title>';
	}
	/**
	 * get stylesheet link to file
	 * @param filename name of stylesheet 
	 * @return stylesheet link as html
	 */
	function getStylesheet($filename){
		return '<link rel="stylesheet" href="./css/'.$filename.'" >';
	}
	/**
	 * get script link to file
	 * @param filename name of script 
	 * @return script link as html
	 */
	function getScript($filename){
		return '<script type="text/javascript" src="./js/'.$filename.'" ></script>';	
	}
	/**
	 * generate listitem 
	 * @param listitem the listitem element
	 * @return listitem as html
	 */
	function listitem($listitem){
		return '<li>' . $listitem . '</li>';
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
	function getSidePanel(){
		return searchSidePanel();
	}
	/**
	 * get the main footer content\n
	 * displaying:
	 * * copyright
	 */
	function getMainFooterContent(){
		return
			'<hr>'
			. getScript("dropdownlist.js")
			. '<p>&copy; Mikael Holmbom ' . date("Y") . '</p>'
			. '<p>Webbprogrammering, 7.5hp, Mittuniversitetet</p>';
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
	/**
	 * 
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
	/**
	 * get searchsidepanel as hidden. to open, see searchsidepanel.js
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
	
	////////////////////////////////////////////////////////////////////
	//
	//	AUTHORIZATION helper functions
	//
	/////////////////////////////////////////////////////////////////
	
	/**
	 * gets authorization forms
	 * if not logged in: returns login form, 
	 * if already authorized: return logout form
	 * @return div containing authorization content
	 */
	function getAuthorizationContent(){
		$cont = "";
		
		$cont .= '<div id="authorizationsection">';
		# if user is not auhtorized
		if(! isset($_SESSION['authorized_user'])){
			$cont .= getLoginForm()
					. getRegisterContent();
			
		} else {
			#$cont .= getLogoutForm();
		}
	
		$cont .= '</div>';
	
		return $cont;
	}
	/**
	 * get link to registration content
	 */
	function getRegisterContent(){
		return '<span><a href="'. $GLOBALS['register_page']. '">register here!</a></span>';
	}
	/**
	 * form with fields:
	 * 			input_username  (text)
	 * 			input_password 	(password)
	 * submit button:
	 * 			login	(submit)
	 * 
	 */
	function getLoginForm(){
		$errmsg = isset($_SESSION['login_errmsg']) ? $_SESSION['login_errmsg'] : "";
		return
			'<div>'
			.'<form id="loginform" method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" >'
				.	'<div><span id="login_errmsg"> ' . $errmsg . '</span></div>'
				.	'<label for="input_username">username</label><br>'
				.	'<input type="text" id="input_username" name="input_username" ><br>'
				.	'<label for="input_password">password</label><br>'
				.	'<input type="password" id="input_password" name="input_password"><br>'
				.	'<input type="submit" class="btn" value="login" name="login"><br>'
			.'</form>'
			.'</div>';

	}
	/**
	 * get form used for logout current user
	 */
	function getLogoutForm(){
		return 
				'<form id="logoutform" method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">'
				.	'<input type="submit" class="btn" value="logout"	name="logout">'
				. '</form>';
	}
	
	/**
	 * display data as table row
	 * @param rowdata data to display
	 */
	function tr($rowdata){
		return '<tr>'.$rowdata.'</tr>';
	}
	/**
	 * display data as table data
	 * @param data data to display
	 */
	function td($data){
		return '<td>'.$data.'</td>';
	}


?>
