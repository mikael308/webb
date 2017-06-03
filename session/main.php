<?php
	/**
	 * defines helper functions to read scope variables 
	 * 
	 * @author Mikael Holmbom
	 */

	/**
	 * starts session if not already active
	 */
	function startSession(){
		if(session_status() !== PHP_SESSION_ACTIVE){
			session_start();
		}
	}


?>