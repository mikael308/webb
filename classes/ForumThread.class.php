<?php

require_once 'classes/DataAccessObject.class.php';

/**
 * represents a thread in forum 
 *
 * Programvaruteknik,	Webbprogrammering 7.5hp
 * @author Mikael Holmbom
 * @version 1.0
 */
class ForumThread extends DataAccessObject {
	
	# this id
	private $id;
	# this subject containing this thread
	private $subject;
	# this topic
	private $topic;
	# original creator of this thread
	private $creator;
	
	private $lastAttributor;
	
	public function __construct($topic){
		$this->topic = $topic;
	}
	/**
	 * get this id
	 */
	public function getId(){
		return $this->id;
	}
	public function getSubject(){
		return $this->subject;
	}
	/**
	 * get this topic
	 */
	public function getTopic(){
		return $this->topic;
	}
	/**
	 * get this creator
	 */
	public function getCreator() {
		return $this->creator;
	}
	/**
	 * get last attributor
	 */
	public function getLastAttributor(){
		return $this->lastAttributor;
	}
	public function getPrimaryKey(){
		return $this->getId();
	}
	/**
	 * set this id
	 * @param id id value
	 */
	public function setId($id){
		$this->id = $id;
	}
	public function setSubject($subject){
		$this->subject = $subject;
	}
	/**
	 * set this creator
	 * @param creator new creator
	 */
	public function setCreator($creator) {
		$this->creator = $creator;
	}
	/**
	 * set this last attributor
	 * @param lastAttributor last attributor
	 */
	public function setLastAttributor($lastAttributor){
		$this->lastAttributor = $lastAttributor;
	}
	


}

?>
