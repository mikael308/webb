<?php
/**
 * functions to help displaying objects
 * @author Mikael Holmbom
 * @version 1.0
 */

	require_once "./config/pageref.php";
	require_once "./database/database.php";
	require_once "./session/main.php";	
	require_once "./sections/dateformat.php";
	
	startSession();


	/**
	 * get a url to view user page
	 * @param forumuser_pk primary key of the user to display
	 * @return link url as string
	 */
	function getDisplayUserLink($forumuser_pk){
		return $GLOBALS['user_page'] . '?u=' . $forumuser_pk;
	}
	/**
	 * generate newsfeed view
	 * @param newsfeed the newsfeed
	 * @return content as html string
	 */
	function newsfeedView(News $newsfeed){
		return '<div class="newsfeed">'
			. '<h2 class="title">' . $newsfeed->getTitle() . '</h2>'
			. '<div class="created">' . formatDateTime($newsfeed->getCreated()) . '</div>'
			. '<div class="message">' . $newsfeed->getMessage() . '</div>'
			. '<div class="author">' . $newsfeed->getAuthor()->getName() . '</div>'

		. '</div>';
	}
	/**
	 * display information about user
	 * @param user the user to get info about
	 */
	function getUserInfo(ForumUser $user){
		if($user == NULL) return "";
		
		$nForumThreads 	= count::forumThreads($user);
		$nForumPosts 	= count::forumPosts($user);
	
		$cont = userinfoTableContent(array(
						'name' => $user->getName()
				));
				
		if ( $_SESSION['authorized_user']->getPrimaryKey() == $user->getPrimaryKey()){
			$cont .= userinfoTableContent(array(
					'email' => $user->getEmail()
			));
		}		
		$cont .= userinfoTableContent(array(
						'role' => $user->getRole(),
						'registered' => $user->getRegistered(),
						'created threads' => $nForumThreads,
						'posts' => $nForumPosts
				));
		
		return 
			  '<table id="userinfo">'
				. $cont
			. '</table>';
	}
	function userinfoTableContent($cont){
		$c = '';
		foreach($cont as $label => $data)
			$c .= tr('<td class="userinfo_label">'.$label.'</td><td class="userinfo_data">'.$data.'</td>');
		return $c;
	}


?>
