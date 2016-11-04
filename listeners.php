<?php
/**
 * defines helper functions to read scope variables 
 * 
 * @author Mikael Holmbom
 */

	############################
	#
	#	REQUEST VARIABLES
	#
	###########################

	/**
	 * get value from request GET\n
	 * scope using this must declare variable $requestIndex
	 * @param index return value from GET request 
	 * @return 
	 */
	function get($index){
		global $requestIndex;
		$key = $requestIndex[$index];
				
		if(isset($_GET[$key])){
			return $_GET[$key];
		}
		return NULL;
	}
	/**
	 * get value from request POST\n
	 * scope using this must declare variable $requestIndex
	 * @param index return value from GET request 
	 * @return 
	 */
	function post($index){
		global $requestIndex;
		$key = $requestIndex[$index];
				
		if(isset($_POST[$key])){
			return $_POST[$key];
		}
		return NULL;
	}
	/**
	 * determine if index is set as GET\n
	 * scope using this must declare variable $requestIndex
	 * @param index index to determine
	 */
	function issetGet($index){
		global $requestIndex;
		return isset($_GET[$requestIndex[$index]]);
	}
	/**
	 * determine if index is set as POST\n
	 * scope using this must declare variable $requestIndex
	 * @param index index to determine
	 */
	function issetPost($index){
		global $requestIndex;
		return isset($_GET[$requestIndex[$index]]);
	}





	/**
	 * starts session if not already active
	 */
	function startSession(){
		if(session_status() !== PHP_SESSION_ACTIVE){
			session_start();
		}
	}
	/**
	 * if session authorized_user is not authorized:\n
	 * redirect to param page
	 * @param redirectedPage the page to redirect client to
	 */
	function restrictedToAuthorized($redirectedPage){
		if(! isset($_SESSION['authorized_user'])){
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
		if( $authUser != NULL && $authoUser->isAdmin()){
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
						$_SESSION['login_errmsg'] = "could not connect to database";
						break;
					case -1:
						$_SESSION['login_errmsg'] = "could not login, wrong username or password";
						break;
					case -2:
						$_SESSION['login_errmsg'] = "could not login, user is banned";
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
			if (isset($_POST['logout'])){
				$_SESSION['authorized_user'] = null;
				header("Location: index.php");
				exit();
			}
		}
	}
	/**
	 * get this sessions authorized user
	 */
	function getAuthorizedUser(){
		if(isset($_SESSION['authorized_user'])){
			return $_SESSION['authorized_user'];	
		}
		return NULL;
	}

?>