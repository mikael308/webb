<?php
	/**
	 * sections containing information about forum content
	 * @author Mikael Holmbom
	 * @version 1.0
	 */

	require_once "./database/Extract.php";
	require_once "./sections/forum/main.php";
	require_once "./sections/views.php";
	require_once "./session/authorization.php";


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
		if($thread == NULL){
			return "";
		}
		$maxlength = 40;
		$lastAuthor = $thread->getLastAttributor();
		return
			'<div class="latestThreadViewRef">'
			. 	'<a href="'.getDisplayThreadLink($thread, 1).'">'
			.			'<div class="topic">'
			.				textToLength($thread->getTopic(), $maxlength)
			.			'</div>'
			.		'</a>'
			. 	'<a href="'.getDisplayThreadLink($thread, $thread->getLastPageIndex()).'">'
			.			'<div class="message">'
			.				textToLength($thread->getLastPost()->getMessage(), $maxlength)
			.			'</div>'
			.		'</a>'
			. 	'<a href="'.getDisplayUserLink($lastAuthor->getPrimaryKey()).'">'
			.			'<div class="author">'
			.			textToLength($lastAuthor->getName(), $maxlength)
			.			'</div>'
			. 	'</a>'
			. '</div>';
	}
	/**
	 * get div of latests posts per thread
	 * the amount of displayed threads defined in settings n_latest_threads
	 * @return as html
	 */
	function displayLatestThreads(){
		if(! userIsAuthorized()){
			return "";
		}

		$amount = (int) readSettings("n_latest_threads");
		$threadscont = "";
		$threads = Extract::latestThreads($amount);
		foreach($threads as $thread){
			$threadscont .= threadsLatestPostView($thread);
		}

		return
			'<div id="latestThreads">'
			. '<h1>latest posts</h1>'
			. 	$threadscont
			. '</div>';

	}

?>
