<?php
/**
 * helper functions to display the content of a ForumThread
 * @author Mikael Holmbom
 */

 	require_once "forumformat.php";

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

		$forumpost_arr 	= readPostsFromThread($thread->getPrimaryKey());
		$maxlim 		= ($n_postsPerPage + $i);
		$n_posts 		= count($forumpost_arr);
		$max_pages		= getMaxPagesThread($thread);
		
		if($page < 1 || $page > $max_pages)
			return errorMessage("invalid page number");
		if($n_posts < 1)
			return infoMessage("subject contains no threads");

		$cont = "";
		for (; $i < $maxlim && $i < $n_posts; $i++){
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
		
		$author = $post->getAuthor();
		return '<div class="forum_content_listitem forum_post">'
			. '<div class="author">'
				. '<div class="name"><a href="viewuser_page.php?u='.$author->getPrimaryKey().'">' . $post->getAuthor()->getName() . '</a></div>'
				. '<div class="role">' . $author->getRole() . '</div>'
				. '<div class="registered">registered<br>'.formatDate($author->getRegistered()).'</div>'
			. '</div>'
			. '<div class="post">'
				. '<div class="message">' . $post->getMessage() . '</div>'
				. '<div class="created">created:' . $post->getCreated() . '</div>'
			. '</div>'
		. '</div>';
	}
	

?>
