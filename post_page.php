<!DOCTYPE html>
<?php
/**
 * page used to create forumthread or forumpost
 * 
 *
 * @author Mikael Holmbom
 * @version 1.0
 */	

	# autoload classes
	spl_autoload_register(function($class) {
		include 'classes/' . $class . '.class.php';
	});
	
	require_once "sections.php";
	require_once "database.php";
	require_once "listeners.php";
	require_once "settings.php";
	require_once "display_format.php";
	
	startSession();
	restrictedToAuthorized("index.php");
	
		
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST['create_forumthread'])){
			createThread();
			
		} elseif (isset($_POST['post_reply'])){
			postReply();
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
				if(isset($_GET['t'])){
					# REPLY TO THREAD
					$thread = read::thread($_GET['t']);
					echo 'thread: ' . $thread->getTopic()
						. '<form method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">'
						. '<input type="text" name="thread" value="'.$thread->getId().'" hidden> '
						. getCreateForumPostForm()
						. '<input type="submit" value="post" name="post_reply">'
						. '</form>';
				} elseif(isset($_GET['s'])){
					$_SESSION['s'] = $_GET['s'];
					# CREATE THREAD
					echo '<p>create forum thread </p>'
						. '<form method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">'
						. getCreateForumThreadForm()
						. getCreateForumPostForm() . '<br>'
						. '<input type="submit" value="create" name=create_forumthread>'
					. '</form>';
				}
			} else {
					
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
	function getCreateForumPostForm(){
		return
			'<label for="forumpost_message">message</label><br>'
			. '<textarea rows="5" cols="40" name="forumpost_message" required></textarea>';
	}
	/**
	* get form to create forumthread as html string
	*/
	function getCreateForumThreadForm(){
		return 
				'<label for="forumthread_topic">topic</label><br>'
				. '<input type="text" name="forumthread_topic" autofocus required><br>';
	}
		/**
	* get form to create forumthread as html string
	*/
	function getThreadpageLink(ForumThread $thread, $pageIdx){
		return 'forum_page.php?t='. $thread->getId() . '&p=' . $pageIdx; 
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
		$thread->setSubject($subj);
		$timestamp = date($GLOBALS['timestamp_format']);
		
		$post = new ForumPost();
		$post->setAuthor($user);
		$post->setMessage($msg);
		$post->setCreated($timestamp);
					
		# database
		$thread = persist::forumThread($thread, $post);
		persist::forumPost($thread, $post);
		
		# redirect to thread page
		$link = getThreadpageLink($thread, 1);
		header("Location: " . $link);
		exit();
	}
	/**
	* post a reply with data from POST
	*/
	function postReply(){
		$msg = $_POST['forumpost_message'];
		$thread = read::thread($_POST['thread']);
		
		$post = new ForumPost();
		$post->setAuthor(getAuthorizedUser());
		$post->setMessage($msg);
		
		persist::forumPost($thread, $post);
		
		# redirect to thread page
		header("Location: " . linkLastPageOf($thread));
		exit();
	}
	
	/**
	 * get the last page of thread
	 * @param thread requested thread
	 * @return last page index of threads post
	 */
	function linkLastPageOf(ForumThread $thread){
		$posts_per_page = readSettings("posts_per_page");
		$n_posts = count(read::postsFromThread($thread->getId()));
		$last_page = ceil($n_posts / $posts_per_page);
		
		return getThreadpageLink($thread, $last_page);
	}
?>
