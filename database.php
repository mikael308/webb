<?php
/**
 * 
 * database helper functions
 * 
 * to create connection with database @see connect()
 *
 * @author Mikael Holmbom
 * @version 1.0
 */

	require_once "database_read.php";
	require_once "database_update.php";
	require_once "database_delete.php";
	require_once "database_persist.php";
	require_once "database_count.php";
	require_once "settings.php";
	
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
					
					$user = read::forumUser($data->name);
					
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
	 * get forumusers with id as param user_id
	 * @param user_id
	 * @return list of forumusers with match
	 */
	function searchForumUser($user_id){
		$db_conn = connect();
		if($db_conn){
			$query = "SELECT fuser.name "
			 . " FROM ".$GLOBALS['dbtable_forumusers']." AS fuser "
			 . " LEFT JOIN ".$GLOBALS['dbtable_roles']." ON fuser.role=roles.id "
			 . " WHERE fuser.name LIKE '%" . $user_id . "%';";
			
			$res = pg_query($db_conn, $query);
			if($res){
				$num_rows = pg_num_rows($res);
				if($num_rows == 0) return NULL;
				
				$users = array();
				for ($i = 0; $i < $num_rows; $i++){
					$data = pg_fetch_object($res, $i);
					$users[] = read::forumuser($data->name);
					
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
			$query = "SELECT post.id "
			 . " FROM ".$GLOBALS['dbtable_forumposts']." AS post "
			 . " WHERE post.message LIKE '%" . $post_msg . "%';";
			
			$res = pg_query($db_conn, $query);
			if($res){
				$num_rows = pg_num_rows($res);
				if($num_rows == 0) return NULL;
				
				$posts = array();
				for ($i = 0; $i < $num_rows; $i++){
					$data = pg_fetch_object($res, $i);
					$post = read::forumPost($data->id);
					
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
	
	
?>
