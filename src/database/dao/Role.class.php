<?php

namespace Web\Database\DAO;

require_once "./database/dao/DataAccessObject.class.php";


/**
 *
 * DataAccessObject
 * Defines a Role used for ForumUser
 *
 * @author Mikael Holmbom
 * @version 1.0
 */
class Role extends DataAccessObject{

	/**
	 * this id
	 */
	private $id = -1;
	/**
	 * this title
	 */
	private $title;

	/**
	* @param id the id of this Role
	* @param title the title of this Role
	*/
	function __construct($id, $title){
		$this->id = $id;
		$this->title = $title;
	}
	/**
	* get this id
	*/
	public function getId(){
		return $this->id;
	}
	/**
	* get this title
	*/
	public function getTitle(){
		return $this->title;
	}
	/**
	 * get this primary key
	 */
	public function getPrimaryKey(){
		return $this->getId();
	}
}
