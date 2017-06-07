<?php
	/**
	 * sections used for posting to database
	 * @author Mikael Holmbom
	 * @version 1.0
	 */

	 require_once "./database/post.php";


	/**
	 * get form to create forumthread as html string
	 */
	function getForumThreadtopicRow(){
		return
			td("<label for='forumthread_topic'>topic</label>")
			. td("<input type='text' name='forumthread_topic' autofocus required >");
	}
	/**
	 * get form to create forumpost as string
	 */
	function getCreateForumPostMessageRow($value = ""){
		return
			td("<label for='forumpost_message'>message</label>")
			. td(
				"<textarea rows='5' cols='40' name='forumpost_message' autofocus required />"
				. 	$value
				. "</textarea>"
			);
	}
	/**
	 * get a form view to reply to post
	 * request index T: PK of thread to reply to
	 * @return form as html string
	 */
	function postReplyView(){
		$thread = Read::thread(get_index("t"));
		return
			"<h3>reply to thread: " . $thread->getTopic() . "</h3>"
			. "<form method='POST' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'>"
			. 	"<table>"
			# hidden
			. 		"<input type='hidden' name='thread' value='".$thread->getId()."' /> "
			# clean message input
			.			tr(getCreateForumPostMessageRow())
			# submit
			.			tr(td("")
							.td("<input type='submit' value='post' name='post_reply'>"))
			. 	"</table>"
			. "</form>";
	}
	/**
	 * get a view to create view
	 * @return form as html string
	 */
	function createThreadView(){
		return "<h3>create forum thread </h3>"
			. "<form method='POST' action='".htmlspecialchars($_SERVER['PHP_SELF'])."'>"
			. 	"<table>"
			# clean input
			.			tr(getForumThreadtopicRow())
			.			tr(getCreateForumPostMessageRow())
			# submit
			. 		tr(td("")
						. td("<input type='submit' value='create' name=create_forumthread>"))
			. 	"</table>"
			. "</form>";
	}
	/**
	 * get a view to update post
	 * request index post: the post PK to update
	 * @return form as html string
	 */
	function updatePostView(){
		if(! isset($_GET["post"])){
			return errorMessage("could not update post");
		}

		$post = Read::forumPost($_GET["post"]);
		# post must exist or user have authority to edit
		if($post == NULL || ! editable($post)){
			return errorMessage("could not update post");
		}

		return
			"<h3>edit " . $thread->getThread()->getTopic() . "</h3>"
			. "<table>"
			. 	"<form method='POST' action='".htmlspecialchars($_SERVER['PHP_SELF'])."'>"
			# hidden values
			.			"<input type='hidden' name='post' value='" . $post->getPrimaryKey() . "' >"
			.			"<input type='hidden' name='p' value='" . $_GET["p"] . "' >"
			.			"<input type='hidden' name='op' value='edit'>"
			# post message
			.			tr(getCreateForumPostMessageRow($post->getMessage()))
			# submit
			.			tr(
							td("")
							. td("<input type='submit' value='post' name='update_post'>"))
			. "</form>"
			. "</table>";
	}
	/**
	 * newsview\n get a form to post and create news
	 * @return string as form
	 */
	function createNewsView(){
		return "<h3>create newsfeed</h3>"
			. "<form method='POST' action='".htmlspecialchars($_SERVER['PHP_SELF'])."'>"
			.	"<table>"
					# news TITLE
			.		tr(
						td("<label for='news_title'>title</label>")
						. td("<input type='text' name='news_title' />")
					)
					# news MESSAGE
			.		tr(
						td("<label for='news_message'>message</label>")
						. td("<textarea rows='5' cols='40' name='news_message' ></textarea>")
					)
					# SUBMIT BUTTON
			.		tr(
						td("")
						. td("<input type='submit' name='create_news' value='create' />")
					)
	 		.	"</table>"
			. "</form>";
	}


?>