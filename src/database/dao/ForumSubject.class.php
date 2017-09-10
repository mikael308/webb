<?php

namespace Database\DAO;

require_once "./database/dao/DataAccessObject.class.php";
require_once "./database/Read.php";
require_once "./database/dao/iForumContent.php";

use Database\Read;

/**
 * defines forumsubject entry
 * 
 * @author Mikael Holmbom
 * @version 1.0
 */
class ForumSubject extends DataAccessObject 
	implements iForumContent
{

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
	
	public function getSize(){
		return sizeof($this->getThreads());
	}
	/**
	 * this subjects threads
	 * @return threads as array
	 */
	public function getThreads(){
		return Read::threadsOfSubject(
			$this->getPrimaryKey()
		);
	}

	/**
	 * get the index of the current last page containing posts
	 * index is affected by the setting: posts_per_page
	 * @return page index as integer
	 */
	function getLastPageIndex(){
		return ceil(
			$this->getSize()
			/
            $_SESSION['settings']->value("threads_per_page")
		);
	}
}
