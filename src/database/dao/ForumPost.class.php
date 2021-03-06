<?php

namespace Web\Database\DAO;

require_once PATH_ROOT_ABS."database/dao/DataAccessObject.class.php";
require_once PATH_ROOT_ABS."database/Read.php";
require_once PATH_ROOT_ABS."database/Count.php";

use \Web\Database\Read;

/**
 * defines a post in a thread
 *
 * @author Mikael Holmbom
 * @version 1.0
 */
class ForumPost extends DataAccessObject
{

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
    public function __construct() 
    {
        
    }
    
    public function getId() 
    {
        return $this->id;
    }
    public function getAuthorFK() 
    {
        return $this->author_fk;
    }
    public function getMessage() 
    {
        return $this->message;
    }
    public function getCreated() 
    {
        return $this->created;
    }
    public function getEdited() 
    {
        return $this->edited;
    }
    public function getThreadFK() 
    {
        return $this->thread_fk;
    }
    public function getPrimaryKey() 
    {
        return $this->getId();
    }
    public function setId($id) 
    {
        $this->id = $id;
        return $this;
    }
    public function setAuthor(\Web\Database\DAO\ForumUser $user) 
    {
        $this->setAuthorFK($user->getPrimaryKey());
        return $this;
    }
    public function setAuthorFK($author_fk)
    {
        $this->author_fk = $author_fk;
        return $this;
    }
    public function setMessage($message) 
    {
        $this->message = $message;
        return $this;
    }
    public function setCreated($created) 
    {
        $this->created = $created;
        return $this;
    }
    public function setEdited($edited) 
    {
        $this->edited = $edited;
        return $this;
    }
    public function setThreadFK($thread_fk) 
    {
        $this->thread_fk = $thread_fk;
        return $this;
    }

    /**
     * @return true if this post have been edited after it was created
     */
    public function isEdited()
    {
        return
            $this->getEdited() != null && 
            $this->getEdited() != $this->getCreated();
    }

    # DATABASE
    ####################
    
    /**
     * get the thread of this post
     * @return \Web\Database\DAO\ForumThread thread of this post as ForumThread instance
     */
    public function getThread() : ForumThread
    {
        return $this->getThreadFK() == null
            ? null
            : Read::thread($this->getThreadFK());
    }
    /**
     * get the author of this post
     * @return ForumUser author of this post
     */
    public function getAuthor() : ForumUser
    {
        return $this->getAuthorFK() == null
            ? null
            : Read::forumUserById($this->getAuthorFK());
    }

    public function getPageIndex() : int
    {
        return \Web\Database\Count::postPageIndex($this->getPrimaryKey());
    }

    public function toArray()
    {
        return [
            'id'        => $this->getId(),
            'author'    => $this->getAuthorFK(),
            'thread'    => $this->getThreadFK(),
            'message'   => $this->getMessage(),
            'created'   => $this->getCreated(),
            'edited'    => $this->getEdited()
        ];
    }

}
