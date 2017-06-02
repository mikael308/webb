<?php
/**
 * helper functions to display the content of a ForumThread
 * @author Mikael Holmbom
 */

	require_once "./config/pageref.php";
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
	
		$n_postsPerPage = readSettings("posts_per_page");
		$i 				= ($page -1) * $n_postsPerPage; # start offset

		$forumpost_arr 	= read::postsFromThread($thread->getPrimaryKey());
		$maxlim 		= ($n_postsPerPage + $i);
		$n_posts 		= count($forumpost_arr);
		$max_pages		= count::maxPagesThread($thread);
		
		if($page < 1 || $page > $max_pages)
			return errorMessage("invalid page number");
		if($n_posts < 1)
			return infoMessage("subject contains no threads");

		$cont = "";
		# iterate over posts 
		# from i to maxpage interval or end of thread
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
		
		$editMessageForm = editable($post) ? editMessageForm($post) : "";
		$deletePostForm = editable($post) ? deletePostForm($post) : "";
		
		$edited = ($post->getEdited() != NULL && $post->getCreated() != $post->getEdited()) ? 
			'<div class="edited">edited: ' . formatDateTime($post->getEdited()) . '</div>' 
			: ""; 
		
		$author = $post->getAuthor();

		return '<div class="forum_content_listitem forum_post">'
			. '<div class="author">'
				. '<div class="name"><a href="'.$GLOBALS['user_page'] .'?u='.$author->getPrimaryKey().'">' . $author->getName() . '</a></div>'
				. '<div class="role">' . $author->getRole() . '</div>'
				. '<div class="registered">registered<br>'.formatDate($author->getRegistered()).'</div>'
			. '</div>'
			. '<div class="post">'
				. '<div class="message">' . $post->getMessage() . '</div>'
				. '<div class="dates">'
				. 	'<div class="created">created: ' . formatDateTime($post->getCreated()) . '</div>'
				. 	$edited
				. '</div>'
			. '</div>'
			. 	'<div class="option_panel">' 
			.		tooltip($editMessageForm, "edit post")
			.		tooltip($deletePostForm, "delete post")
			.	'</div>'
		. '</div>';
	}
	/**
	 * determine if current authorized user can edit param post
	 * @return True if post can be edited by current authorized user
	 */
	function editable(ForumPost $post){
		if($post == null){
			return False;
		}
		
		$authUser = getAuthorizedUser();
		if($authUser->isAdmin() || $authUser->isModerator()){
			return True;
		}
		
		# if user is author of message
		if($authUser->getPrimaryKey() == $post->getAuthor()->getPrimaryKey()){
			$a = date($GLOBALS['timestamp_format']);
			$b = $post->getCreated();
		
			$diff = strtotime($a) - strtotime($b);
			# if message was created within past 15min
			if($diff < (60 * 15)){
				return True;
			}
			
		}
		
		return False;
	}
	function editMessageForm(ForumPost $post){
		return
			'<form class="edit_post" name="" method="POST" action="'.htmlspecialchars($GLOBALS['post_page'] ).'" >'
			.		'<input type="hidden" name="post" value="' . $post->getPrimaryKey() . '" />'
			.		'<input type="hidden" name="msg" value="' . $post->getMessage() . '" />'
			# the page index to redirect after edit
			.		'<input type="hidden" name="p" value="' . $_GET['p'] . '" />'
			.		'<input type="submit" class="icon_button material-icons" value="edit" name="edit_post"></input>'
			. '</form>';
	}
	
	function deletePostForm(ForumPost $post){
		return
			'<form class="delete_post" name="" method="POST" action="'.htmlspecialchars($GLOBALS['post_page'] ).'" >'
			# the post to delete
			.		'<input type="hidden" name="post" value="' . $post->getPrimaryKey() . '" />'
			# the page index to redirect after deletion
			.		'<input type="hidden" name="p" value="' . $_GET['p'] . '" />'
			.		'<input type="submit" class="icon_button material-icons" value="delete" name="delete_post"></input>'
			. '</form>';
	}
	

?>
