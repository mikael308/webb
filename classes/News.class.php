<?php

require_once 'classes/DataAccessObject.class.php';

/**
 * Programvaruteknik,	Webbprogrammering 7.5hp
 *
 * @author Mikael Holmbom
 * @version 1.0
 */ 
class News extends DataAccessObject {

	private $id = NULL;
	private $author = NULL;
	private $title = NULL;
	private $message = NULL;
	private $created = NULL;

	function __construct(){

	}

	function setAuthor($author){
		$this->author = $author;
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
	function getAuthor(){
		return $this->author;
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
