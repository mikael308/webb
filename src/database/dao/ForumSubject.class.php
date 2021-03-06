<?php

namespace Web\Database\DAO;

require_once PATH_ROOT_ABS."database/dao/DataAccessObject.class.php";
require_once PATH_ROOT_ABS."database/Read.php";
require_once PATH_ROOT_ABS."database/dao/iForumContent.php";

use Web\Database\Read;

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
     * @param id string new id value
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * set this topic
     * @param topic string new topic value
     * @return $this
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
        return $this;
    }

    /**
     * set this subtitle
     * @param id string new subtitle value
     * @return $this
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
        return $this;
    }
    /**
     * get this id
     * @return string this topic value
     */
    public function getTopic()
    {
        return $this->topic;
    }
    /**
     * get this subtitle
     * @return string this subtitle value
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }
    /**
     * get this primary key
     * @return string this primary key value
     */
    public function getPrimaryKey()
    {
        return $this->id;
    }
    
    public function getSize()
    {
        return sizeof($this->getThreads());
    }
    /**
     * this subjects threads
     * @return array ForumThreads as array
     */
    public function getThreads()
    {
        return Read::threadsOfSubject(
            $this->getPrimaryKey()
        );
    }

    /**
     * get the index of the current last page containing posts
     * index is affected by the setting: posts_per_page
     * @return integer page index
     */
    function getLastPageIndex() : int
    {
        return ceil(
            $this->getSize()
            /
            $_SESSION['settings']->value("threads_per_page")
        );
    }
}
