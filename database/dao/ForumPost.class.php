<?php

require_once "./database/dao/DataAccessObject.class.php";


/**
 * defines a post in a thread
 *
 * @author Mikael Holmbom
 * @version 1.0
 */
class ForumPost extends DataAccessObject{

	# this id
	private $id;
	# the user who posted this post
	private $author_fk;
	# posts message
	private $message;
	# timestamp when this post was created
	private $created;
	# timestamp when this post was last edited
	private $edited;
	# foreign key to this posts related thread
	private $thread_fk;
	
	
	/**
	 */
	public function __construct(){
		
		
	}
	
	public function getId(){
		return $this->id;
	}
	public function getAuthorFK(){
		return $this->author_fk;
	}
	public function getMessage(){
		return $this->message;
	}
	public function getCreated(){
		return $this->created;
	}
	public function getEdited(){
		return $this->edited;
	}
	public function getThreadFK(){
		return $this->thread_fk;
	}
	public function getPrimaryKey(){
		return $this->getId();
	}
	public function setId($id){
		$this->id = $id;
	}
	public function setAuthorFK($author_fk){
		$this->author_fk = $author_fk;
	}
	public function setMessage($message){
		$this->message = $message;
	}
	public function setCreated($created){
		$this->created = $created;
	}
	public function setEdited($edited){
		$this->edited = $edited;
	}
	public function setThreadFK($thread_fk){
		$this->thread_fk = $thread_fk;
	}

	/**
	 * @return true if this post have been edited after it was created
	 */
	public function isEdited(){
		return
			$this->getEdited() != NULL
			&& $this->getEdited() != $this->getCreated();
	}

	# DATABASE
	####################
	
	/**
	 * get the thread of this post
	 * @return thread of this post as ForumThread instance
	 */
	public function getThread(){
		if($this->getThreadFK() == NULL) return NULL;
		
		return Read::thread($this->getThreadFK());
	}
	/**
	 * get the author of this post
	 * @return author of this post as ForumUser instance
	 */
	public function getAuthor(){
		if($this->getAuthorFK() == NULL) return NULL;
		
		return Read::forumUser($this->getAuthorFK());
	}

}


?>
