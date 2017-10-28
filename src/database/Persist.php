<?php
namespace Web\Database;

use Web\Database\DAO\ForumPost;
use Web\Database\DAO\ForumThread;
use Web\Database\DAO\News;

/**
 * persist data to database
 * @author Mikael Holmbom
 * @version 1.0
 */
class Persist
{
    /**
     * persist forumpost to database
     * @param thread ForumThread containing thread
     * @param post ForumPost the post to persist
     * @return True if post was inserted in database successfully
     */
    public static function forumPost(
        \Web\Database\DAO\ForumPost $post
    ) {
        $db_conn = connect();
        if ($db_conn) {
            $query = "INSERT INTO " . $GLOBALS['database']['table']['forumposts']
                . " (author, thread, message, created) "
                . " VALUES('"
                .       $post->getAuthorFK() .  "','"
                .       $post->getThreadFK() . "','"
                .       $post->getMessage()
                .       "', now());";

            $res = pg_query($db_conn, $query);
            if ($res) {
                pg_free_result($res);
                return True;

            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return False;
    }
    /**
     * persist forumthread to database
     * @param thread forumthread to persist
     * @return the persisted thread with id
     */
    public static function forumThread(
        \Web\Database\DAO\ForumThread $thread
    ) {
        $resThread = null;
        $db_conn = connect();
        if($db_conn){
            $query = "INSERT INTO " . $GLOBALS['database']['table']['forumthreads'] 
                . " (subject, topic)"
                . " VALUES('".$thread->getSubject()->getPrimaryKey()."', '".$thread->getTopic()."') "
                . " RETURNING id ;";
                
            $res = pg_query($db_conn, $query);
            if($res){
                $data = pg_fetch_object($res, 0);
                $resThread = $thread;
                $resThread->setId($data->id);
                
                pg_free_result($res);
                
                return $resThread;
                
            } else {
                throw new RuntimeException(pg_last_error($db_conn));
            }
        }
        return null;
    }
    /**
     * persist a user to database
     * @param user \Web\Database\Dao\ForumUser the user to persist
     * @param passw string requested password to persist
     */
    public static function forumUser(
        \Web\Database\DAO\ForumUser $user,
        $passw
    ) {
        $db_conn = connect();
        if($db_conn){
            $banned_val = $user->isBanned() ? "TRUE" : "FALSE";
            
            $crypt_passw = crypt(
                $passw,
                $GLOBALS["crypt_salt"]
            );
            $query = "INSERT INTO " . $GLOBALS['database']['table']['forumusers']
                . " (name, email, role, banned, password, registered) "
                . " VALUES('".$user->getName()."',"
                . "'" . $user->getEmail() . "', "
                . "'" . $user->getRole() . "',"
                . "'" . $banned_val . "', " 
                . "'" . $crypt_passw . "', "
                . "'" . $user->getRegistered() . "');";
            
            $res = pg_query($db_conn, $query);
            if($res){
                pg_free_result($res);
                return True;
                
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return False;
    }
    /**
     * persist news to database
     * @param news News news instance to persist
     * @return true if persist was successful
     */
    public static function news(
        \Web\Database\DAO\News $news
    ) {
        $db_conn = connect();
        if($db_conn){
            echo 'persisting author ' . $news->getAuthorPK();
            $query = "INSERT INTO " . $GLOBALS['database']['table']['news']
            . " (author, title, message, created) "
                    . " VALUES("
                    . "'" . $news->getAuthorPK() . "',"
                    . "'" . $news->getTitle() . "', "
                    . "'" . $news->getMessage() . "', "
                    . "'now()');";

            $res = pg_query($db_conn, $query);
            if($res){
                pg_free_result($res);
                return True;
                    
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return False;
    }
} # ! PERSIST

?>
