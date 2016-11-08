<?php
/**
 * helper functions to display the content of a ForumSubject
 * @author Mikael Holmbom
 */

	require_once "forumformat.php";
	require_once "display_format.php";

	/**
	 * display list of links to threads related to subject
	 * @param subject subject containing threads to display
	 * @param page the page interval of the threads to display
	 * @return content as html string
	 */
	function forumContentListThreads($subject, $page){
		if ($subject == NULL) 
			return errorMessage("could not display subject");
		if ($page == NULL || $page == "") 
			return errorMessage("invalid page");

		$threads 	= read::threads(" WHERE thread.subject=" . $subject->getPrimaryKey());
	
		$tpp 		= (int) readSettings("threads_per_page");
		$i 			= ($tpp * ($page-1)); # start offset
		
		$maxlim 	= ($tpp + $i); # end offset
		$n_threads 	= count($threads);
		$max_pages 	= getMaxPagesSubject($subject);

		if($i > $max_pages)
			return errorMessage("invalid page number");
		if($n_threads < 1)
			return infoMessage("subject contains no threads");
		
		$cont = "";
		for (; $i < $maxlim && $i < $n_threads; $i++){
			$cont .= threadLinkView($threads[$i]);
		}
	
		return $cont;
	}

	/**
	 * display a link to a thread
	 * @param thread the thread to display
	 * @return content as html string
	 */
	function threadLinkView(ForumThread $thread){
		if ($thread == NULL) 
			return errorMessage("thread could not be displayed");

		$tlink = getDisplayThreadLink($thread, 1);
		$creator = $thread->getCreator();
		$lastAttr = $thread->getLastAttributor();
		$cont =  	
	 	 '<div class="forum_content_listitem forum_thread">' 	
			. 	'<a href="'.$tlink.'">'
			. 		'<div id="topic" class="topic">'.$thread->getTopic().'</div>'
			. 	'</a>'			
			.	'<div id="info">'
			.		'<div class="authors">'
			.			'<div class="clickable creator">created by: <a href="'.getDisplayUserLink($creator->getPrimaryKey()).'">'.$creator->getName().'</a></div>'
			.			'<div class="clickable lastAttributor">last: <a href="'.getDisplayUserLink($lastAttr->getPrimaryKey()).'">'.$lastAttr->getName().'</a></div>'
			.		'</div>'
			.		'<div class="indexlink"><div class="label">index:</div>'
			.			threadInnerPag($thread)
			.		'</div>'
			. 	'</div>' 
			. '</div>';

		return $cont;
	}
	function threadlinkPagButton($thread, $index){
		return '<a href="' . getDisplayThreadLink($thread, $index) . '">' . $index . '</a>';
	}
	function threadInnerPag(ForumThread $thread){
		$cont = "<div class='clickabe threadlink_pagination'>";

		$maxPages = getMaxPagesThread($thread);
		$paginterval = readSettings("pag_max_interval_threadlink");
		$maxlim = min($maxPages, $paginterval);
		$i = 1;
		# beginning indexes
		for(; $i <= $maxlim; $i++){
			$cont .= threadlinkPagButton($thread, $i);
		}
		
		$end_offset = ($maxPages - $paginterval) +1;
		if($i < $end_offset){
			$i = $end_offset;
		}
		if($maxPages > ($paginterval * 2)){ # mid part
			$cont .= " ... ";
		}
		# ending indexes
		for(;$i <= $maxPages; $i++){
			$cont .= threadlinkPagButton($thread, $i);
		}
		return $cont . '</div>';
	}

?>
