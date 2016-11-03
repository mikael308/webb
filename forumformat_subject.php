<?php
/**
 * helper functions to display the content of a ForumSubject
 * @author Mikael Holmbom
 */


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

		$threads 	= readThreads(" WHERE thread.subject=" . $subject->getPrimaryKey());
	
		$tpp 		= (int) readSettings("threads_per_page");
		$i 			= ($tpp * ($page-1)); # start offset
		
		$maxlim 	= ($tpp + $i);
		$n_threads 	= count($threads);

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
		$creator = getCreator($thread);

		$cont =  	
	 	 '<div class="forum_content_listitem forum_thread">' 	
			. 	'<a href="'.$tlink.'">'
			. 		'<div id="topic" class="topic">'.$thread->getTopic().'</div>'
			. 	'</a>'			
			.	'<div id="info">'
			.		'<div class="creator">created by: <a href="'.getDisplayUserLink($creator).'">'.$creator->getName().'</a></div>'
			.		'<div class="indexlink">'
			.		'</div>'
			. 	'</div>' 
			. '</div>';

		return $cont;
	}

?>
