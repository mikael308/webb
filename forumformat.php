<?php
/**
 * helper functions to display forum\n
 * uses abstract factory pattern to create forum content\n
 * to generate forum content: {@link #forum($index, $indexValue, $page)}
 * @author Mikael Holmbom
 */

	require_once "pageref.php";
	require_once "display_format.php";


	/**
	 * facade function generating forum content 
	 * @param index content to display. use keys <ul><li>thread</li>subject<li></li><li>main</li></ul>
	 * @param indexValue 
	 * @param page the page to view
	 * @return content as html string
	 */
	function forum($index, $indexValue, $page){

		switch($index){
			case "thread": 		return displayThreadContent(read::thread($indexValue), $page);
			case "subject":		return displaySubjectContent(read::subject($indexValue), $page);
			case "main":		return displayMainContent();
			default:			return errorMessage("page request error");
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
		. 	'<div id="forum_top_bts" class="button_panel">'.$top_bts . '</div>'
		. 	'<div id="forum_content_list">'. $forumcontlist .'</div>'
		.	'<div id="forum_bottom_bts" class="button_panel">'. $bottom_bts .'</div>'
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

		$subject = $thread->getSubject();

		$pag = pagination(
			$p, 
			(int) readSettings("pag_max_interval"),
			count::maxPagesThread($thread), 
			$GLOBALS['forum_page'] . "?t=" . $thread->getPrimaryKey());

		return forumViewFormat(
			getBreadcrum($subject, $thread),
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

		$pag = pagination(
			$p, 
			(int) readSettings("pag_max_interval"), 
			count::maxPagesSubject($subject),
			$GLOBALS['forum_page'] . "?s=" . $subject->getPrimaryKey());

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
	 * get the pagination indexes buttons
	 * @param currentPage the current displayed page
	 * @param pageWidth the interval amount of pages adjacent to current page
	 * @param n_pages total number of pages
	 * @param link the page pagination button will direct to, without the page value
	 * @return content as html string
	 */
	function pagination($currentPage, $pageWidth, $n_pages, $link){
		if ($currentPage == NULL || $link == NULL) return "";
		$pageWidth = ceil($pageWidth);
		
		function pagButton($class, $link, $idx){
			return 
			  '<a class="pag_button '.$class.'" href="'. $link . '">'
			.	$idx
			. '</a>';
		}
		
		# offset of pagination indexes
		$left_offset 	= $currentPage - $pageWidth;
		$right_offset 	= $currentPage + $pageWidth;

		# expand the upper or lower interval if cur page is in beginning or end of page list length
		if($left_offset < 1){
			$right_offset = min($n_pages, ($right_offset+ abs($left_offset-1)));
		}
		if($right_offset > $n_pages){
			$left_offset = max(1, ($left_offset - abs($n_pages-$right_offset)));
		}

		# correct the interval to match the size of the page list length
		$i 		= max($left_offset, 1); # start offset -> min value: 1
		$maxlim = min($right_offset, $n_pages);

		# page index
		$pagIdxBtns = "";
		for(; $i <= $maxlim; $i++){
			$class = "";
			if($i == $currentPage)
				$class = " pag_button_current";
			$pagIdxBtns .= pagButton($class, $link."&p=".$i, $i);
		}
		
		$prevPage 	= max($currentPage-1, 1);
		$nextPage 	= min($currentPage+1, $maxlim);
		
		return
			'<div id="pag_nav">'
			. 	pagButton("pag_button_dir", $link."&p=1",				"<i id='pag_first' class='material-icons'>first_page</i>") 
			. 	pagButton("pag_button_dir", $link."&p=".$prevPage,	"<i id='pag_prev'  class='material-icons'>navigate_before</i>")
			. 	$pagIdxBtns
			. 	pagButton("pag_button_dir", $link."&p=".$nextPage,	"<i id='pag_next'  class='material-icons'>navigate_next</i>")
		 	. 	pagButton("pag_button_dir", $link."&p=".$n_pages,		"<i id='pag_last'  class='material-icons'>last_page</i>")
			. '</div>';
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
