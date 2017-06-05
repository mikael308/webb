<?php

	/**
	 * update data in database
	 * @author Mikael Holmbom
	 * @version 1.0
 	 */
	class update{
		/**
		 * update forumser
		 * @param user the user to update
		 * @throws RuntimeException on failed query
		 * @return True if the user was updated successfully
		 */
		public static function forumUser(ForumUser $user){
			$db_conn = connect();
			if($db_conn){
				$banned_val = $user->isBanned() ? "TRUE" : "FALSE";
				
				$query = " UPDATE " . $GLOBALS['dbtable_forumusers'] . " "
				 . " SET "
				 . " email='" 	. $user->getEmail() . "', "
				 . " banned='" 	. $banned_val . "' "
				 
				 . " WHERE name='" . $user->getPrimaryKey() . "';";
				 
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
		 * update existring forumpost
		 * @param post the post to update
		 * @throws RuntimeException on failed query
		 * @return True if update was successful 
		 */
		public static function forumPost(ForumPost $post){
			$db_conn = connect();
			if($db_conn){
				$query = " UPDATE " . $GLOBALS['dbtable_forumposts'] . " "
					. " SET "
					. " message='". $post->getMessage() ."', "
					. " edited=now() "
					. " WHERE id='" . $post->getId() . "' ;";	
					
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
		
	} # ! UPDATE


?>