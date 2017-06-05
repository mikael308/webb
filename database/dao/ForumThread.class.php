<?php

require_once './database/dao/DataAccessObject.class.php';
require_once "./config/settings.php";

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
	 * get creator foreign key
	 */
	public function getCreatorFK(){
		return $this->creator_fk;
	}
	/**
	 * get this topic
	 */
	public function getTopic(){
		return $this->topic;
	}
	/**
	 *get this primary key
	 */
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
	 * 
	 * @return this threads subject
	 */
	public function getSubject(){
		return Read::subjects($this->getSubjectFK())[0];
	}
	/**
	 * @return last attributor of this thread
	 */
	public function getLastAttributor(){
		return Read::lastAttributor($this->getPrimaryKey());
	}
	/**
	 * 
	 * @return the cretor of this thread
	 */
	public function getCreator() {
		return Read::creator($this->getPrimaryKey());
	}
	/**
	 * get posts contained in this thread
	 * @return forumpost[]
	 */
	public function getPosts(){
		return Read::postsFromThread($this->getPrimaryKey());
	}
	/**
	 * get current amount of posts
	 * @return amount of posts
	 */
	function postsSize(){
		return Count::postsBy($this);
	}
	/**
	 * get the index of the current last page containing posts
	 * index is affected by the setting: posts_per_page
	 * @return page index as integer
	 */
	function getLastPageIndex(){
		return ceil($this->postsSize() / readSettings("posts_per_page"));
	}

	/**
	 * @return this threads last post
	 */
	function getLastPost(){
		return $this->getPosts()[($this->postsSize()-1)];
	}

}

?>
