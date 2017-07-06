<?php
	/**
	 * defines listeners for authorization 
	 * 
	 * @author Mikael Holmbom
	 * @version 1.0
	 */

	/**
	 * if session authorized_user is not authorized:\n
	 * redirect to param page
	 * @param redirectedPage the page to redirect client to
	 */
	function restrictedToAuthorized($redirectedPage=null){
		if($redirectedPage == null)
			$redirectedPage = $GLOBALS["pagelink"]["register"];
		if(! userIsAuthorized()){	
			header("Location: " . $redirectedPage);
			exit();
		}
	}
	/**
	 * if session authorized_user is not admin:\n
	 * redirect to param page
	 * @param redirectedPage the page to redirect client to
	 */
	function restrictedToAdmin($redirectedPage){
		restrictedToAuthorized($redirectedPage);
		$authUser = getAuthorizedUser();
		
		if( $authUser == NULL || ! $authUser->isAdmin()){
			header("Location: " . $redirectedPage);
			exit();
		} 
	}
	/**
	 * listens for POST request for\n
	 * login\n
	 * logout
	 */
	function authorizationListener(){
		loginListener();
		logoutListener();
	}
	/**
	 * listens for login POST request\n
	 * sets session authorized_user to logged in user
	 * @return True if login was successful
	 */
	function loginListener(){
		if($_SERVER["REQUEST_METHOD"] == "POST"){
		
			if(isset($_POST['login'])){
				$user 	= $_POST['input_username'];
				$passw 	= $_POST['input_password'];
				
				switch(login($user, $passw)){
					case 1: # successful login
						return True;
					case 0:
						$_SESSION["login_errmsg"] = "could not connect to database";
						break;
					case -1:
						$_SESSION["login_errmsg"] = "could not login, wrong username or password";
						break;
					case -2:
						$_SESSION["login_errmsg"] = "could not login, user is banned";
						break;
				}
				
			}
		}
		return False;
	}
	/**
	 * listens for logout POST requests\n
	 * set session authorized_user to null and redirect to index
	 */
	function logoutListener(){
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			$authUser = getAuthorizedUser();
			if (isset($_POST["logout"])){

				if($authUser != NULL)
					$_SESSION["authorized_user"] = NULL;

				header("Location: " . $GLOBALS["pagelink"]["index"]);
				exit();
			}
		}
	}
	/**
	 * get this sessions authorized user
	 */
	function getAuthorizedUser(){
		if(isset($_SESSION["authorized_user"])){
			return $_SESSION["authorized_user"];
		}
		return NULL;
	}

	/**
	 * @return true if current user is authorized
	 */
	function userIsAuthorized(){
		return getAuthorizedUser() != NULL;
	}


?>