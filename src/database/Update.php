<?php
namespace Database;
/**
 * update data in database
 * @author Mikael Holmbom
 * @version 1.0
     */
class update
{
    /**
     * update forumser
     * @param user \Database\DAO\ForumUser the user to update
     * @throws \RuntimeException on failed query
     * @return True if the user was updated successfully
     */
    public static function forumUser(ForumUser $user){
        $db_conn = connect();
        if ($db_conn) {
            $banned_val = $user->isBanned() ? "TRUE" : "FALSE";
            
            $query = " UPDATE " . $GLOBALS['database']['table']['forumusers'] . " "
             . " SET "
             . " email='"   . $user->getEmail() . "', "
             . " banned='"  . $banned_val . "' "
             
             . " WHERE name='" . $user->getPrimaryKey() . "';";
             
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
     * update existring forumpost
     * @param post \Database\DAO\ForumPost the post to update
     * @throws \RuntimeException on failed query
     * @return True if update was successful 
     */
    public static function forumPost(\Database\DAO\ForumPost $post) 
    {
        $db_conn = connect();
        if ($db_conn) {
            $query = " UPDATE  " . $GLOBALS['database']['table']['forumposts'] 
                . " AS p "
                . " SET "
                . " message=$1 ,"
                . " edited=now() "
                . " WHERE p.id=$2;";

            $res = pg_query_params(
                $db_conn, 
                $query,
                array( 
                    $post->getMessage(), 
                    $post->getId()
                ));

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
