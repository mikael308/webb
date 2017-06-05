<?php
	/**
	 * sections containing information about forum content
	 * @author Mikael Holmbom
	 * @version 1.0
	 */

	require_once "./database/Extract.php";


	/**
	 * format string to a maxlength
	 * @param text the text to format
	 * @param max max string length
	 * @return formatted string
	 */
	function textToLength($text, $max){
		if(strlen($text) <= $max)
			return $text;
		return substr($text, 0, $max)."...";
	}

	/**
	 * get a summary view of a threads latest post
	 * @return as html
	 */
	function threadsLatestPostView(ForumThread $thread){
		return
			'<div class="latestThreadViewRef">'
			. '<a href="forum.php?t='.$thread->getId().'&p='.$thread->getLastPageIndex().'" >'
			. 	'<div class="topic">'
			.			textToLength($thread->getTopic(), 8)
			. 	'</div>'
			. 	'<div class="message">'
			.			textToLength($thread->getLastPost()->getMessage(), 8)
			. 	'</div>'
			. 	'<div class="author">'
			. 		textParser($thread->getLastAttributor()->getName(), 14)
			. 	'</div>'
			. '</a>'
			. '</div>';
	}

	function displayLatestThreads(){
		$amount = (int) readSettings("n_latest_threads");
		$threadscont="";
		$threads=Extract::latestThreads($amount);
		foreach($threads as $thread){
			$threadscont .= threadView($thread);
		}
		return
			'<div id="latestThreads">'
			. 	$threadscont
			. '</div>';

	}

?>
