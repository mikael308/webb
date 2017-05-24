<?php
	/**
	 * defines helper functions to read scope variables 
	 * 
	 * @author Mikael Holmbom
	 */

	require_once "./config/pageref.php";
	require_once "./session/authorization.php";
	require_once "./session/reuests.php";

	/**
	 * starts session if not already active
	 */
	function startSession(){
		if(session_status() !== PHP_SESSION_ACTIVE){
			session_start();
		}
	}


?>