<?php
/**
 * helper functions to display the content of a ForumSubject
 * @author Mikael Holmbom
 */

	require_once "./sections/forum/main.php";
	require_once "./sections/views.php";
	require_once "./sections/messages.php";

	/**
	 * display list of links to threads related to subject
	 * @param subject subject containing threads to display
	 * @param page the page interval of the threads to display
	 * @return content as html string
	 */
	function forumContentListThreads($subject, $page){
		if ($subject == NULL){
			return errorMessage("could not display subject");
		}
		if ($page == NULL || $page == ""){
			return errorMessage("invalid page");
		}

		$threads 	= $subject->getThreads();
	
		$tpp 		= (int) readSettings("threads_per_page");
		$i 			= ($tpp * ($page-1)); # start offset
		
		$maxlim 	= ($tpp + $i); # end offset
		$n_threads 	= count($threads);
		$max_pages 	= Count::maxPagesSubject($subject);

		if($n_threads < 1)
			return infoMessage("subject contains no threads");
		if( $page < 1 || $page > $max_pages)
			return errorMessage("invalid pagenumber");


		$cont = "";
		# iterate over threads 
		# from i to maxpage interval or end of subject
		$max_i = min($maxlim, $n_threads);
		for (; $i < $max_i; $i++){
			$cont .= threadLinkView($threads[$i]);
		}
	
		return $cont;
	}

	/**
	 * display a link to a thread
	 * @param thread the thread to display
	 * @return content as html string
	 */
	function threadLinkView($thread){
		if ($thread == NULL)
			return errorMessage("thread could not be displayed: invalid thread");

		$tlink = getDisplayThreadLink($thread, 1);
		$creator = $thread->getCreator();
		$lastAttr = $thread->getLastAttributor();

		# generate author sections
		$divCreator =
			$creator == NULL ?
			"":
			"<div class='creator'>"
			.		"created by: <a class='clickable' "
			.			"href='".getDisplayUserLink($creator->getPrimaryKey())."'>"
			. 		$creator->getName()
			. 		"</a>"
			.	"</div>";
		$divLastAttr =
			$lastAttr == NULL ?
			"":
			"<div class='lastAttributor'>"
			.		"last: <a class='clickable' "
			.			" href='".getDisplayUserLink($lastAttr->getPrimaryKey())."'>"
			.			$lastAttr->getName()
			.			"</a>"
			.	"</div>";

		# information section of threadlink
		$divInfo =
			"<div class='info'>"
			.		"<div class='authors'>"
			.			$divCreator
			.			$divLastAttr
			.		"</div>"
			.		"<div class='indexlink'>"
			.			"<div class='label'>" . "index:" . "</div>"
			.			threadInnerPag($thread)
			.		"</div>"
			. "</div>";

		return
	 	 	"<div class='forum_content_listitem forum_thread'>"
			.		"<a href='".$tlink."'>"
			. 		"<h3 class='topic'>"
			. 			$thread->getTopic()
			.			"</h3>"
			. 	"</a>"
			.		$divInfo
			. "</div>";

	}

	/**
	 * get simple thread pagination link
	 * @return as html
	 */
	function threadlinkPagButton($thread, $index){
		return '<a class="clickable" href="' . getDisplayThreadLink($thread, $index) . '">' . $index . '</a>';
	}

	/**
	 * get pagination links to
	 * @return list of pagination links
	 */
	function getStartEndPags($thread, $pagInterval){
		if($thread == NULL)
			return "";
		$maxPages = Count::maxPagesThread($thread);
		$maxlim = min($maxPages, $pagInterval);

		$pags = array();

		$i = 1;
		# beginning indexes
		for(; $i <= $maxlim; $i++){
			$pags[$i] = threadlinkPagButton($thread, $i);
		}

		# get the end offset
		$end_offset = ($maxPages - $pagInterval) +1;
		if($i < $end_offset){
			$i = $end_offset;
		}
		# ending indexes
		for(;$i <= $maxPages; $i++){
			$pags[$i] = threadlinkPagButton($thread, $i);
		}
		return $pags;
	}

	function threadInnerPag(ForumThread $thread){
		$cont = '';
		
		$pagInterval = readSettings("pag_max_interval_threadlink");

		$lastPagIdx = 0;
		$pags = getStartEndPags($thread, $pagInterval);
		foreach($pags as $i => $pag){
			if($i != ($lastPagIdx+1)){
				# previous page was not current (index -1)
				# add a mark for stepping in index
				$cont .= " ... ";
			}
			$lastPagIdx=$i;
			$cont .= $pag;
		}

		return
			  '<div class="threadlink_pagination">'
			. 	$cont
			. '</div>';
	}

?>
