<?php

namespace Web\Database\DAO;

require_once PATH_ROOT_ABS."database/Read.php";
require_once PATH_ROOT_ABS."database/dao/DataAccessObject.class.php";

use \Web\Database\Read;

/**
 * handles news in forum
 *
 * @author Mikael Holmbom
 * @version 1.0
 */
class News extends DataAccessObject 
{

    private $id = null;
    private $author_pk = null;
    private $title = null;
    private $message = null;
    private $created = null;

    function __construct()
    {

    }

    function setAuthorPK($author_pk)
    {
        $this->author_pk = $author_pk;
        return $this;
    }
    function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
    function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }
    function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    function getAuthorPK()
    {
        return $this->author_pk;
    }
    function getAuthor()
    {
        return $this->getAuthorPK() == null
            ? null
            : Read::forumUserById($this->getAuthorPK());
    }
    function getTitle()
    {
        return $this->title;
    }
    function getMessage()
    {
        return $this->message;
    }
    function getCreated()
    {
        return $this->created;
    }

    function getPrimaryKey()
    {
        return $this->id;
    }

}
