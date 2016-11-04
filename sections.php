<?php

/**
 *  
 * helper fucntions containing dynamic std sections of website
 * 
 * @author Mikael Holmbom: miho1202
 * @version 1.0
 */

 	require_once "display_format.php";

	/**
	 * get main head content
	 * defines:
	 * * meta charset
	 * * link
	 * * title
	 */
	function getMainHeadContent(){
		return
			'<meta charset="utf-8">'
			. getStylesheet("main.css")
			. getStylesheet("msg.css")
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
			. getSubNavContent();
			
	}
	/**
	 * get the main footer content\n
	 * displaying:
	 * * copyright
	 */
	function getMainFooterContent(){
		return
			'<hr>'
			. '<p>&copy; Mikael Holmbom ' . date("Y") . '</p>'
			. '<p>Webbprogrammering, 7.5hp, Mittuniversitetet</p>';
	}
	/**
	 * get the navigation content
	 */
	function getNavContent(){
		$user = getAuthorizedUser();
		$authorizedUserId = $user != NULL ? $user->getPrimaryKey() : "";
		
		$cont = '<nav>'
			. '<ul id="page_nav">'
			. 	listitem(getNavButton('index.php', 'start'))
			. 	listitem(getNavButton('forum_page.php', 'forum'))
			. 	listitem(getNavButton('viewuser_page.php?u=' . $authorizedUserId, 'my page'))
			. 	listitem(getMetaNav())
			. '</ul>'
			;
		
		$user = getAuthorizedUser();
		if($user != NULL 
			&& $user->isAdmin()){
			$cont .= getNavButton("admin_page.php", "admin");
		}

		$cont	.= '</nav>';
		return $cont;
	}

	/**
	 * get a icon button
	 * @param icon the icon title
	 * @return icon button as html
	 */
	function getIconButton($icon){
		return '<i id="'.$icon.'" class="clickable material-icons">'.$icon.'</i>';
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
			. 	'<a href="about_page.php">'. getToolTip(getIconButton("info_outline"), "about") . '</a>'
			. 	'<a href="search_page.php">'.getToolTip(getIconButton('search'), "search").'</a>'
			. '</div>';
	}
	/**
	 * get the subnavigation content
	 */
	function getSubNavContent(){
		$cont = '<div id="subnav">';
		
		$user = getAuthorizedUser();
		if($user != NULL){
			logoutListener();
			$cont .= getLogoutForm();
			
			$cont .= "<div id='username_label'>you're logged in as <a href='viewuser_page.php?u=".$user->getPrimaryKey()."'>" . $user->getName() . "</a></div>";
		}
		
			
		$cont .= '</div>';
		return $cont;
		
	}
	function getBreadcrum(ForumSubject $subject = NULL, ForumThread $thread = NULL){
		function getBreadcrumLink($link, $title){
			return 
				'<div class="parallellogram">'
					. '<a class="breadcrum_link" href="'.$link.'">'.$title.'</a>'
				.'</div>';
		}
		
		$cont = '<div id="breadcrum">'
				. getBreadcrumLink("forum_page.php", "main");
		if($subject != NULL){
			$cont .= getBreadcrumLink("forum_page.php?s=".$subject->getPrimaryKey()."&p=1", $subject->getTopic());
		}
		if($thread != NULL){
			$cont .= getBreadcrumLink("forum_page.php?t=".$thread->getPrimaryKey()."&p=1", $thread->getTopic());
		}
		$cont .= '</div>';
		return $cont;
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
		return '<span><a href="registeruser_page.php">register here!</a></span>';
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
				. '<div><span id="login_errmsg"> ' . $errmsg . '</span></div>'
				.'<label for="input_username">username (email)</label><br>'
				.'<input type="text" id="input_username" name="input_username" autofocus><br>'
				.'<label for="input_password">password</label><br>'
				.'<input type="password" id="input_password" name="input_password"><br>'
				.'<input type="submit" class="btn" value="login" name="login"><br>'
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
