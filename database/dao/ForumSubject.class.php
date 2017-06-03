<?php

	require_once "./database/Read.php";

/**
 * defines forumsubject entry
 * 
 * @author Mikael Holmbom
 * @version 1.0
 */
class ForumSubject extends DataAccessObject {

	/**
	 * this primary key
	 * 
	 */
	private $id;
	/**
	 * this topic
	 */
	private $topic;
	/**
	 * this subtitle
	 */
	private $subtitle;
	
	public function __construct(){
		
	}
	/**
	 * set this id
	 * @param id new id value
	 */
	public function setId($id){
		$this->id = $id;
	}
	/**
	 * set this topic
	 * @param topic new topic value
	 */
	public function setTopic($topic){
		$this->topic = $topic;
	}
	/**
	 * set this subtitle
	 * @param id new subtitle value
	 */
	public function setSubtitle($subtitle){
		$this->subtitle = $subtitle;
	}
	/**
	 * get this id
	 * @return this topic value
	 */
	public function getTopic(){
		return $this->topic;
	}
	/**
	 * get this subtitle
	 * @return this subtitle value
	 */
	public function getSubtitle(){
		return $this->subtitle;
	}
	/**
	 * get this primary key
	 * @return this primary key value
	 */
	public function getPrimaryKey(){
		return $this->id;
	}
	/**
	 * this subjects threads
	 * @return threads as array
	 */
	public function getThreads(){
		return read::threadsOfSubject($this->getPrimaryKey());
	}
}

?>
