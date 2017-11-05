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
namespace Web\Database;

# password crypt salt
$GLOBALS['database']['crypt_salt'] = 'd4';

$GLOBALS['database']['schema'] = "proj";
$GLOBALS['database']['table']['forumusers'] = $GLOBALS['database']['schema'] . ".forumusers";
$GLOBALS['database']['table']['forumthreads']  = $GLOBALS['database']['schema'] . ".forumthreads";
$GLOBALS['database']['table']['subjects'] = $GLOBALS['database']['schema'] . ".forumsubjects";
$GLOBALS['database']['table']['forumposts'] = $GLOBALS['database']['schema'] . ".forumposts";
$GLOBALS['database']['table']['roles'] = $GLOBALS['database']['schema'] . ".roles";
$GLOBALS['database']['table']['news'] = $GLOBALS['database']['schema'] . ".news";

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
    $servername     = "127.0.0.1";
    $port           = "5432";
    $dbname         = "postgres";
    $username       = "postgres";
    $password       = "password";

    $conn_str = "host=" . $servername
    . " port="          . $port
    . " dbname="        . $dbname
    . " user="          . $username
    . " password="      . $password;

    return pg_connect($conn_str);
}

/**
* determine if a user already exists in database
* @param user
*/
function exists(\Web\Database\DAO\ForumUser $user)
{
    $exists = False;

    $db_conn = connect();
    if ($db_conn) {
        $table = $GLOBALS['database']['table']['forumusers'];
        $res = pg_query_params(
            $db_conn,
            "SELECT name, email FROM $table "
            . " WHERE name = $1"
            . "  OR email = $2 ;",
            [$user->getName(), $user->getEmail()]
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
