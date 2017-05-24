<?php
	/**
	 * defines listeners for handling requests
	 * 
	 * @author Mikael Holmbom
	 * @version 1.0
	 */


	/**
	 * get index value from request GET\n
	 * param must be >= 0
	 * @param index return value from GET request 
	 * @return 
	 */
	function get_index($key){
		if(isset($_GET[$key])){
			if(preg_match("/^[0-9]*$/", $_GET[$key])){
				return $_GET[$key];
			}
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





?>