<?php
/**
 * helper functions to display forum\n
 * uses abstract factory pattern to create forum content\n
 * to generate forum content: {@link #forum($index, $indexValue, $page)}
 * @author Mikael Holmbom
 */

	require_once "./config/pageref.php";
	require_once "./sections/views.php";
	require_once "./sections/forum/pagination.php";

	/**
	 * facade function generating forum content 
	 * @param index content to display. use keys <ul><li>thread</li>subject<li></li><li>main</li></ul>
	 * @param indexValue 
	 * @param page the page to view
	 * @return content as html string
	 */
	function forum($index, $indexValue=NULL, $page=NULL){

		switch($index){
			case "thread": 		
				return displayThreadContent(
					Read::thread($indexValue),
					$page);
			case "subject":		
				return displaySubjectContent(
					Read::subjects($indexValue)[0],
					$page);
			case "main":		
				return displayMainContent();
			default:			
				return errorMessage("page request error");
		}

	}
	
	/**
	 * get forum content view format
	 * @param breadrcrum current breadcrum
	 * @param top_bts buttons displayed above list of content
	 * @param forumcontlist list of content
	 * @param bottom_bts buttons displayed under list of content
	 * @param pag pagination of content
	 * @return content as html string
	 */
	function forumViewFormat($breadcrum, $header, $top_bts, $forumcontlist, $bottom_bts, $pag){
		return 
		'<article id="forum_content">'
		.	$breadcrum
		. 	'<h2>' . $header . '</h2>'
		. 	'<div id="forum_top_bts" class="button_panel">'
		.		$top_bts 
		. 	'</div>'
		. 	'<div id="forum_content_list">'
		. 		$forumcontlist 
		.	'</div>'
		.	'<div id="forum_bottom_bts" class="button_panel">'
		. 		$bottom_bts 
		.	'</div>'
		. 	$pag
		. '</article>';
	}
	
	/**
	 * display the content of a forumthread
	 * @param thread the thread to 
	 * @param p the page to display
	 * @return content as html string
	 */
	function displayThreadContent($thread, $p){
		if($thread == NULL)
			return errorMessage("invalid thread");
		if ($p == NULL || $p == "") 
			return errorMessage("invalid page");

		$pag = Pagination::generateThread(
			$thread,
			$p
		);

		return forumViewFormat(
			getBreadcrum($thread->getSubject(), $thread),
			$thread->getTopic(),
			"",
			forumContentListPosts($thread, $p),
			replyButton($thread),
			$pag);
	}
	
	/**
	 * display the content of a forumsubject
	 * @param subject the subject containing threads to display
	 * @param p the page number to display
	 * @return content as html string
	 */
	function displaySubjectContent($subject, $p){
		if($subject == NULL)
			return errorMessage("invalid subject");
		if ($p == NULL || $p == "") 
			return errorMessage("invalid page");

		$pag = Pagination::generateSubject(
			$subject,
			$p
		);

		return forumViewFormat(
			getBreadcrum($subject, NULL),
			$subject->getTopic(),
			newThreadButton($subject),
			forumContentListThreads($subject, $p),
			"",
			$pag);
	}

	/**
	 * display main forum content
	 * @return main forum content as html
	 */
	function displayMainContent(){

		return forumViewFormat(
			getBreadcrum(NULL, NULL),
			"forum",
			"",
			forumContentListSubjects(read::subjects()),
			"",
			"");
	}

	/**
	 * get a reply button to reply to a thread
	 * @param thread the thread to reply to
	 * @return content as html string
	 */
	function replyButton($thread){
		return 
			'<a class="button forum_button" href="'.$GLOBALS['post_page'] .'?op=reply&t='.$thread->getPrimaryKey().'">reply</a>';
	}

	/**
	 * get a button to add thread to subject
	 * @param subject the subject to add thread to
	 * @return button as html string
	 */
	function newThreadButton($subject){
		return '<a class="button forum_button" href="'.$GLOBALS['post_page'] .'?op=createthread&s='.$subject->getPrimaryKey().'">new thread</a>';
	}

?>
