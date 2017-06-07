<?php
/**
 * helper functions to display the content of a ForumThread
 * @author Mikael Holmbom
 */

	require_once "./config/pageref.php";
	require_once "./database/post.php";
 	require_once "./sections/forum/main.php";
	require_once "./session/main.php";	

	/**
	 * display forumcontent list of posts
	 * @param thread the thread containing posts
	 * @param page the page determing the interval start offset of posts
	 * @return content as html string
	 */
	function forumContentListPosts(ForumThread $thread, $page){
		if ($thread == NULL) 
			return errorMessage("could not display thread");
		if ($page == NULL || $page == "") 
			return errorMessage("invalid page");
	

		# start offset
		$n_postsPerPage = readSettings("posts_per_page");
		$i = ($page -1) * $n_postsPerPage;

		$forumpost_arr = Read::postsFromThread($thread->getPrimaryKey());
		# upper limit of pages
		$maxlim = ($n_postsPerPage + $i);
		$n_posts = $thread->postsSize();
		$max_pages = Count::maxPagesThread($thread);

		if($n_posts < 1)
			return infoMessage("thread contains no posts");
		if($page < 1 || $page > $max_pages)
			return errorMessage("invalid page number");

		$cont = "";
		# iterate over posts 
		# from i to maxpage interval or end of thread
		$max_i = min($maxlim, $n_posts);
		for (; $i < $max_i; $i++){
			$cont .= forumPostView($forumpost_arr[$i]);
		}
		return $cont;
	}

	/**
	 * display a ForumPost instance
	 * @param post the forumpost to display
	 * @return content as html string
	 */
	function forumPostView(ForumPost $post){
		if ($post == NULL) 
			return errorMessage("could not display post");

		# buttons for optionpanel of the postview
		$editMessageForm = editable($post) ? editMessageForm($post) : "";
		$deletePostForm = editable($post) ? deletePostForm($post) : "";

		# if the post was edited after it was created
		$edited =
			$post->isEdited() ?
			"<div class='edited'>edited: " . formatDateTime($post->getEdited()) . "</div>"
			: "";

		$author = $post->getAuthor();

		return
		"<div class='forum_content_listitem forum_post'>"
			# information about the author of the post
			. "<div class='author'>"
				. "<div class='name'>"
				.		"<a href='".$GLOBALS["user_page"] ."?u=".$author->getPrimaryKey()."'>"
				. 		$author->getName()
				. 	"</a>"
				.	"</div>"
				. "<div class='role'>"
				. 	$author->getRole()
				. "</div>"
				. "<div class='registered'>registered<br>"
				.		formatDate($author->getRegistered())
				.	"</div>"
			. "</div>"
			# the actual post message
			. "<div class='post'>"
				. "<div class='message'>"
				. 	$post->getMessage()
				. "</div>"
				. "<div class='dates'>"
				. 	"<div class='created'>created: " . formatDateTime($post->getCreated()) . "</div>"
				. 	$edited
				. "</div>"
			. "</div>"

			. "<div class='option_panel'>"
			.		tooltip(
						$editMessageForm,
						'edit post'
					)
			.		tooltip(
						$deletePostForm,
						'delete post'
					)
			.	"</div>"
		. "</div>";
	}

	/**
	 * get form used for editing ForumPost message<br>
	 * contains:<br>
	 * submit button (go to edit view)
	 * @param post post to edit
	 * @return form as html
	 */
	function editMessageForm(ForumPost $post){
		return
			"<form class='edit_post' name=' method='POST' action='".htmlspecialchars($GLOBALS["post_page"] )."' >"
			.		"<input type='hidden' name='post' value='" . $post->getPrimaryKey() . "' />"
			# the page index to redirect after edit
			.		"<input type='hidden' name='p' value='" . $_GET["p"] . "' />"
			.		"<input type='submit' class='icon_button material-icons' value='edit_post' name='op'></input>"
			. "</form>";
	}
	/**
	 * get form used for deleting ForumPost<br>
	 * contains:<br>
	 * submit delete button
	 * @param post post to delete
	 * @return form as html
	 */
	function deletePostForm(ForumPost $post){
		return
			"<form class='delete_post' method='POST' action='".htmlspecialchars($GLOBALS["post_page"] )."' >"
			# the post to delete
			.		"<input type='hidden' name='post' value='" . $post->getPrimaryKey() . "' />"
			# the page index to redirect after deletion
			.		"<input type='hidden' name='p' value='" . $_GET["p"] . "' />"
			.		"<input type='submit' class='icon_button material-icons' value='delete' name='delete_post'></input>"
			. "</form>";
	}


?>
