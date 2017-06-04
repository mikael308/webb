<?php
	/**
	 * sections containing information about forum content
	 * @author Mikael Holmbom
	 * @version 1.0
	 */

	require_once "./database/Extract.php";

	function threadView(ForumThread $thread){
		return
			'<a href="forum.php?t='.$thread->getId().'&p='.$thread->getLastPageIndex().'" >'
			. '<div>'
			.		$thread->getTopic()
			. " : "
			. 	$thread->getLastAttributor()->getName()
			. '</div>'
			. '</a>';
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
