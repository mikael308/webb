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
		$max_pages 	= count::maxPagesSubject($subject);

		if($i > $max_pages)
			return errorMessage("invalid page number");
		if($n_threads < 1)
			return infoMessage("subject contains no threads");
		
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
	function threadLinkView(ForumThread $thread){
		if ($thread == NULL) 
			return errorMessage("thread could not be displayed");

		$tlink = getDisplayThreadLink($thread, 1);
		$creator = $thread->getCreator();
		$lastAttr = $thread->getLastAttributor();
		return  	
	 	 	  '<div class="forum_content_listitem forum_thread">' 	
			.	'<a href="'.$tlink.'">'
			. 		'<h3 class="topic">'
			. 			$thread->getTopic()
			.		'</h3>'
			. 	'</a>'			
			.	'<div class="info">'
			.		'<div class="authors">'
			.			'<div class="creator">created by: <a class="clickable" href="'.getDisplayUserLink($creator->getPrimaryKey()).'">'.$creator->getName().'</a></div>'
			.			'<div class="lastAttributor">last: <a class="clickable" href="'.getDisplayUserLink($lastAttr->getPrimaryKey()).'">'.$lastAttr->getName().'</a></div>'
			.		'</div>'
			.		'<div class="indexlink"><div class="label">index:</div>'
			.			threadInnerPag($thread)
			.		'</div>'
			. 	'</div>' 
			. '</div>';

	}
	function threadlinkPagButton($thread, $index){
		return '<a class="clickable" href="' . getDisplayThreadLink($thread, $index) . '">' . $index . '</a>';
	}
	function threadInnerPag(ForumThread $thread){
		$cont = '';

		$maxPages = count::maxPagesThread($thread);
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
		return 
			  '<div class="threadlink_pagination">'
			. 	$cont 
			. '</div>';
	}

?>
