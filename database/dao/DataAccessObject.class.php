<?php

	require_once "./database/database.php";


	/**
	 * abstract class defining a object from database
	 *
	 * @author Mikael Holmbom
	 * @version 1.0
	 */
	abstract class DataAccessObject{

		abstract public function getPrimaryKey();

	}

?>
