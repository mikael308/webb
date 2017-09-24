<?php

namespace Web\Database\DAO;

require_once "./database/Read.php";
require_once "./database/dao/DataAccessObject.class.php";

use \Web\Database\Read;
/**
 * handles news in forum
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
			Read::forumUser($this->getAuthorPK());

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
