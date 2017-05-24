<?php

	require_once "./database/database.php";

	autoloadDAO();
	
	/**
	 * read entries from database
	 * @author Mikael Holmbom
	 */

	class read{
		/**
		 * filter between database entry row and dataaccessobjects\n
		 * validates database value
		 * @param in value read from database entry
		 * @return valid string
		 */
		private static function clean($in){
			return htmlspecialchars($in);
		}
		/**
		 * translate database row to ForumThread
		 * @param data database row
		 * @return ForumThread of data
		 */
		private static function toThread($data){
			$thread = new ForumThread(read::clean($data->topic));
			$thread->setId(read::clean($data->id));
			$thread->setSubjectFK(read::clean($data->subject));
			
			return $thread;
		}
		/**
		 * translate database row to ForumSubject
		 * @param data database row
		 * @return ForumSubject of data
		 */
		private static function toSubject($data){
			$subj = new ForumSubject();
			$subj->setId(read::clean($data->id));
			$subj->setTopic(read::clean($data->topic));
			$subj->setSubtitle(read::clean($data->subtitle));
			
			return $subj;
		}
		/**
		 * translate database row to Role
		 * @param data database row
		 * @return Role of data
		 */
		private static function toRole($data){
			return new Role(read::clean($data->id), 
				read::clean($data->title));
		}
		/**
		 * translate database row to News
		 * @param data database row
		 * @return News of data
		 */
		private static function toNews($data){
			$news = new News();
			$news->setId(read::clean($data->id));
			$news->setAuthorPK(read::clean($data->author));
			$news->setTitle(read::clean($data->title));
			$news->setMessage(read::clean($data->message));
			$news->setCreated(read::clean($data->created));
			
			return $news;
		}
		/**
		 * translate database row to ForumPost
		 * @param data database row
		 * @return ForumPost of data
		 */
		private static function toPost($data){
			$post = new ForumPost();
			$post->setId(read::clean($data->id));
			$post->setAuthorFK(read::clean($data->author));
			$post->setMessage(read::clean($data->message));
			$post->setCreated(read::clean($data->created));
			$post->setEdited(read::clean($data->edited));
			$post->setThreadFK(read::clean($data->thread));
			
			return $post;
		}
		/**
		 * translate database row to ForumUser
		 * @param data database row
		 * @return ForumUser of data
		 */
		private static function toUser($data){
			$user = new ForumUser();
			$user->setName(read::clean($data->name));
			$user->setEmail(read::clean(crypt($data->email,$GLOBALS['crypt_salt'])));
			$user->setRole(read::clean($data->title));
			$user->setRegistered(read::clean($data->registered));
			$user->setBanned($data->banned === 't' ? 1:0);

			return $user; 
		}
		
		/**
		 * read role with specific id\n
		 * @throws RuntimeException on failed query
		 * @param id 
		 */
		public static function role($id){
			$resRole = NULL;
			$db_conn = connect();
			if ($db_conn){
				$query = " SELECT id, title "
					. " FROM " . $GLOBALS['dbtable_roles']
					. " WHERE id=$1 "
					. " ;";
				
				$res = pg_query_params($db_conn, $query, array($id));
				if ($res){
					if (pg_num_rows($res) == 1){
						$resRole = read::toRole(pg_fetch_object($res));
					}
					
					pg_free_result($res);			
				} else {
					throw new RuntimeException(pg_last_error($db_conn));
				}
			}
			return $resRole;
		}
		/**
		* read all subjects
		* @param spec specify query 
		* @throws RuntimeException on failed query
		* @return array of search result 
		*/	
		public static function subjects($subject_id = NULL){
			$subjs = NULL;
			$db_conn = connect();
			if ($db_conn){
				$res = NULL;
				if($subject_id == NULL){
					$res = pg_query($db_conn, 
						" SELECT subject.id, subject.topic, subject.subtitle "
						 . " FROM " . $GLOBALS['dbtable_subjects'] . " as subject "
						 . " ;");
				} else {
					$res = pg_query_params($db_conn,
						" SELECT subject.id, subject.topic, subject.subtitle "
						 . " FROM " . $GLOBALS['dbtable_subjects'] . " as subject "
						 . " WHERE subject.id=$1 "
						 . " ;",
						array($subject_id));
				}
				
				if ($res){
					$subjs = array();
					for($i = 0; $i < pg_num_rows($res); $i++){
						$subjs[] = read::toSubject(pg_fetch_object($res, $i));
					}
						
					pg_free_result($res);			
				} else {
					throw new RuntimeException(pg_last_error($db_conn));
				}
			}
			return $subjs;
		}
		/**
		 * read requested subject from database
		 * @throws RuntimeException on failed query
		 * @return requested forumsubject, if not found NULL is returned 
		 */
		public static function threadsOfSubject($subject_id){
			$resThreadArr = array();
			$db_conn = connect();
			if ($db_conn){
				$query = " SELECT thread.id, thread.subject, thread.topic "
					. " FROM " . $GLOBALS['dbtable_forumthreads'] . " AS thread "
					. " WHERE thread.subject=$1 "
					. " ;";
				
				$res = pg_query_params($db_conn, $query, array($subject_id));
				if ($res){
					$resThreadArr = array();
					$n_row = pg_num_rows($res);
	
					for ($i = 0; $i < $n_row; $i++){
						$resThreadArr[] = read::toThread(pg_fetch_object($res, $i));
					}
					
					pg_free_result($res);
				} else {
					throw new RuntimeException(pg_last_error($db_conn));
				}
			}
			return $resThreadArr;
		}
		
		/**
		 * read a specific thread from database
		 * @param thread_id id of thread to read
		 * @throws RuntimeException on failed query
		 * @return the first found row in database, if no result was found NULL is returned
		 */
		public static function thread($thread_id){
			$thread = NULL;
			
			$db_conn = connect();
			if ($db_conn){
				$query = " SELECT thread.id, thread.subject, thread.topic "
					. " FROM " . $GLOBALS['dbtable_forumthreads'] . " AS thread "
					. " WHERE thread.id=$1 "
					. " ;";
				
				$res = pg_query_params($db_conn, $query, array($thread_id));
				if ($res){
					if(pg_num_rows($res) > 0){
						$thread = read::toThread(pg_fetch_object($res, 0));
					}
					
					pg_free_result($res);
				} else {
					throw new RuntimeException(pg_last_error($db_conn));
				}
			}
			return $thread;
		}
		/**
		 * read news from database
		 * @param whereclause specify query
		 * @throws RuntimeException on failed query
		 * @return array of news instances matching query
		 */
		public static function news($whereclause = NULL){
			$news_arr = array( );
			
			$db_conn = connect();
			if ($db_conn){
				$query = "SELECT news.id, news.author, news.title, news.message, news.created "
					. " FROM " . $GLOBALS['dbtable_news'] . " AS news ";
				if($whereclause != NULL && $whereclause != ""){
					$query .= " " . $whereclause . " ";	
				};
				$query .= " ;";
				
				$res = pg_query($db_conn, $query);
				if ($res){
					$n_rows = pg_num_rows($res);
					for ($i = 0; $i < $n_rows; $i++){
						$news_arr[] = read::toNews(pg_fetch_object($res, $i));
					}
					pg_free_result($res);
				} else {
					throw new RuntimeException(pg_last_error($db_conn));
				}
			}
			return $news_arr;
		}

		/**
		* read posts related to thread with id as param from database
		* ordered by created attr
		* @throws RuntimeException on failed query
		* @param thread_id the id of the thread to read
		*/
		public static function postsFromThread($thread_id){
			$post_arr = array();
			$db_conn = connect();
			if ($db_conn){
				$query = "SELECT post.id "
					. " FROM " . $GLOBALS['dbtable_forumposts'] . " AS post "
					. " WHERE thread=$1 "
					. " ORDER BY post.created ASC"
					. " ;";
				
				$res = pg_query_params($db_conn, $query, array($thread_id));
				if ($res){
					$n_rows = pg_num_rows($res);
					for ($i = 0; $i < $n_rows; $i++){
						$data = pg_fetch_object($res, $i);
	
						$post_arr[] = read::forumPost($data->id);
					}
					pg_free_result($res);
				} else {
					throw new RuntimeException(pg_last_error($db_conn));
				}
			}
			return $post_arr;
		}
		/**
		 * read a specific forumpost from database\n 
		 * @param post_id id of forumpost to read
		 * @throws RuntimeException on failed query
		 * @return forumpost object. If requested forumpost was not found, NULL is returned\n
		 */
		public static function forumPost($post_id){
			$post = NULL;
			$db_conn = connect();
			
			if($db_conn){
				$query = "SELECT post.id, post.thread, post.message, post.created, post.edited, post.author "
					. " FROM " . $GLOBALS['dbtable_forumposts'] . " AS post "
					. " WHERE post.id=$1"
					. " ;";
				$res = pg_query_params($db_conn, $query, array($post_id));
				if($res){
					$post = read::toPost(pg_fetch_object($res));
	
					pg_free_result($res);
				} else {
					throw new RuntimeException(pg_last_error($db_conn));
				}
			}
			return $post;
		}
	
		/**
		 * read forumusers from database
		 * @param whereclause specify query
		 * @throws RuntimeException on failed query
		 * @return array of forumusers results from database
		 */
		public static function forumUser($user_id){
			$user = NULL;
			$db_conn = connect();
			if($db_conn){
				$query = "SELECT fuser.name, fuser.email, roles.title, fuser.banned, fuser.registered "
				 . " FROM ".$GLOBALS['dbtable_forumusers']." AS fuser "
				 . " LEFT JOIN ".$GLOBALS['dbtable_roles']
				 . "   ON fuser.role=roles.id "
				 . " WHERE fuser.name=$1 "
				 . " ;";
	
				$res = pg_query_params($db_conn, $query, array($user_id));
				if($res){
					if(pg_num_rows($res) == 0) return NULL;
					
					$user = read::toUser(pg_fetch_object($res, 0));
	
					pg_free_result($res);
					
				} else {
					throw new RuntimeException(pg_last_error($db_conn));
				}
			}
			return $user;
		}
		/**
		 * read the creator of forumthread\n
		 * creator is defined as the first post of a thread
		 * @param thread primary key of thread to find the creator of
		 * @throws RuntimeException on failed query
		 * @return the creator as ForumUser, if no creator was found NULL is returned
		 */
		public static function creator($thread_fk){
			if($thread_fk == NULL || $thread_fk == "") return NULL;
			
			$creator = NULL;
			
			$db_conn = connect();
			if ($db_conn){
				$query = "SELECT p.author " 
					. " FROM ". $GLOBALS['dbtable_forumposts'] ." AS p "
					. " LEFT JOIN " . $GLOBALS['dbtable_forumthreads'] . " AS t "
					. "     ON p.thread=t.id "
					. " WHERE p.thread=$1 "
					. " ORDER BY p.created ASC "
					. " LIMIT 1"
					. " ;";
				
				$res = pg_query_params($db_conn, $query, array($thread_fk));
				if($res){
					if(pg_num_rows($res) > 0){
						$data = pg_fetch_object($res, 0);
					
						if($data != NULL){
							$creator = read::forumUser($data->author);	
						}
					}
					
					pg_free_result($res);
				} else {
					throw new RuntimeException(pg_last_error($db_conn));
				}
			}
			return $creator;
		}
		/**
		 * get the last attributor of param thread
		 * @param thread_pk primary key of requested thread
		 * @throws RuntimeException on failed query
		 * @return last attributor of thread as ForumUser
		 */
		public static function lastAttributor($thread_pk){
			if($thread_pk == NULL || $thread_pk == "") return NULL;
			$user = NULL; # the last attributor
			$db_conn = connect();
			if($db_conn){
				$query = "SELECT author "
					. " FROM " . $GLOBALS['dbtable_forumposts']
					. " WHERE thread=$1 "
					. " ORDER BY created DESC "
					. " LIMIT 1 "
					. " ;";
				$res = pg_query_params($db_conn, $query, array($thread_pk));
				if($res){
					if(pg_num_rows($res) > 0){
						$data = pg_fetch_object($res, 0);
						$user = read::forumUser($data->author);
					}
				} else {
					throw new RuntimeException(pg_last_error($db_conn));
				}
			}
			
			return $user;
		}
	} # ! READ


?>