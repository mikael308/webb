<?php
namespace Database;

/**
 * delete data from database
 * @author Mikael Holmbom
 * @version 1.0
 */
class Delete{
    /**
     * delete forumuser matching with param user
     * @param user \Database\DAO\ForumUser to delete
     * @throws \RuntimeException on failed query
     * @return True if user was successfully deleted
     */
    public static function forumUser(
        \Database\DAO\ForumUser $user
    ) {
        $db_conn = connect();
        if ($db_conn){
            $query = "DELETE FROM " . $GLOBALS['dbtable_forumusers'] ." as fuser "
            . " WHERE fuser.name='" . $user->getPrimaryKey() . "';";
            $res = pg_query($db_conn, $query);
            if($res){
                pg_free_result($res);
                return True;    
            } else {
                throw new RuntimeException(pg_last_error($db_conn));
            }
            
        }
        return False;
    }
    /**
     * deletes forumpost from database
     *
     * @param post \Database\DAO\ForumPost to delete
     * @throws \RuntimeException on failed query
     * @return True if deletion was successful
     */
    public static function forumPost(
        \Database\DAO\ForumPost $post
    ) {
        $db_conn = connect();
        if ($db_conn){
            $query = "DELETE FROM " . $GLOBALS['dbtable_forumposts'] . " as fpost "
                . " WHERE fpost.id='" . $post->getPrimaryKey() . "';";
            $res = pg_query($db_conn, $query);
            if($res){
                pg_free_result($res);
                return True;
            } else {
                throw new RuntimeException(pg_last_error($db_conn));
            }
        }
        return False;
    }
    
} # !DELETE