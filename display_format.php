<?php
/**
 * functions to help displaying objects
 * @author Mikael Holmbom
 * @version 1.0
 */

	require_once "database.php";
	require_once "listeners.php";
	
	startSession();
	
	/**
	 * format used for displaying timestamps
	 */
	$GLOBALS['timestamp_format']  = 'Y-m-d G:i:s';
	
	/**
	 * get a error message
	 * @param message the text message contained
	 * @return error message as html string
	 */
	function errorMessage($message){
		return '<p class="err_msg msg"><strong class="msg_header">error</strong> '.$message.'</p>';
	}
	/**
	 * get a info message
	 * @param message the text message contained
	 * @return info message as html string
	 */
	function infoMessage($message){
		return '<p class="info_msg msg"><strong class="msg_header">info</strong> '.$message.'</p>';
	}
	/**
	 * get url link to thread page 
	 * @param thread the thread 
	 * @param page the index page of the link
	 * @return link url as string
	 */
	function getDisplayThreadLink(ForumThread $thread, $page){
		return "forum_page.php?t=" . $thread->getId() . "&p=" . $page;
	}
	/**
	 * get a url to view user page
	 * @param forumuser_pk primary key of the user to display
	 * @return link url as string
	 */
	function getDisplayUserLink($forumuser_pk){
		return 'viewuser_page.php?u=' . $forumuser_pk;
	}
	/**
	 * generate newsfeed view
	 * @param newsfeed the newsfeed
	 * @return content as html string
	 */
	function newsfeedView(News $newsfeed){
		return '<div class="newsfeed">'
			. '<div class="title">' . $newsfeed->getTitle() . '</div>'
			. '<div class="created">' . $newsfeed->getCreated() . '</div>'
			. '<div class="message">' . $newsfeed->getMessage() . '</div>'
			. '<div class="author">' . $newsfeed->getAuthor() . '</div>'

		. '</div>';
	}
	/**
	 * display information about user
	 * @param user the user to get info about
	 */
	function getUserInfo(ForumUser $user){
		if($user == NULL) return "";
		
		$nForumThreads 	= countForumThreads($user);
		$nForumPosts 	= countForumPosts($user);
	
		$cont = '<table id="userinfo">'
				. tr(td('name') . td($user->getName()));
		if ( $_SESSION['authorized_user']->getPrimaryKey() == $user->getPrimaryKey()){
			$cont .= tr(td('email') . td($user->getEmail()));
		}		
		$cont .=
				 tr(td('role') . td($user->getRole()))
			 	. tr(td('registered') . td($user->getRegistered()))
				. tr(td('created threads') . td($nForumThreads))
				. tr(td('posts') . td($nForumPosts))
			. '</table>';
			
		return $cont;
	}
	/**
	 * format a datetimestamp string
	 * @param datetimestamp the string to format
	 * @return transformed string 
	 */
	function formatDate($datetimestamp){
		return substr($datetimestamp, 0, 10);
	}
	/**
	 * generate a element that shows tooltip when hover
	 * @param target the target element to display a tooltip
	 * @param tooltip the tooltip text
	 * @return tooltip element as html
	 */
	function getToolTip($target, $tooltip){
		return '<div class="tooltip">'
			. 	'<span class="tooltiptext">' . $tooltip . '</span>'
			.	$target
			. '</div>';
		
	}



?>
