<?php



	class persist{
		/**
		 * persist forumpost to database
		 * @param thread containing thread
		 * @param post the post to persist
		 * @return True if post was inserted in database successfully
		 */
		public static function forumPost(ForumThread $thread, ForumPost $post){
			
			$db_conn = connect();
			if($db_conn){
				$query = "INSERT INTO " . $GLOBALS['dbtable_forumposts'] 
					. " (author, thread, message, created) "
					. " VALUES('".$post->getAuthor()->getPrimaryKey()."','".$thread->getId()."','".$post->getMessage()."', now());";
					
				$res = pg_query($db_conn, $query);
				if($res){
					pg_free_result($res);
					return True;
					
				} else {
					echo pg_last_error($db_conn);
				}
			}
			return False;
		}
		/**
		 * persist forumthread to database
		 * @param thread forumthread to persist
		 */
		public static function forumThread(ForumThread $thread){
			$resThread = null;
			$db_conn = connect();
			if($db_conn){
				$query = "INSERT INTO " . $GLOBALS['dbtable_forumthreads'] 
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
					echo pg_last_error($db_conn);
				}
			}
			return NULL;
		}
		/**
		 * persist a user to database
		 * @param user the user to persist
		 * @param passw requested password to persist
		 */
		public static function forumUser(ForumUser $user, $passw){
			$db_conn = connect();
			if($db_conn){
				$banned_val = $user->isBanned() ? "TRUE" : "FALSE";
				
				$crypt_passw = crypt($passw,$GLOBALS['crypt_salt']);
				$query = "INSERT INTO " . $GLOBALS['dbtable_forumusers'] 
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
					echo pg_last_error($db_conn);
				}
			}
			return False;
		}
		/**
		 * persist news to database
		 * @param news news instance to persist
		 * @return true if persist was successful
		 */
		public static function news(News $news){
			$db_conn = connect();
			if($db_conn){
				echo 'persisting author ' . $news->getAuthorPK();
				$query = "INSERT INTO " . $GLOBALS['dbtable_news']
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
					echo pg_last_error($db_conn);
				}
			}
			return False;
		}
	} # ! PERSIST


?>