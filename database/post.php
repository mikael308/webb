
<?php
/**
 *
 * @author Mikael Holmbom
 * @version 1.0
 */	

	require_once "./config/pageref.php";
	require_once "./sections/main.php";
	require_once "./sections/views.php";
	require_once "./sections/dateformat.php";
	require_once "./database/database.php";
	require_once "./database/read.php";
	require_once "./session/main.php";	
	require_once "./config/settings.php";
	require_once "./security/helper.php";

	autoloadDAO();
	startSession();
	
	
	/**
	 * get form to create forumthread as html string\n
	 * get the last page if requested index is over thread bound or param pageidx is null
	 * @param thread concerned thread
	 * @param pageIdx requested page index
	 * @return forum page with requested pageindex link as string 
	 */
	function getThreadPageLink(ForumThread $thread, $pageIdx = NULL){
		$posts_per_page = readSettings("posts_per_page");
		$n_posts = count(read::postsFromThread($thread->getId()));
		$last_page = ceil($n_posts / $posts_per_page);
		
		if($pageIdx == NULL || $pageIdx > $last_page)
			$pageIdx = $last_page;
			
		return $GLOBALS['forum_page'] . '?t='. $thread->getId() . '&p=' . $pageIdx; 
	}

	/**
	* create a thread with POST arguments
	*/
	function createThread(){
		$topic = $_POST['forumthread_topic'];
		$msg = cleanupMessage($_POST['forumpost_message']);
		if(get_index("s") == NULL){
			echo errorMessage('could not read subject');
		}
		
		$subj = read::subject($_SESSION['s']);
		$_SESSION['s'] = NULL;
		
		#TODO validate topic and msg

		$user = getAuthorizedUser();
		$thread = new ForumThread($topic);
		$thread->setSubjectFK($subj->getPrimaryKey());
		$timestamp = date($GLOBALS['timestamp_format']); # now
		
		$post = new ForumPost();
		$post->setAuthorFK($user->getPrimaryKey());
		$post->setMessage($msg);
		$post->setCreated($timestamp);
					
		# database
		$thread = persist::forumThread($thread, $post);
		persist::forumPost($thread, $post);
		
		# redirect to thread page
		$link = getThreadPageLink($thread, 1);
		header("Location: " . $link);
		exit();
	}
	/**
	* post a reply with data from POST\n
	* redirects to forums last page
	*/
	function postReply(){
		$msg = cleanupMessage($_POST['forumpost_message']);
		$thread = read::thread($_POST['thread']);
		
		$post = new ForumPost();
		$post->setAuthorFK(getAuthorizedUser()->getPrimaryKey());
		$post->setMessage($msg);
		
		persist::forumPost($thread, $post);
		
		# redirect to thread page
		header("Location: " . getThreadPageLink($thread));
		exit();
	}
	/**
	 * update post of POST['post'] with message of POST['forumpost_message']\n
	 * redirects to forums page of edited post
	 */
	function updatePost(){
		$newmsg = cleanupMessage($_POST['forumpost_message']);
		$post = read::forumPost($_POST['post']);
		$post->setMessage($newmsg);
		
		update::forumPost($post);
		
		# redirect to thread page
		$p = isset($_POST['p']) ? $_POST['p'] : 1; 
		$pageLink = getThreadPageLink($post->getThread(), $p);
		
		header("Location: " . getThreadPageLink($post->getThread(), $pageLink));
		exit();
		
	}
	/**
	 * delete post from database
	 */
	function deletePost(){
		$post = read::forumPost($_POST['post']);
		$thread = $post->getThread();
		if (delete::forumPost($post)){
			# redirect to thread page
			$page = isset($_POST['p']) ? $_POST['p'] : NULL;
			header("Location: " . getThreadPageLink($thread, $page));
			exit();	
		}		
	}
	/**
	 * create news and persist to database from post indexes\n
	 * on successful creation redirects to index page,
	 * else error message is shown
	 */
	function createNews(){
		$title = $_POST['news_title'];
		$msg = cleanupMessage($_POST['news_message']);
		
		$news = new News();
		
		$news->setAuthorPK(getAuthorizedUser()->getPrimaryKey());
		$news->setTitle($title);
		$news->setMessage($msg);
		
		if(persist::news($news)){
			header("Location: " . $GLOBALS['index_page']);
			exit();
		} else {
			echo errorMessage("could not create news");
		}
		
	}
	


?>