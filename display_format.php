<?php
/**
 * functions to help displaying objects
 * @author Mikael Holmbom
 * @version 1.0
 */

	require_once "pageref.php";
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
		return $GLOBALS['forum_page'] . "?t=" . $thread->getId() . "&p=" . $page;
	}
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
			. '<div class="created">' . $newsfeed->getCreated() . '</div>'
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
	/**
	 * format a datetimestamp string
	 * @param datetimestamp the string to format
	 * @return transformed string 
	 */
	function formatDate($datetimestamp){
		return substr($datetimestamp, 0, 10);
	}
	/**
	 * format a datetimestamp string to date time format
	 * @param datetimestamp the string to format
	 * @return string
	 */
	function formatDateTime($datetimestamp){
		return substr($datetimestamp, 0, 19);
	}
	/**
	 * generate a element that shows tooltip when hover
	 * @param target the target element to display a tooltip
	 * @param tooltip the tooltip text
	 * @return tooltip element as html
	 */
	function toolTip($target, $tooltip){
		return '<div class="tooltip">'
			. 	'<span class="tooltiptext">' . $tooltip . '</span>'
			.	$target
			. '</div>';
		
	}
	/**
	 * generate a switchbutton form input
	 * @param unknown $name name of input
	 * @param unknown $checked set the preset state of switchbutton
	 * @return string html
	 */
	function switchButton($name, $checked){
		return "<label class='switch'>"
  				. "<input type='checkbox' ".($checked?"checked":"unchecked")." name='".$name."'>"
  				. "<div class='slider'></div>"
			. 	"</label>";
	}



?>
