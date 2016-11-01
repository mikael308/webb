<?php
/**
 * Programvaruteknik,	Webbprogrammering 7.5hp
 * 
 * database helper functions
 * 
 * to create connection with database @see connect()
 *
 * @author Mikael Holmbom: miho1202
 * @version 1.0
 */

	
	# autoload classes
	spl_autoload_register(function($class) {
		include 'classes/' . $class . '.class.php';
	});
	
	$GLOBALS['schema'] = "proj";
	$GLOBALS['dbtable_forumusers'] = $GLOBALS['schema'] . ".forumusers";
	$GLOBALS['dbtable_forumthreads']  = $GLOBALS['schema'] . ".forumthreads";
	$GLOBALS['dbtable_subjects'] = $GLOBALS['schema'] . ".forumsubjects";
	$GLOBALS['dbtable_forumposts'] = $GLOBALS['schema'] . ".forumposts";
	$GLOBALS['dbtable_roles'] = $GLOBALS['schema'] . ".roles";
	$GLOBALS['dbtable_news'] = $GLOBALS['schema'] . ".news";
	

	/**
	 * opens connection to database
	 */
	function connect(){
		
		$servername 	= "localhost";
		$port 			= "5432";
		$dbname 		= "postgres";
		$username 		= "mikael";
		$password 		= "mydbpassw";

		$conn_str = "host=" 	. $servername
			. " port=" 			. $port 
			. " dbname=" 		. $dbname
			. " user=" 			. $username
			. " password=" 		. $password;
		
		return pg_connect($conn_str);
	}
	/**
	 * make a login request to database
	 * @return 1: login was successful\n0: no contact with database\n-1: failed password/username match\n-2: user banned
	 */
	function login($userPK, $password){
		$retVal = 0;
		
		$db_conn = connect();
		if($db_conn){
		
			$query = "SELECT fuser.password, fuser.name "
				. " FROM " . $GLOBALS['dbtable_forumusers'] . " AS fuser "
				. " LEFT JOIN " . $GLOBALS['dbtable_roles'] . " AS role "
				. " ON fuser.role=role.id " 
				. " WHERE name= '". $userPK ."' AND password = '". $password ."';";
			
			$res = pg_query($db_conn, $query);
			if($res){ # query OK
				
				if (pg_num_rows($res) == 1){ # found 1 matching result
					$data = pg_fetch_object($res, 0);
					
					$user = readForumUser($data->name);
					
					if($user->isBanned()){
						$retVal = -2;
					} else {
						$retVal = 1;
						$_SESSION['authorized_user'] = $user;
					}
					
				} else {
					$retVal = -1;
				}
				pg_free_result($res);
			} else {
				echo pg_last_error($db_conn);
			}

		} else {
			#TODO errormsg

		}
	
		return $retVal;
		
	}
	/**
	 * determine if a user already exists in database
	 * @param user 
	 */
	function exists(ForumUser $user){
		$exists = False;
		
		$db_conn = connect();
		if($db_conn){
			
			$query = "SELECT name FROM " . $GLOBALS['dbtable_forumusers'] 
			. " WHERE name = '". $user->getPrimaryKey() . "' ;";
			
			$res = pg_query($db_conn, $query);
			if($res){
				if(pg_num_rows($res)){
					$exists = True;
				}
				pg_free_result($res);
			}
			
		}
		#TODO throw error on !$db_conn 
		
		return $exists;
	}
	/**
	 * read role with specific id\n
	 * @param id 
	 */
	function readRole($id){
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
	* read all subjects with specific whereclause
	* @param whereclause specify query 
	* @return array of search result 
	*/	
	function readSubjects($whereclause = NULL){
		$subjs = NULL;
		$db_conn = connect();
		if ($db_conn){
			$query = " SELECT subject.id, subject.topic, subject.subtitle "
				. " FROM " . $GLOBALS['dbtable_subjects'] . " as subject ";
			if($whereclause != NULL && $whereclause != ""){
				$query .= " " . $whereclause . " ";
			}
			$query .= " ;";
			
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
	function readSubject($subject_id){
		if($subject_id == NULL)
			return NULL;
		
		$res = readSubjects(" WHERE subject.id='" . $subject_id . "'");
		if($res != NULL){
			return $res[0];
		}

		return NULL;
	}
	
	/**
	* read all threads with specific whereclause
	* @param whereclause specify query
	* @return array of search result 
	*/	
	function readAllThreadsWhere($whereclause = NULL){
		$resThreadArr = NULL;
		$db_conn = connect();
		if ($db_conn){
			$query = " SELECT thread.id, thread.subject, thread.topic "
			." FROM " . $GLOBALS['dbtable_forumthreads'] . " AS thread "
			;
			if($whereclause != NULL && $whereclause != ""){
				$query .= " WHERE " . $whereclause;
			}
			$query .= " ;"; 	
			
			$res = pg_query($db_conn, $query);
			if ($res){
				
				$resThreadArr = array();
				$n_row = pg_num_rows($res);

				for ($i = 0; $i < $n_row; $i++){
					$data = pg_fetch_object($res, $i);
					
					$thread = new ForumThread($data->topic);
					$thread->setSubject(readSubject($data->subject));
					#$thread->setCreator($data->first);
					#$thread->setLastAttributor($data->last);
					$thread->setId($data->id);
					
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
	 */
	function readNews($whereclause = NULL){
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
					$news->setAuthor($data->author);
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
	*/
	function readThread($thread_id){
		$resThread = readAllThreadsWhere(" thread.id=".$thread_id." ")[0];

		return $resThread;
	}
	/**
	* read posts related to thread with id as param from database
	* @param thread_id the id of the thread to read
	*/
	function readPostsFromThread($thread_id){
		$post_arr = array( );
		$db_conn = connect();
		if ($db_conn){
			$query = "SELECT post.id "
				. " FROM " . $GLOBALS['dbtable_forumposts'] . " AS post "
				. " WHERE thread='" . $thread_id . "' "
				. " ORDER BY post.created ;";
			
			$res = pg_query($db_conn, $query);
			if ($res){
				$n_rows = pg_num_rows($res);
				for ($i = 0; $i < $n_rows; $i++){
					$data = pg_fetch_object($res, $i);

					$post = readForumPost($data->id);
					
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
	function readForumPost($post_id){
		$post = NULL;
		$db_conn = connect();
		
		if($db_conn){
			$query = "SELECT post.id, post.thread, post.message, post.created, post.author "
				. " FROM " . $GLOBALS['dbtable_forumposts'] . " AS post "
				. " WHERE post.id='".$post_id."' ;";
				
			$res = pg_query($db_conn, $query);
			if($res){
				$data = pg_fetch_object($res);
				$author = readForumUser($data->author);
				
				$post = new ForumPost($data->id);
				$post->setAuthor($author);
				$post->setMessage($data->message);
				$post->setCreated($data->created);
				$post->setThread($data->thread);
				
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
	function readForumUser($user_id){
		return readForumUsers("fuser.name='" . $user_id . "'")[0];
	}
	function readForumUsers($whereclause = NULL){
		$db_conn = connect();
		if($db_conn){
			$query = "SELECT fuser.name, fuser.email, roles.title, fuser.banned "
			 . " FROM proj.forumusers AS fuser "
			 . " LEFT JOIN proj.roles ON fuser.role=roles.id ";
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
					
					$b = $data->banned === 't' ? 1:0;
					$user->setBanned($b);

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
	 * persist a user to database
	 * @param user the user to persist
	 * @param passw requested password to persist
	 */
	function persistForumUser(ForumUser $user, $passw){
		$persistOK = False;
		$db_conn = connect();
		if($db_conn){
			$banned_val = $user->isBanned() ? "TRUE" : "FALSE";
			
			$query = "INSERT INTO " . $GLOBALS['dbtable_forumusers'] 
				. " (name, email, role, banned, password) "
				. " VALUES('".$user->getName()."','".$user->getEmail()."', ".$user->getRole().",'".$banned_val."', '".$passw."');";
			
			$res = pg_query($db_conn, $query);
			if($res){
				$persistOK = True;
				pg_free_result($res);
			} else {
				echo pg_last_error($db_conn);
			}
		}
		return $persistOK;
	}
	/**
	 * persist forumthread to database
	 * @param thread forumthread to persist
	 */
	function persistForumThread(ForumThread $thread){
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
				
			} else {
				echo pg_last_error($db_conn);
			}
		}
		return $resThread;
	}
	/**
	 * persist forumpost to database
	 * @param thread containing thread
	 * @param post the post to persist
	 * @return True if post was inserted in database successfully
	 */
	function persistForumPost(ForumThread $thread, ForumPost $post){
		$persistOK = False;
		$db_conn = connect();
		if($db_conn){
			$query = "INSERT INTO " . $GLOBALS['dbtable_forumposts'] 
				. " (author, thread, message, created) "
				. " VALUES('".$post->getAuthor()->getPrimaryKey()."','".$thread->getId()."','".$post->getMessage()."', now());";
				
			$res = pg_query($db_conn, $query);
			if($res){
				$persistOK = True;
				pg_free_result($res);
				
			} else {
				echo pg_last_error($db_conn);
			}
		}
		return $persistOK;
	}
	/**
	 * delete forumuser matching with param user
	 * @param user forumuser to delete
	 * @return True if user was successfully deleted
	 */
	function deleteForumUser(ForumUser $user){
		$db_conn = connect();
		if ($db_conn){
			$query = "DELETE FROM " . $GLOBALS['dbtable_forumusers'] ." as fuser "
			. " WHERE fuser.name='" . $user->getPrimaryKey() . "';";
			$res = pg_query($db_conn, $query);
			if($res){
				pg_free_result($res);
				return True;	
			}
			
		}
		return False;
	}
	/**
	 * get the creator of forumthread\n
	 * creator is defined as the first post of a thread
	 * @param thread thread to find the creator of
	 * @return the creator as ForumUser, if no creator was found NULL is returned
	 */
	function getCreator(ForumThread $thread){
		if($thread == NULL) return NULL;
		
		$user = NULL; # the creator
		
		$db_conn = connect();
		if ($db_conn){
			$query = "SELECT p.author " 
				. " FROM ". $GLOBALS['dbtable_forumposts'] ." AS p "
				. " LEFT JOIN " . $GLOBALS['dbtable_forumthreads'] . " AS t "
				. "     ON p.thread=t.id "
				. " WHERE p.thread='" . $thread->getPrimaryKey() . "' "
				. " ORDER BY p.created ASC "
				. " LIMIT 1"
				. " ;";
			
			$res = pg_query($db_conn, $query);
			if($res){
				$data = pg_fetch_object($res, 0);
				
				pg_free_result($res);
				if($data != NULL){
					$user = readForumUser($data->author);	
				}
			}
			
		}
		return $user;
	}
	/**
	 * count the number of forumthreads created by specific user
	 * @param user the specific user
	 */
	function countForumThreads(ForumUser $user){
		
		$db_conn = connect();
		if ($db_conn){
			$query = "SELECT p.author, p.message, t.topic "			
				. " FROM proj.forumthreads AS t "
				. " LEFT JOIN proj.forumposts as p "
				. " ON p.thread=t.id "
				. " WHERE p.author = '".$user->getPrimaryKey()."' "
				. " AND p.id IN "
				. " ( "
				# the first posts in every thread
				. " SELECT p.id "
				. " FROM proj.forumposts p "
				. " WHERE p.id = "
					. " ( "
						. " SELECT p2.id "
						. " FROM proj.forumposts p2 "
						. " WHERE p2.thread=p.thread "
						. " ORDER BY p2.created ASC "
						. " LIMIT 1 "
					. " ) "
				. " ) "
				. " ;";
			
			$res = pg_query($db_conn, $query);
			if($res){
				$numrow = pg_num_rows($res);
				
				pg_free_result($res);
				return $numrow;
			}
			
		} # ! db_conn
		
		return 0;
		
	}
	/**
	 * count the number of forumposts created by specific user
	 * @param user the specific user
	 */
	function countForumPosts(ForumUser $user){
		$nFP = 0;
		$db_conn = connect();
		if($db_conn){
			$res = pg_query($db_conn, "SELECT author, id FROM ". $GLOBALS['dbtable_forumposts'] 
					." WHERE author='".$user->getPrimaryKey()."';");
			if($res){
				$nFP = pg_num_rows($res);
				pg_free_result($res);
			}
			
		}
		
		return $nFP;
	}
	/**
	 * get forumusers with id as param user_id
	 * @param user_id
	 * @return list of forumusers with match
	 */
	function searchForumUser($user_id){
		$db_conn = connect();
		if($db_conn){
			$query = "SELECT fuser.name, fuser.email, roles.title "
			 . " FROM proj.forumusers AS fuser "
			 . " LEFT JOIN proj.roles ON fuser.role=roles.id "
			 . " WHERE fuser.name LIKE '%" . $user_id . "%';";
			
			$res = pg_query($db_conn, $query);
			if($res){
				$num_rows = pg_num_rows($res);
				if($num_rows == 0) return NULL;
				
				$users = array();
				for ($i = 0; $i < $num_rows; $i++){
					$data = pg_fetch_object($res, $i);
					$user = new ForumUser();
					$user->setName($data->name);
					$user->setEmail($data->email);
					$user->setRole($data->title);
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
	 * search posts with messaging containing param post_msg
	 * @param post_msg 
	 * @return return posts containing message as post_msg. NULL is returned if no matches were maid
	 */
	function searchPost($post_msg){
		$post_msg =  htmlspecialchars($post_msg);
		
		$db_conn = connect();
		if($db_conn){
			$query = "SELECT posts.message, posts.author, posts.thread "
			 . " FROM proj.forumposts AS posts "
			 . " WHERE posts.message LIKE '%" . $post_msg . "%';";
			
			$res = pg_query($db_conn, $query);
			if($res){
				$num_rows = pg_num_rows($res);
				if($num_rows == 0) return NULL;
				
				$posts = array();
				for ($i = 0; $i < $num_rows; $i++){
					$data = pg_fetch_object($res, $i);
					$post = new ForumPost();
					$post->setMessage($data->message);
					$post->setThread($data->thread);
					$author = readForumUser($data->author);
					$post->setAuthor($author);
					
					$posts[] = $post;				
				}
				
				pg_free_result($res);
				
				return $posts;
				
			} else {
				echo pg_last_error($db_conn);
			}
		}
		return NULL;
	}
	/**
	 * updates the forumuser in database
	 */
	function updateForumUser(ForumUser $user){
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
			}
		}
		return False;
	}
	/**
	 * update banned attribute of user
	 * @param user the user to edit
	 * @param banned boolean or string representing boolean value that will be set to banned attribute in database
	 */
	function updateBanned(ForumUser $user, $banned){
		
		$db_conn = connect();
		if($db_conn){
			$banned_val;
			if(is_bool($banned)){
				$banned_val = $banned ? "TRUE" : "FALSE";	
			} elseif(is_string($banned)){
				$banned_val = $banned;
			} else {
				return False;
			}
			
			$query = "UPDATE " . $GLOBALS['dbtable_forumusers'] . " AS fuser "
			 . " SET banned='" . $banned_val . "' "
			 . " WHERE fuser.name='" . $user->getPrimaryKey() . "';";
			 			
			$res = pg_query($db_conn, $query);
			if($res){
				pg_free_result($res);
				
				return True;	
			}
		}
		return False;
	}


?>
