<?php

require_once 'classes/DataAccessObject.class.php';

/**
 * Programvaruteknik,	Webbprogrammering 7.5hp
 *
 * @author Mikael Holmbom
 * @version 1.0
 */ 
class ForumUser extends DataAccessObject {

	/**
	 * this user name
	 */ 
	private $name;
	/**
	 * this user email
	 */
	private $email;
	/**
	 * this user role
	 */
	private $role;
	/**
	 * true if this user is banned
	 */
	private $banned;
	
	/**
	 *
	 */
	function __construct(){
	}
	/**
	 * get this name
	 */
	public function getName(){
		return $this->name;
	}
	/**
	 * get this email
	 */
	public function getEmail(){
		return $this->email;
	}
	/**
	 * get this role
	 */
	public function getRole(){
		return $this->role;
	}
	/**
	 * get this private key
	 */
	public function getPrimaryKey(){
		return $this->getName();
	}
	/**
	 * set this name
	 * @param name new name value
	 */
	public function setName($name){
		$this->name = $name;
	}
	/**
	 * set this email
	 * @param email new email value
	 */
	public function setEmail($email){
		$this->email = $email;
	}
	/**
	 * set this role
	 * @param role new role value
	 */
	public function setRole($role){
		$this->role = $role;
	}
	/**
	* set this banned state value
	* @param banned boolean value for this banned state
	*/
	public function setBanned($banned){
		$this->banned = $banned;
	}
	/**
	 * determine if this user role is admin
	 * @param true if user is admin
	 */
	public function isAdmin(){
		if($this->getRole() == NULL) return False;
		return $this->getRole() == "admin";
	}
	/**
	 * determine if this user role is  moderator
	 * @param true if user is moderator
	 */
	public function isModerator(){
		if($this->getRole() == NULL) return False;
		return $this->getRole() == "moderator";
	}
	/**
	 * determine if this user role is user
	 * @param true if user is user
	 */
	public function isUser(){
		if($this->getRole() == NULL) return False;
		return $this->getRole() == "user";
	}
	/**
	* determine if this instance has the state: banned
	* @return True if this instance is banned
	*/
	public function isBanned(){
		return $this->banned;
	}
	
}

?>
