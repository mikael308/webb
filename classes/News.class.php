<?php

require_once 'classes/DataAccessObject.class.php';
require_once "database_read.php";

/**
 * Programvaruteknik,	Webbprogrammering 7.5hp
 *
 * @author Mikael Holmbom
 * @version 1.0
 */ 
class News extends DataAccessObject {

	private $id = NULL;
	private $author_pk = NULL;
	private $title = NULL;
	private $message = NULL;
	private $created = NULL;

	function __construct(){

	}

	function setAuthorPK($author_pk){
		$this->author_pk = $author_pk;
	}
	function setTitle($title){
		$this->title = $title;
	}
	function setMessage($message){
		$this->message = $message;
	}
	function setCreated($created){
		$this->created = $created;
	}
	function setId($id){
		$this->id = $id;
	}
	function getAuthorPK(){
		return $this->author_pk;
	}
	function getAuthor(){
		return $this->getAuthorPK() == NULL ?
			NULL:
			read::forumUser($this->getAuthorPK());

	}
	function getTitle(){
		return $this->title;
	}
	function getMessage(){
		return $this->message;
	}
	function getCreated(){
		return $this->created;
	}

	function getPrimaryKey(){
		return $this->id;
	}
}


?>
