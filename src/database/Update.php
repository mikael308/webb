<?php
namespace Web\Database;
/**
 * update data in database
 * @author Mikael Holmbom
 * @version 1.0
 */
class Update
{

    /**
     * update forumser
     * @param user \Web\Database\DAO\ForumUser the user to update
     * @throws \RuntimeException on failed query
     * @return True if the user was updated successfully
     */
    public static function forumUser(\Web\Database\DAO\ForumUser $user)
    {
        $db_conn = connect();
        if ($db_conn) {
            $banned_val = $user->isBanned() ? "TRUE" : "FALSE";
            $table = $GLOBALS['database']['table']['forumusers'];
             
            $res = pg_query_params(
                $db_conn,
                " UPDATE $table "
                . " SET email=$1, banned=$2 "
                . " WHERE name=$3;",
                [ $user->getEmail(), $banned_val, $user->getPrimaryKey() ]
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
     * update existring forumpost
     * @param post \Database\DAO\ForumPost the post to update
     * @throws \RuntimeException on failed query
     * @return True if update was successful 
     */
    public static function forumPost(\Web\Database\DAO\ForumPost $post)
    {
        $db_conn = connect();
        if ($db_conn) {
            $table = $GLOBALS['database']['table']['forumposts'];

            $res = pg_query_params(
                $db_conn, 
                " UPDATE  $table AS p "
                . " SET "
                . " message=$1,"
                . " edited=now() "
                . " WHERE p.id=$2;",
                [ $post->getMessage(), $post->getId() ]
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
    
} # ! UPDATE
