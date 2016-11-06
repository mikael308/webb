<?php

require_once 'classes/DataAccessObject.class.php';

/**
 *
 * @author Mikael Holmbom
 * @version 1.0
 */
class ForumPost extends DataAccessObject{

	# this id
	private $id;
	# the user who posted this post
	private $author;
	# posts message
	private $message;
	# timestamp when this post was written
	private $created;
	private $thread;
	
	
	/**
	 * @param author
	 * @param message
	 * @param created
	 */
	public function __construct(){
		
		
	}
	
	public function getId(){
		return $this->id;
	}
	public function getAuthor(){
		return $this->author;
	}
	public function getMessage(){
		return $this->message;
	}
	public function getCreated(){
		return $this->created;
	}
	public function getThread(){
		return $this->thread;
	}
	public function getPrimaryKey(){
		return $this->getId();
	}
	public function setId($id){
		$this->id = $id;
	}
	public function setAuthor($author){
		$this->author = $author;
	}
	public function setMessage($message){
		$this->message = $message;
	}
	public function setCreated($created){
		$this->created = $created;
	}
	public function setThread($thread_id){
		$this->thread = $thread_id;
	}

}


?>
