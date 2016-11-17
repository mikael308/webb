<?php

	/**
	 * read entries from database
	 * @author Mikael Holmbom
	 */
	class read{
		/**
		 * read role with specific id\n
		 * @param id 
		 */
		public static function role($id){
			$resRole = NULL;
			$db_conn = connect();
			if ($db_conn){
				$query = " SELECT id, title "
					. " FROM " . $GLOBALS['dbtable_roles']
					. " WHERE id = '" . $id . "' "
					. " ;";
				
				$res = pg_query($db_conn, $query);
				if ($res){
					if (pg_num_rows($res) == 1){
						$data = pg_fetch_object($res);
						$resRole = new Role($data->id, $data->title);
					}
					
					pg_free_result($res);			
				}
			}
			return $resRole;
		}
		/**
		* read all subjects
		* @param spec specify query 
		* @return array of search result 
		*/	
		public static function subjects($spec= ""){
			$subjs = NULL;
			$db_conn = connect();
			if ($db_conn){
				$query = " SELECT subject.id, subject.topic, subject.subtitle "
					. " FROM " . $GLOBALS['dbtable_subjects'] . " as subject "
					. $spec
					. " ;";
				
				$res = pg_query($db_conn, $query);
				if ($res){
	
					$subjs = array();
					for($i = 0; $i < pg_num_rows($res); $i++){
						$data = pg_fetch_object($res, $i);
	
						$subj = new ForumSubject();
						$subj->setId($data->id);
						$subj->setTopic($data->topic);
						$subj->setSubtitle($data->subtitle);
	
						$subjs[] = $subj;
					}
									
					pg_free_result($res);			
				}
			}
			return $subjs;
		}
		/**
		 * read requested subject from database
		 * @param subject_id id of requested subject
		 * @return requested forumsubject, if not found NULL is returned 
		 */
		public static function subject($subject_id){
			if($subject_id == NULL)
				return NULL;
			
			$res = read::subjects(" WHERE subject.id='" . $subject_id . "'");
			if($res != NULL){
				return $res[0];
			}
	
			return NULL;
		}
		
		/**
		* read all threads can be specified by param
		* @param spec specify query
		* @return array of search result 
		*/	
		public static function threads($spec = ""){
			$resThreadArr = array();
			$db_conn = connect();
			if ($db_conn){
				$query = " SELECT thread.id, thread.subject, thread.topic "
				." FROM " . $GLOBALS['dbtable_forumthreads'] . " AS thread "
				. $spec
				. " ;";
				
				$res = pg_query($db_conn, $query);
				if ($res){
					
					$resThreadArr = array();
					$n_row = pg_num_rows($res);
	
					for ($i = 0; $i < $n_row; $i++){
						$data = pg_fetch_object($res, $i);
						
						$thread = new ForumThread($data->topic);
						$thread->setId($data->id);
						$thread->setSubjectFK($data->subject);
												
						$resThreadArr[] = $thread;
					}
					
					pg_free_result($res);
				}
			}
			return $resThreadArr;
		}
		/**
		 * read news from database
		 * @param whereclause specify query
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
						$data = pg_fetch_object($res, $i);
						
						$news = new News();
						$news->setId($data->id);
						$news->setAuthorPK($data->author);
						$news->setTitle($data->title);
						$news->setMessage($data->message);
						$news->setCreated($data->created);
						$news_arr[] = $news;
					}
					pg_free_result($res);
				}
			}
			return $news_arr;
	
		}
		/**
		 * read a specific thread from database
		 * @param thread_id id of thread to read
		 * @return the first found row in database, if no result was found NULL is returned
		 */
		public static function thread($thread_id){
			$resThreads = read::threads(" WHERE thread.id=".$thread_id." ");
			if(sizeof($resThreads) > 0)
				return $resThreads[0];
			
			return NULL;
		}
		/**
		* read posts related to thread with id as param from database
		 * ordered by created attr
		* @param thread_id the id of the thread to read
		*/
		public static function postsFromThread($thread_id){
			$post_arr = array( );
			$db_conn = connect();
			if ($db_conn){
				$query = "SELECT post.id "
					. " FROM " . $GLOBALS['dbtable_forumposts'] . " AS post "
					. " WHERE thread='" . $thread_id . "' "
					. " ORDER BY post.created ASC;";
				
				$res = pg_query($db_conn, $query);
				if ($res){
					$n_rows = pg_num_rows($res);
					for ($i = 0; $i < $n_rows; $i++){
						$data = pg_fetch_object($res, $i);
	
						$post = read::forumPost($data->id);
						
						$post_arr[] = $post;
					}
					pg_free_result($res);
				}
			}
			return $post_arr;
		}
		/**
		 * read a specific forumpost from database\n 
		 * @param post_id id of forumpost to read
		 * @return forumpost object. If requested forumpost was not found, NULL is returned\n
		 */
		public static function forumPost($post_id){
			$post = NULL;
			$db_conn = connect();
			
			if($db_conn){
				$query = "SELECT post.id, post.thread, post.message, post.created, post.edited, post.author "
					. " FROM " . $GLOBALS['dbtable_forumposts'] . " AS post "
					. " WHERE post.id='".$post_id."' ;";
					
				$res = pg_query($db_conn, $query);
				if($res){
					$data = pg_fetch_object($res);
											
					$post = new ForumPost();
					$post->setId($data->id);
					$post->setAuthorFK($data->author);
					$post->setMessage($data->message);
					$post->setCreated($data->created);
					$post->setEdited($data->edited);
					$post->setThreadFK($data->thread);
					
					pg_free_result($res);
				}
			}
			return $post;
		}
	
		/**
		* read user from database with specific id
		* @param user_id id of user to read
		* @return user as ForumUser instance, if no user could be found: return NULL 
		*/
		public static function forumUser($user_id){
			return read::forumUsers("fuser.name='" . $user_id . "'")[0];
		}
		/**
		 * read forumusers from database
		 * @param whereclause specify query
		 * @return array of forumusers results from database
		 */
		public static function forumUsers($whereclause = NULL){
			$db_conn = connect();
			if($db_conn){
				$query = "SELECT fuser.name, fuser.email, roles.title, fuser.banned, fuser.registered "
				 . " FROM ".$GLOBALS['dbtable_forumusers']." AS fuser "
				 . " LEFT JOIN ".$GLOBALS['dbtable_roles']." ON fuser.role=roles.id ";
				 if($whereclause != NULL && $whereclause != ""){
				 	$query .= " WHERE " . $whereclause;
				 }
				 $query .= ";";
				
				$res = pg_query($db_conn, $query);
				if($res){
					if(pg_num_rows($res) == 0) return NULL;
					
					$users = array();
					for($i = 0; $i < pg_num_rows($res); $i++){
						$data = pg_fetch_object($res, $i);
						
						$user = new ForumUser();
						$user->setName($data->name);
						$user->setEmail($data->email);
						$user->setRole($data->title);
						$user->setRegistered($data->registered);
						$user->setBanned($data->banned === 't' ? 1:0);
	
						$users[] = $user;
					}
					
					pg_free_result($res);
					
					return $users;
					
				} else {
					echo pg_last_error($db_conn);
				}
			}
			return NULL;
		}
		/**
		 * read the creator of forumthread\n
		 * creator is defined as the first post of a thread
		 * @param thread primary key of thread to find the creator of
		 * @return the creator as ForumUser, if no creator was found NULL is returned
		 */
		public static function creator($thread_fk){
			if($thread_fk == NULL || $thread_fk == "") return NULL;
			
			$user = NULL; # the creator
			
			$db_conn = connect();
			if ($db_conn){
				$query = "SELECT p.author " 
					. " FROM ". $GLOBALS['dbtable_forumposts'] ." AS p "
					. " LEFT JOIN " . $GLOBALS['dbtable_forumthreads'] . " AS t "
					. "     ON p.thread=t.id "
					. " WHERE p.thread='" . $thread_fk . "' "
					. " ORDER BY p.created ASC "
					. " LIMIT 1"
					. " ;";
				
				$res = pg_query($db_conn, $query);
				if($res){
					if(pg_num_rows($res) > 0){
						$data = pg_fetch_object($res, 0);
					
						if($data != NULL){
							$user = read::forumUser($data->author);	
						}
					}
					
					pg_free_result($res);
				}
				
			}
			return $user;
		}
		/**
		 * get the last attributor of param thread
		 * @param thread_pk primary key of requested thread
		 * @return last attributor of thread as ForumUser
		 */
		public static function lastAttributor($thread_pk){
			if($thread_pk == NULL || $thread_pk == "") return NULL;
			$user = NULL; # the last attributor
			$db_conn = connect();
			if($db_conn){
				$query = "SELECT author "
					. " FROM " . $GLOBALS['dbtable_forumposts']
					. " WHERE thread='" . $thread_pk . "' "
					. " ORDER BY created DESC "
					. " LIMIT 1 "
					. " ;";
				$res = pg_query($db_conn, $query);
				if($res){
					if(pg_num_rows($res) > 0){
						$data = pg_fetch_object($res, 0);
						$user = read::forumUser($data->author);
					}
				}
			}
			
			return $user;
		}
	} # ! READ


?>