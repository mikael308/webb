<?php

namespace Web\Database;

use \Web\Database\DAO\ForumUser;
use \Web\Database\DAO\ForumSubject;
use \Web\Database\DAO\ForumThread;
use function Web\Framework\Request\getSettings;

/**
 * 
 * @author Mikael Holmbom
 * @version 1.0
 */
class Count
{
    /**
     * count the number of forumthreads created by specific user
     * @throws \RuntimeException on failed query
     * @param user \Web\Database\DAO\ForumUser the specific user
     */
    public static function forumThreads(
        ForumUser $user
    ) {
        $table = $GLOBALS['database']['table']['forumthreads'];
        $join_table = $GLOBALS['database']['table']['forumposts'];
                                                                                                                                    
        return Count::query(
            " FROM $table AS t "
            . " LEFT JOIN $join_table as p "
            . " ON p.thread=t.id "
            . " WHERE p.author = '".$user->getPrimaryKey()."' "
            . " AND p.id IN ( " 
                # the first posts in every thread
                . " SELECT p.id "
                . " FROM $join_table p "
                . " WHERE p.id =  ( "
                    . " SELECT p2.id "
                    . " FROM $join_table p2 "
                    . " WHERE p2.thread=p.thread "
                    . " ORDER BY p2.created ASC "
                    . " LIMIT 1 "
                . " ) "
            . " );"
        );
    }
    /**
     * count the number of forumposts created by specific user
     * @throws \RuntimeException on failed query
     * @param user string the specific user
     */
    public static function forumPosts(
        ForumUser $user
    ) {
        $table = $GLOBALS['database']['table']['forumposts'];
        return Count::query(
            " FROM $table "
            . " WHERE author='" . $user->getPrimaryKey() . "';"
        );
    }

    /**
     * get the index of post in thread ordered by created attribute
     * @param post_pk string primary key to request post
     * @throws \RuntimeException on failed query
     * @return int index of post
     */
    public static function postPageIndex(
        $post_pk
    ) {
        $table = $GLOBALS['database']['table']['forumposts'];

        $count = Count::query(
            " FROM $table "
            . " WHERE thread='". Read::forumPost($post_pk)->getThread()->getPrimaryKey() ."' "
            . " AND created <= ( "
            .       " SELECT created "
            .       " FROM $table "
            .       " WHERE id='".$post_pk."' "
            . " );"
        );
        return ceil($count / getSettings()->value("posts_per_page"));
    }
    /**
     * count results from database query
     * @param string  $query the query defining from and where sql statements
     * @throws \RuntimeException on failed query
     * @return int num rows from query result|null
     */
    public static function query(
        $query
    ) {
        $db_conn = connect();
        if ($db_conn) {
            $res = pg_query(
                $db_conn,
                "SELECT COUNT(*) " . $query
            );
            if ($res) {
                $num = pg_fetch_object($res)->count;
                pg_free_result($res);

                return (int) $num;
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return null;
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
        $table = $GLOBALS['database']['table']['forumposts'];
        $n_posts = Count::query(
            " FROM $table "
            . " WHERE thread='" . $thread->getPrimaryKey() ."';"
        );
        $max_pages = ceil($n_posts / $_SESSION['settings']->value("posts_per_page"));
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
        $table = $GLOBALS['database']['table']['forumthreads'];
        
        $n_threads = Count::query(
            " FROM $table AS t "
            . " WHERE t.subject='" . $subject->getPrimaryKey() . "';"
        );
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
        $table = $GLOBALS['database']['table']['forumposts'];
        return Count::query(
            " FROM $table AS p "
            . " WHERE p.thread='".$thread->getPrimaryKey()."';"
        );
    }

} # ! COUNT
