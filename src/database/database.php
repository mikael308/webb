<?php
/**
*
* database helper functions
*
* to create connection with database @see connect()
*
* @author Mikael Holmbom
* @version 1.0
*/
namespace Database;

#require_once "../config/settings.php";
#require_once "Read.php";

# password crypt salt
$GLOBALS['crypt_salt'] = 'd4';

$GLOBALS['schema'] = "proj";
$GLOBALS['dbtable_forumusers'] = $GLOBALS['schema'] . ".forumusers";
$GLOBALS['dbtable_forumthreads']  = $GLOBALS['schema'] . ".forumthreads";
$GLOBALS['dbtable_subjects'] = $GLOBALS['schema'] . ".forumsubjects";
$GLOBALS['dbtable_forumposts'] = $GLOBALS['schema'] . ".forumposts";
$GLOBALS['dbtable_roles'] = $GLOBALS['schema'] . ".roles";
$GLOBALS['dbtable_news'] = $GLOBALS['schema'] . ".news";

function autoloadDAO()
{
    # autoload dao classes
    spl_autoload_register(function($class) {
    include './database/dao/' . $class . '.class.php';
    });
}


/**
* opens connection to database
*/
function connect()
{
    $servername 	= "localhost";
    $port 			= "5432";
    $dbname 		= "postgres";
    $username 		= "mikael";
    $password 		= "mydbpassw";

    $conn_str = "host=" 	. $servername
    . " port=" 			. $port
    . " dbname=" 		. $dbname
    . " user=" 			. $username
    . " password=" 		. $password;

    return pg_connect($conn_str);
}

/**
* determine if a user already exists in database
* @param user
*/
function exists(\Database\DAO\ForumUser $user)
{
    $exists = False;

    $db_conn = connect();
    if ($db_conn) {

    $query = "SELECT name FROM " . $GLOBALS['dbtable_forumusers']
    . " WHERE name = '". $user->getPrimaryKey() . "' ;";
    #TODO params
    $res = pg_query(
        $db_conn,
        $query
    );
    if ($res) {
        if (pg_num_rows($res)) {
            $exists = True;
        }
        pg_free_result($res);
    }

    }
    #TODO throw error on !$db_conn

    return $exists;
}
