<!DOCTYPE html>
<?php
/**
 * page used to create forumthread or forumpost\n
 * the GET variable has index of op
 * 	<ul>
 * 	<li>
 * 		<h1>reply</h1> reply to a existing thread\n must contain GET attr: 
 * 		<table>
 * 			<tr><th>t</th><td>the thread PK to reply to</td><tr>
 * 			<tr><th>p</th><td>the page index to redirect back to</td><tr>
 * 		</table>
 * 	</li>
 * 	<li>
 * 		<h1>createthread</h1> create a new thread to a subject\n must contain GET attr:
 * 		<table>
 * 			<tr><th>s</th><td>the post PK to reply to</td><tr>
 * 			<tr><th>p</th><td>the page index to redirect back to</td><tr>
 * 		</table>
 * 	</li>
 * 	<li> 
 * 		<h1>news</h1> create a news article. user must be admin
 * 	</li>
 * </ul>
 * 
 *
 * @author Mikael Holmbom
 * @version 1.0
 */	

	# autoload classes
	spl_autoload_register(function($class) {
		include 'classes/' . $class . '.class.php';
	});
	
	require_once "pageref.php";
	require_once "sections.php";
	require_once "./database/database.php";
	require_once "./database/read.php";
	require_once "listeners.php";
	require_once "settings.php";
	require_once "./format/display.php";

	startSession();
	restrictedToAuthorized($GLOBALS['index_page']);
	
		
	/*
	 * listens for submissions from forms on this page
	 */
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST['create_forumthread'])){
			createThread();
		} elseif (isset($_POST['post_reply'])){
			postReply();
		} elseif (isset($_POST['update_post'])){
			updatePost();
		} elseif(isset($_POST['delete_post'])){
			deletePost();
		} elseif(isset($_POST['create_news'])){
			createNews();
		}
	}

?>

<html>
<head>
<?php	echo getMainHeadContent();?>	
</head>
<body>
	<header>
		<?php echo getMainHeaderContent(); ?>
	</header>
	<main>
		<?php
			$user = getAuthorizedUser();
			try{
				if($_SERVER["REQUEST_METHOD"] == "GET"){
					handleGetOp();
		
				} else if($_SERVER["REQUEST_METHOD"] == "POST"){
					if(isset($_POST['edit_post'])){					
						echo updatePostView();
		
					} else {
						echo errorMessage("unknown post");
					}
				}
			} catch(RuntimeException $e){
				echo $e->message;
			

		?>

	</main>
	<footer>
		<?php echo getMainFooterContent(); ?>
	</footer>
</body>
</html>
<?php

	/**
	 * handle get variable op
	 * @return boolean True if operation was parsed successful
	 */
	function handleGetOp(){

		if(isset($_GET['op'])) {
			switch($_GET['op']){
				case 'reply':
					if(get_index('t') != NULL){
						echo postReplyView();
					} else {
						echo errorMessage("invalid thread");
					}
					break;
				case 'createthread':
					$_SESSION['s'] = $_GET['s']; #TODO till session??? subject
					if(get_index('s') != NULL){
						echo createThreadView();
					} else {
						echo errorMessage("invalid subject");
					}
					break;
				case 'news':
					if(getAuthorizedUser()->isAdmin()){
						echo createNewsView();
					} else {
						echo errorMessage("access denied: admin only");
					}
						
					break;
				default:
					echo errorMessage("unknown operation");
					return False;
					break;
			} # ! switch op
			return True;
				
		} # ! isset GET op
		
		return True;
	}

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
		$msg = $_POST['forumpost_message'];
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
		$msg = $_POST['forumpost_message'];
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
		$newmsg = $_POST['forumpost_message'];
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
		$msg = $_POST['news_message'];
		
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
	
	/* *************************************
	 * 
	 *  VIEWS to operate on database
	 * 
	 * *************************************/
	
	/**
	 * get form to create forumthread as html string
	 */
	function getForumThreadtopicRow(){
		return
		td('<label for="forumthread_topic">topic</label>')
		. td('<input type="text" name="forumthread_topic" autofocus required >');
	}
	/**
	 * get form to create forumpost as string
	 */
	function getCreateForumPostMessageRow($value = ""){
		return
			td('<label for="forumpost_message">message</label>')
			. td('<textarea rows="5" cols="40" name="forumpost_message" autofocus required />'.$value.'</textarea>');
	}
	/**
	 * get a view to reply to post
	 * @return form as html string
	 */
	function postReplyView(){
		$thread = read::thread(get_index('t'));
		return '<h3>reply to thread: ' . $thread->getTopic() . '</h3>'
			. '<form method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">'
			. 	'<table>'
			. 		'<input type="hidden" name="thread" value="'.$thread->getId().'" /> '
			.		tr(getCreateForumPostMessageRow())
			.		tr(td('')
						.td('<input type="submit" value="post" name="post_reply">'))
			. 	'</table>'
			. '</form>';
	}
	/**
	 * get a view to create view
	 * @return form as html string
	 */
	function createThreadView(){
		return '<h3>create forum thread </h3>'
			. '<form method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">'
			. 	'<table>'
			.		tr(getForumThreadtopicRow())
			.		tr(getCreateForumPostMessageRow())
			. 		tr(td('')
						. td('<input type="submit" value="create" name=create_forumthread>'))
			. 	'</table>'
			. '</form>';
	}
	/**
	 * get a view to update post
	 * @return form as html string
	 */
	function updatePostView(){
		if(! isset($_POST['msg']) || ! isset($_POST['post']))
			return errorMessage("could not update post");
		
		$post = read::forumPost($_POST['post']);
		$thread = $post->getThread();
		
		return '<h3>edit ' . $thread->getTopic().'</h3>'
			. '<table>'
			. '<form method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">'
			.	'<input type="hidden" name="post" value="' . $_POST['post'] . '" >'
			.	'<input type="hidden" name="p" value="' . $_POST['p'] . '" >'
			.	tr(getCreateForumPostMessageRow($_POST['msg']))
			.	tr(td('')
					. td('<input type="submit" value="post" name="update_post">'))
			. '</table>'
			. '</form>';	
	}
	/**
	 * newsview\n get a form to post and create news
	 * @return string as form
	 */
	function createNewsView(){
		return '<h3>create newsfeed</h3>'
			. '<form method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">'
			.	'<table>'
					# news TITLE
			.		tr(td('<label for="news_title">title</label>')
						. td('<input type="text" name="news_title" />'))
					# news MESSAGE
			.		tr(td('<label for="news_message">message</label>')
						. td('<textarea rows="5" cols="40" name="news_message" ></textarea>'))
					# SUBMIT BUTTON
			.		tr(td('')
						. td('<input type="submit" name="create_news" value="create" />'))
	 		.	'</table>'
			. '</form>';
	}

?>
