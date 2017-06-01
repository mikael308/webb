<?php
	/**
	 * AUTHORIZATION helper functions
	 *
	 * @author Mikael Holmbom
	 * @version 1.0
	 */
	
	require_once "./config/pageref.php";
	require_once "./sections/views.php";

	
	/**
	 * gets authorization forms
	 * if not logged in: returns login form, 
	 * if already authorized: return logout form
	 * @return div containing authorization content
	 */
	function getAuthorizationContent(){
		$cont = "";
		
		# if user is not auhtorized
		if(! isset($_SESSION['authorized_user'])){
			$cont .= getLoginForm()
					. getRegisterContent();
			
		} else {
			#$cont .= getLogoutForm();
		}
	
		return '<div id="authorizationsection">'
			. $cont
			. '</div>';
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
	

?>
