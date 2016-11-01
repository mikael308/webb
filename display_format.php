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
	 * get url link to thread page 
	 * @param thread the thread 
	 * @param page the index page of the link
	 * @return link url as string
	 */
	function getDisplayThreadLink(ForumThread $thread, $page){
		return "thread_page.php?t=" . $thread->getId() . "&p=" . $page;
	}
	/**
	 * get a url to view user page
	 * @param forumuser the user to display
	 * @return link url as string
	 */
	function getDisplayUserLink(ForumUser $forumuser){
		return 'viewuser_page.php?u=' . $forumuser->getPrimaryKey();
	}

	/**
	 *
	 * @param thread the thread to display
	 * @return display as html string
	 */
	function displayThreadLink(ForumThread $thread){		
		$tlink = getDisplayThreadLink($thread, 1);

		$cont = 
			 '<a href="'.$tlink.'">'
			.	'<div class="thread forum_navigator">' 	
			. 		'<div id="topic" class="topic">'.$thread->getTopic().'</div>'
			.		'<div id="info">'
			.			'<div id="indexlink">'
			.				'blahblah'
			.			'</div>'
			. 		'</div>' 
			. 	'</div>'
			. '</a>';
		return $cont;
	}
	/**
	 * display a newsfeed
	 * @param newsfeed the newsfeed to display
	 * @return display as html string
	 */
	function displayNewsfeed(News $newsfeed){
		return '<div class="newsfeed">'
				. '<div class="title">' . $newsfeed->getTitle() . '</div>'
				. '<div class="created">' . $newsfeed->getCreated() . '</div>'
				. '<div class="message">' . $newsfeed->getMessage() . '</div>'
				. '<div class="author">' . $newsfeed->getAuthor() . '</div>'
	
			. '</div>';
	}
	/**
	 * display list of subjects
	 * @param subjects list of subjects to display
	 * @return display as html string
	 */
	function displayForumSubjects($subjects){
		$cont = '<div id="forum_navigator_list">';
		foreach($subjects as $subject){
			$cont .= displayForumSubject($subject);
		}
		$cont .= '</div>';
		return $cont;
	}
	/**
	 * display a forumsubject link
	 * @param subject the forumsubject to display
	 * @return display as html string
	 */
	function displayForumSubject(ForumSubject $subject){
		$cont = 
			 '<a href="forum_page.php?s='.$subject->getPrimaryKey().'&p=1">'
			.	'<div class="subject forum_navigator">' 	
			. 		'<div id="topic" class="topic">'.$subject->getTopic().'</div>'
			.		'<div id="info">'
			.			'<div class="subtitle">'.$subject->getSubtitle().'</div>'
			. 		'</div>' 
			. 	'</div>'
			. '</a>';
		return $cont;
	}
	/**
	 * 
	 * @param thread_id the id of thread to display
	 * @return forumposts as html
	 */
	function displayForumPosts(ForumThread $thread, $start_offset, $amount){
		$cont = "";
		$forumpost_arr = readPostsFromThread($thread->getPrimaryKey());
		$n_posts = count($forumpost_arr);
		for ($i = $start_offset; $i < ($amount + $start_offset) && $i < $n_posts; $i++){
			$cont .= displayForumPost($forumpost_arr[$i]);
		}
		return $cont;
	}
	/**
	 *
	 * @param post the forumpost to display
	 * @return a forumpost as html
	 */
	function displayForumPost(ForumPost $post){
		
		$author = $post->getAuthor();
		return '<div class="displayForumPost">'
			. '<div class="author">'
				. '<div class="name"><a href="viewuser_page.php?u='.$author->getPrimaryKey().'">' . $post->getAuthor()->getName() . '</a></div>'
				. '<div class="role">' . $author->getRole() . '</div>'
				. '<div class="registered">registered<br>'.formatDate($author->getRegistered()).'</div>'
			. '</div>'
			. '<div class="post">'
				. '<div class="message">' . $post->getMessage() . '</div>'
				. '<div class="created">created:' . $post->getCreated() . '</div>'
			. '</div>'
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
	



?>
