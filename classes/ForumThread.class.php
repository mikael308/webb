<?php

require_once 'classes/DataAccessObject.class.php';

/**
 * represents a thread in forum 
 * thread contains forumposts
 * thread is part of forumsubject
 *
 * @author Mikael Holmbom
 * @version 1.0
 */
class ForumThread extends DataAccessObject {
	
	# this id
	private $id;
	# this subject containing this thread
	private $subject_fk;
	# this topic
	private $topic;
	# original creator of this thread
	private $creator_fk;
		
	public function __construct($topic){
		$this->topic = $topic;
	}
	/**
	 * get this id
	 */
	public function getId(){
		return $this->id;
	}
	/**
	 * get subject containing this thread
	 */
	public function getSubjectFK(){
		return $this->subject_fk;
	}
	/**
	 * get this topic
	 */
	public function getTopic(){
		return $this->topic;
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
	/**
	 * set subject containing this thread
	 */
	public function setSubjectFK($subject_fk){
		$this->subject_fk = $subject_fk;
	}
	
	
	
	# DATABASE
	################
	
	/**
	 * get this threads subject
	 */
	public function getSubject(){
		return read::subject($this->getSubjectFK());
	}
	/**
	 * get last attributor
	 */
	public function getLastAttributor(){
		return read::lastAttributor($this->getPrimaryKey());
	}
	/**
	 * get this creator
	 */
	public function getCreator() {
		return read::creator($this->getPrimaryKey());
	}



}

?>
