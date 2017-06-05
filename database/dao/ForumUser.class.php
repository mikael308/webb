<?php

	require_once './database/dao/DataAccessObject.class.php';


	/**
	 * defines a user of the forum
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
		 * timestamp when this forumuser was registered
		 */
		 private $registered;

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
		 * get timestamp when this forumuser was registered
		 */
		public function getRegistered(){
			return $this->registered;
		}
		/**
		 * get this primary key
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
		 * set this registered timestamp
		 * @param regsitered timestamp when this forumuser was registered
		 */
		public function setRegistered($registered){
			$this->registered = $registered;
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
