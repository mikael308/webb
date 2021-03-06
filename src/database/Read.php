<?php
namespace Web\Database;

use Web\Database\DAO\ForumUser;

require_once PATH_ROOT_ABS."database/database.php";
require_once PATH_ROOT_ABS."database/Parse.php";


/**
 * read entries from database
 * @author Mikael Holmbom
 * @version 1.0
 */
class Read
{

    /**
     * read role with specific id\n
     * @throws \RuntimeException on failed query
     * @param id
     */
    public static function role($id)
    {
        $resRole = null;
        $db_conn = connect();
        if ($db_conn) {
            $table = $GLOBALS['database']['table']['roles'];
            $res = pg_query_params(
                $db_conn,
                " SELECT id, title "
                . " FROM $table "
                . " WHERE id=$1;",
                [ $id ]
            );
            if ($res) {
                if (pg_num_rows($res) == 1) {
                    $resRole = Parse::toRole(pg_fetch_object($res));
                }

                pg_free_result($res);
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return $resRole;
    }

    public static function subject(
        $subject_id = null
    ) {
        return Read::subjects($subject_id)[0];
    }
    /**
    * read all subjects
    * @param spec string specify query
    * @throws \RuntimeException on failed query
    * @return array of search result
    */
    public static function subjects($subject_id = null)
    {
        $subjs = null;
        $db_conn = connect();
        if ($db_conn) {
            $res = null;
            $table = $GLOBALS['database']['table']['subjects'];
            if ($subject_id == null) {
                $res = pg_query(
                    $db_conn,
                    " SELECT subject.id, subject.topic, subject.subtitle "
                     . " FROM $table as subject;");
            } else {
                $res = pg_query_params(
                    $db_conn,
                    " SELECT subject.id, subject.topic, subject.subtitle "
                     . " FROM $table as subject "
                     . " WHERE subject.id=$1;",
                    [ $subject_id ]
                );
            }

            if ($res) {
                $subjs = array();
                for ($i = 0; $i < pg_num_rows($res); $i++) {
                    $subjs[] = Parse::toSubject(pg_fetch_object($res, $i));
                }

                pg_free_result($res);
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return $subjs;
    }
    /**
     * read requested subject from database
     * @throws \RuntimeException on failed query
     * @return array requested forumsubject, if not found null is returned
     */
    public static function threadsOfSubject($subject_id)
    {
        $resThreadArr = array();
        $db_conn = connect();
        if ($db_conn) {
            $table = $GLOBALS['database']['table']['forumthreads'];

            $res = pg_query_params(
                $db_conn,
                " SELECT thread.id, thread.subject, thread.topic "
                . " FROM $table AS thread "
                . " WHERE thread.subject=$1;",
                array($subject_id)
            );
            if ($res) {
                $resThreadArr = array();
                $n_row = pg_num_rows($res);

                for ($i = 0; $i < $n_row; $i++) {
                    $resThreadArr[] = Parse::toThread(pg_fetch_object($res, $i));
                }

                pg_free_result($res);
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return $resThreadArr;
    }

    /**
     * read a specific thread from database
     * @param thread_id string id of thread to read
     * @throws \RuntimeException on failed query
     * @return \Web\Database\DAO\ForumThread the first found row in database, if no result was found null is returned
     */
    public static function thread($thread_id)
    {
        $thread = null;

        $db_conn = connect();
        if ($db_conn) {
            $table = $GLOBALS['database']['table']['forumthreads'];
            $res = pg_query_params(
                $db_conn,
                " SELECT thread.id, thread.subject, thread.topic "
                . " FROM $table AS thread "
                . " WHERE thread.id=$1;",
                [ $thread_id ]
            );
            if ($res) {
                if (pg_num_rows($res) > 0) {
                    $thread = Parse::toThread(pg_fetch_object($res, 0));
                }

                pg_free_result($res);
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return $thread;
    }

    /**
     * read news from database ordered by created value descending
     * @throws \RuntimeException on failed query
     * @return array of news instances matching query
     */
    public static function news()
    {
        $news_arr = [];

        $db_conn = connect();
        if ($db_conn) {
            $table = $GLOBALS['database']['table']['news'];
            $res = pg_query(
                $db_conn,
                "SELECT news.id, news.author, news.title, news.message, news.created "
                . " FROM $table AS news ORDER BY news.created DESC;"
            );
            if ($res) {
                $n_rows = pg_num_rows($res);
                for ($i = 0; $i < $n_rows; $i++) {
                    $news_arr[] = Parse::toNews(pg_fetch_object($res, $i));
                }
                pg_free_result($res);
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return $news_arr;
    }

    /**
     * read posts related to thread with id as param from database
     * ordered by created attr
     * @throws \RuntimeException on failed query
     * @param thread_id string the id of the thread to read
     * @return array of \Database\DAO\ForumThread
     */
    public static function postsFromThread($thread_id)
    {
        $post_arr = array();
        $db_conn = connect();
        if ($db_conn) {
            $table = $GLOBALS['database']['table']['forumposts'];
            $res = pg_query_params(
                $db_conn,
                "SELECT post.id "
                . " FROM $table AS post "
                . " WHERE thread=$1 "
                . " ORDER BY post.created ASC;",
                [ $thread_id ]
            );
            if ($res) {
                $n_rows = pg_num_rows($res);
                for ($i = 0; $i < $n_rows; $i++) {
                    $data = pg_fetch_object($res, $i);

                    $post_arr[] = Read::forumPost($data->id);
                }
                pg_free_result($res);
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return $post_arr;
    }
    /**
     * read a specific forumpost from database\n
     * @param post_id string id of forumpost to read
     * @throws \RuntimeException on failed query
     * @return \Web\Database\DAO\ForumPost object. If requested forumpost was not found, null is returned\n
     */
    public static function forumPost(
        $post_id
    ) {
        $post = null;
        $db_conn = connect();

        if ($db_conn) {
            $table = $GLOBALS['database']['table']['forumposts'];
            $res = pg_query_params(
                $db_conn,
                "SELECT post.id, post.thread, post.message, post.created, post.edited, post.author "
                . " FROM $table AS post "
                . " WHERE post.id=$1;",
                [ $post_id ]
            );
            if ($res) {
                $post = Parse::toPost(pg_fetch_object($res));

                pg_free_result($res);
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return $post;
    }

    /**
     * @param $user_name string the name of the user
     * @return null|ForumUser
     */
    public static function forumUserByName($user_name)
    {
        $user = null;
        $db_conn = connect();
        if ($db_conn) {
            $table = $GLOBALS['database']['table']['forumusers'];
            $join_table = $GLOBALS['database']['table']['roles'];

            $res = pg_query_params(
                $db_conn,
                "SELECT fuser.id, fuser.name, fuser.email, roles.title, fuser.banned, fuser.registered "
                . " FROM $table AS fuser "
                . " LEFT JOIN $join_table "
                . "   ON fuser.role=roles.id "
                . " WHERE fuser.name=$1;",
                [ $user_name ]
            );
            if ($res) {
                if (pg_num_rows($res) == 0) return null;
                $data = pg_fetch_object($res, 0);
                $user = Parse::toUser($data);

                pg_free_result($res);

            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return $user;
    }
    
    /**
     * read forumusers from database
     * @param whereclause string specify query
     * @throws \RuntimeException on failed query
     * @return \Web\Database\DAO\ForumUser of forumusers results from database
     */
    public static function forumUserById($user_id)
    {
        $user = null;
        $db_conn = connect();
        if ($db_conn) {
            $table = $GLOBALS['database']['table']['forumusers'];
            $join_table = $GLOBALS['database']['table']['roles'];
            
            $res = pg_query_params(
                $db_conn,
                "SELECT fuser.id, fuser.name, fuser.email, roles.title, fuser.banned, fuser.registered "
                . " FROM $table AS fuser "
                . " LEFT JOIN $join_table "
                . "   ON fuser.role=roles.id "
                . " WHERE fuser.id=$1;",
                [ $user_id ]
            );
            if ($res) {
                if (pg_num_rows($res) == 0) return null;
                $data = pg_fetch_object($res, 0);
                $user = Parse::toUser($data);

                pg_free_result($res);

            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return $user;
    }
    /**
     * read the creator of forumthread\n
     * creator is defined as the first post of a thread
     * @param thread string primary key of thread to find the creator of
     * @throws \RuntimeException on failed query
     * @return ForumUser the creator as ForumUser, if no creator was found null is returned
     */
    public static function creator($thread_fk)
    {
        if ($thread_fk == null || $thread_fk == "") return null;

        $creator = null;

        $db_conn = connect();
        if ($db_conn) {
            $table = $GLOBALS['database']['table']['forumposts'];
            $join_table = $GLOBALS['database']['table']['forumthreads'];
            $res = pg_query_params(
                $db_conn,
                "SELECT p.author "
                . " FROM $table AS p "
                . " LEFT JOIN $join_table AS t "
                . "     ON p.thread=t.id "
                . " WHERE p.thread=$1 "
                . " ORDER BY p.created ASC "
                . " LIMIT 1;",
                [ $thread_fk ]
            );
            if ($res) {
                if (pg_num_rows($res) > 0) {
                    $data = pg_fetch_object($res, 0);

                    if ($data != null) {
                        $creator = Read::forumUserById($data->author);
                    }
                }

                pg_free_result($res);
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }
        return $creator;
    }
    /**
     * get the last attributor of param thread
     * @param thread_pk string primary key of requested thread
     * @throws \RuntimeException on failed query
     * @return \Web\Database\DAO\ForumUser last attributor of thread as ForumUser
     */
    public static function lastAttributor($thread_pk)
    {
        if ($thread_pk == null || $thread_pk == "") return null;
        $lastAttributor = null;
        $db_conn = connect();
        if ($db_conn) {
            $table = $GLOBALS['database']['table']['forumposts'];
            $res = pg_query_params(
                $db_conn,
                "SELECT author "
                . " FROM $table"
                . " WHERE thread=$1 "
                . " ORDER BY created DESC "
                . " LIMIT 1;",
                [ $thread_pk ]
            );
            if ($res) {
                if (pg_num_rows($res) > 0) {
                    $data = pg_fetch_object($res, 0);
                    $lastAttributor = Read::forumUserById($data->author);
                }
            } else {
                throw new \RuntimeException(pg_last_error($db_conn));
            }
        }

        return $lastAttributor;
    }
} # ! READ
