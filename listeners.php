<?php

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
		
		if(! $_SESSION['authorized_user']->isAdmin()){
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
		return $_SESSION['authorized_user'];
	}

?>