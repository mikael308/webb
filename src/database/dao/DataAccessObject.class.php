<?php

namespace Web\Database\DAO;

require_once PATH_ROOT_ABS."database/database.php";


/**
 * abstract class defining a object from database
 *
 * @author Mikael Holmbom
 * @version 1.0
 */
abstract class DataAccessObject
{

    abstract public function getPrimaryKey();

    /**
     * @return mixed get this id
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

}
