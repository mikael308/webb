<?php

namespace Web\Database;

use \Web\Database\DAO\ForumUser;
use \Web\Database\DAO\ForumSubject;
use \Web\Database\DAO\ForumThread;

/**
 * 
 * @author Mikael Holmbom
 * @version 1.0
 */
class Count{
    /**
     * count the number of forumthreads created by specific user
     * @throws \RuntimeException on failed query
     * @param user \Web\Database\DAO\ForumUser the specific user
     */
    public static function forumThreads(
        ForumUser $user
    ) {
        $query =
            " FROM ".$GLOBALS['database']['table']['forumthreads']." AS t "
            . " LEFT JOIN ".$GLOBALS['database']['table']['forumposts']." as p "
            . " ON p.thread=t.id "
            . " WHERE p.author = '".$user->getPrimaryKey()."' "
            . " AND p.id IN "
            . " ( " # the first posts in every thread
                . " SELECT p.id "
                . " FROM ".$GLOBALS['database']['table']['forumposts']." p "
                . " WHERE p.id = "
                . " ( "
                    . " SELECT p2.id "
                    . " FROM ".$GLOBALS['database']['table']['forumposts']." p2 "
                    . " WHERE p2.thread=p.thread "
                    . " ORDER BY p2.created ASC "
                    . " LIMIT 1 "
                . " ) "
            . " ) "
                . " ;";
                                                                                                                                                    
        return count::query($query);
    }
    /**
     * count the number of forumposts created by specific user
     * @throws \RuntimeException on failed query
     * @param user string the specific user
     */
    public static function forumPosts(
        ForumUser $user
    ) {
        $query =
            " FROM " . $GLOBALS['database']['table']['forumposts']
            . " WHERE author='" . $user->getPrimaryKey() . "'"
            . " ;";

        return count::query($query);
    }

    /**
     * get the index of post in thread ordered by created attribute
     * @param post_pk string primary key to request post
     * @throws \RuntimeException on failed query
     * @return \Web\Database\DAO\ForumUser index
     */
    public static function postPageIndex(
        $post_pk
    ) {
        $query = 
             " FROM " . $GLOBALS['database']['table']['forumposts']
            . " WHERE thread='". Read::forumPost($post_pk)->getThread()->getPrimaryKey() ."' "
            . " AND created <= ( "
            .       " SELECT created "
            .       " FROM " . $GLOBALS['database']['table']['forumposts']
            .       " WHERE id='".$post_pk."' "
            .   " ) "
            . " ;";
        return ceil(count::query($query) / $_SESSION['settings']->value("posts_per_page"));
    }
    /**
     * count results from database query
     * @param string  $query the query defining from and where sql statements
     * @throws \RuntimeException on failed query
     * @return int num rows from query result|NULL
     */
    public static function query(
        $query
    ) {
        $db_conn = connect();
        if($db_conn){
            $q = "SELECT COUNT(*) " . $query;
            $res = pg_query($db_conn, $q);
            if($res){
                $num = pg_fetch_object($res)->count;
                pg_free_result($res);

                return (int) $num;
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return NULL;
    }
    
    /**
     * get the amount of pages in thread
     * @param thread count this threads pages
     * @throws \RuntimeException on failed query
     * @param amount integer of pages
     */
    public static function maxPagesThread(
        ForumThread $thread
    ) {
        $query =
            " FROM " . $GLOBALS['database']['table']['forumposts']
            . " WHERE thread='" . $thread->getPrimaryKey() ."'"
            .";";
        $n_posts        = Count::query($query);
        $max_pages      = ceil($n_posts / $_SESSION['settings']->value("posts_per_page"));
        return $max_pages;
    
    }
    /**
     * get the amount of pages in subject
     * @param subject count this subjects pages
     * @throws \RuntimeException on failed query
     * @return integer amount of pages
     */
    public static function maxPagesSubject(
        ForumSubject $subject
    ) {
        $query =
            " FROM " . $GLOBALS['database']['table']['forumthreads'] . " AS t "
            . " WHERE t.subject='" . $subject->getPrimaryKey() . "'"
            . ";";

        $n_threads = Count::query($query);
        $threads_per_page = $_SESSION['settings']->value("threads_per_page");
        $max_pages = ceil($n_threads / $threads_per_page);

        return $max_pages;
    }

    /**
     * get the amount of posts in param thread
     * @return int as number of posts
     */
    public static function postsBy(
        ForumThread $thread
    ) {
        $query =
            " FROM " . $GLOBALS['database']['table']['forumposts'] . " AS p "
            . " WHERE p.thread='".$thread->getPrimaryKey()."'"
            . ";";

        return Count::query($query);
    }
} # ! COUNT
