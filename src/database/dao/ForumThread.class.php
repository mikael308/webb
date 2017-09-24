<?php

namespace Web\Database\DAO;

require_once "./database/dao/iForumContent.php";
require_once "./database/Count.php";
require_once "./database/Read.php";
require_once "./session/config/settings.php";

use \Web\Database\Count;
use \Web\Database\Read;

/**
 * represents a thread in forum
 * thread contains forumposts
 * thread is part of forumsubject
 *
 * @author Mikael Holmbom
 * @version 1.0
 */
class ForumThread extends DataAccessObject 
	implements iForumContent 
{

	# this id
	private $id;
	# this subject containing this thread
	private $subject_fk;
	# this topic
	private $topic;
	# original creator of this thread
	private $creator_fk;

	/**
	 * @param topic int this threads topic
	 */
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
	 * @param int id value
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
	 * @return \Web\Database\DAO\ForumSubject this threads subject
	 */
	public function getSubject(){
		return Read::subjects($this->getSubjectFK())[0];
	}
	/**
	 * @return \Web\Database\DAO\ForumUser last attributor of this thread
	 */
	public function getLastAttributor(){
		return Read::lastAttributor($this->getPrimaryKey());
	}
	/**
	 *
	 * @return \Web\Database\DAO\ForumUser the cretor of this thread
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
	 * @return int amount of posts
	 */
	public function getSize(){
		return Count::postsBy($this);
	}
	/**
	 * get the index of the current last page containing posts
	 * index is affected by the setting: posts_per_page
	 * @return int page index as integer
	 */
	function getLastPageIndex(){
		return ceil($this->getSize() / $_SESSION['settings']->value("posts_per_page"));
	}

	/**
	 * @return \Web\database\DAO\ForumPost this threads last post
	 */
	function getLastPost(){
		return $this->getPosts()[($this->getSize()-1)];
	}

}
