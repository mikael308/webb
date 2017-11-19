<?php
namespace Web\Database;

use Web\Database\DAO\ForumPost;
use Web\Database\DAO\ForumSubject;
use Web\Database\DAO\ForumThread;
use Web\Database\DAO\News;
require_once PATH_ROOT_ABS."framework/request/page.php";

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
            $table = $GLOBALS['database']['table']['forumposts'];
            $res = pg_query_params(
                $db_conn,
                "INSERT INTO $table (author, thread, message, created) "
                . " VALUES($1, $2, $3, now());",
                [
                    $post->getAuthorFK(),
                    $post->getThreadFK(),
                    $post->getMessage()
                ]
            );
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
     * @return ForumThread the persisted thread with id
     */
    public static function forumThread(
        \Web\Database\DAO\ForumThread $thread
    ) {
        $resThread = null;
        $db_conn = connect();
        if ($db_conn) {
            $table = $GLOBALS['database']['table']['forumthreads'];
            $res = pg_query_params(
                $db_conn,
                "INSERT INTO $table (subject, topic)"
                . " VALUES($1, $2) "
                . " RETURNING id ;",
                [
                    $thread->getSubject()->getPrimaryKey(),
                    $thread->getTopic()
                ]
            );
            if ($res) {
                $data = pg_fetch_object($res, 0);
                $resThread = $thread;
                $resThread->setId($data->id);
                
                pg_free_result($res);
                
                return $resThread;
                
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return null;
    }
    /**
     * persist a user to database
     * @param user \Web\Database\Dao\ForumUser the user to persist
     * @param passw string requested password to persist
     * @return \Web\Database\DAO\ForumUser|null the persisted ForumUser with id
     */
    public static function forumUser(
        \Web\Database\DAO\ForumUser $user,
        $passw
    ) {
        if (exists($user))
            throw new \Exception("username already exists");

        $db_conn = connect();
        if ($db_conn) {
            $banned_val = $user->isBanned() ? "TRUE" : "FALSE";
            
            $salt = $GLOBALS['database']['crypt_salt'];
            $crypt_passw = crypt($passw, $salt);

            $table = $GLOBALS['database']['table']['forumusers'];
            $res = pg_query_params(
                $db_conn,
                "INSERT INTO $table "
                . " (name, email, role, banned, password, registered) "
                . " VALUES($1, $2, $3, $4, $5, $6)"
                . " RETURNING id;",
                [
                    $user->getName(),
                    $user->getEmail(),
                    $user->getRole(),
                    $banned_val,
                    $crypt_passw,
                    $user->getRegistered()
                ]
            );
            if ($res) {
                $data = pg_fetch_object($res, 0);
                pg_free_result($res);
                $user->setId($data->id);
                return $user;
                
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return null;
    }

    public static function forumSubject(
        ForumSubject $subject
    ) {
        $db_conn = connect();
        if ($db_conn) {
            $table = $GLOBALS['database']['table']['subjects'];
            $res = pg_query_params(
                $db_conn,
                "INSERT INTO $table (topic, subtitle) "
                . " VALUES($1, $2);",
                [
                    $subject->getTopic(),
                    $subject->getSubtitle()
                ]
            );
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
     * persist news to database
     * @param news News news instance to persist
     * @return true if persist was successful
     */
    public static function news(
        \Web\Database\DAO\News $news
    ) {
        $db_conn = connect();
        if ($db_conn) {
            $table = $GLOBALS['database']['table']['news'];
            $res = pg_query_params(
                $db_conn,
                "INSERT INTO $table "
                . " (author, title, message, created) "
                    . " VALUES($1, $2, $3, 'now()');",
                [
                    $news->getAuthorPK(),
                    $news->getTitle(),
                    $news->getMessage()
                ]
            );
            if ($res) {
                pg_free_result($res);
                return True;
                    
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return False;
    }

} # ! PERSIST
