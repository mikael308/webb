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
	require_once "database.php";
	require_once "listeners.php";
	require_once "settings.php";
	require_once "display_format.php";
	
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
			
			if($_SERVER["REQUEST_METHOD"] == "GET"){
				
				if(isset($_GET['op'])) {
					switch($_GET['op']){
						case 'reply':
							if(isset($_GET['t'])){
								echo postReplyView();
							}
							break;
						case 'createthread':
							$_SESSION['s'] = $_GET['s']; #TODO till session??? subject
							echo createThreadView();
								
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
							break;
					} # ! switch op
					
				} # ! isset GET op

			} else if($_SERVER["REQUEST_METHOD"] == "POST"){
				if(isset($_POST['edit_post'])){					
					# UPDATE POST
					echo updatePostView();
	
				} else {
					echo errorMessage("unknown post");
				}
			}
			

		?>

	</main>
	<footer>
		<?php echo getMainFooterContent(); ?>
	</footer>
</body>
</html>
<?php
	/**
	* get form to create forumpost as string
	*/
	function getCreateForumPostInput($value = ""){
		return
			'<label for="forumpost_message">message</label><br>'
			. '<textarea rows="5" cols="40" name="forumpost_message" autofocus required />'.$value.'</textarea>';
	}
	/**
	* get form to create forumthread as html string
	*/
	function getCreateForumThreadInput(){
		return 
				'<label for="forumthread_topic">topic</label><br>'
				. '<input type="text" name="forumthread_topic" autofocus required ><br>';
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
		#TODO get as clean input???
		$topic = $_POST['forumthread_topic'];
		$msg = $_POST['forumpost_message'];
		if(! isset($_SESSION['s'])){
			echo 'ERROR could not read subject';
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
	/**
	 * create news and persist to database from post indexes\n
	 * on successful creation redirects to index page,
	 * else error message is shown
	 */
	function createNews(){
		$content = $_POST['news_content'];
		$title = $_POST['news_title'];
		
		$news = new News();
		
		$news->setAuthorPK(getAuthorizedUser()->getPrimaryKey());
		$news->setTitle($title);
		$news->setMessage($content);
		
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
	 * get a view to reply to post
	 * @return form as html string
	 */
	function postReplyView(){
		$thread = read::thread($_GET['t']);
		return 'thread: ' . $thread->getTopic()
			. '<form method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">'
			. 	'<input type="text" name="thread" value="'.$thread->getId().'" hidden> '
			. 	getCreateForumPostInput()
			. 	'<input type="submit" value="post" name="post_reply">'
			. '</form>';
	}
	/**
	 * get a view to create view
	 * @return form as html string
	 */
	function createThreadView(){
		return '<p>create forum thread </p>'
			. '<form method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">'
			. getCreateForumThreadInput()
			. getCreateForumPostInput() . '<br>'
			. '<input type="submit" value="create" name=create_forumthread>'
		. '</form>';
	}
	/**
	 * get a view to update post
	 * @return form as html string
	 */
	function updatePostView(){
		$post = read::forumPost($_POST['post']);
		$thread = $post->getThread();
		
		return '<p>edit ' . $thread->getTopic().'</hp>'
			. '<form method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">'
			.	'<input type="hidden" name="post" value="' . $_POST['post'] . '" >'
			.	getCreateForumPostInput($_POST['msg'])
			.	'<input type="hidden" name="p" value="' . $_POST['p'] . '" >'
			.	'<input type="submit" value="post" name="update_post">'
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
			.		tr(td('<label for="news_title">title</label>')
						. td('<input type="text" name="news_title" />'))
			.		tr(td('<label for="news_content">content</label>')
						. td('<textarea rows="5" cols="40" name="news_content" ></textarea>'))
			.		tr(td('')
						. td('<input type="submit" name="create_news" value="create" />'))
	 		.	'</table>'
			. '</form>';
	}

?>
