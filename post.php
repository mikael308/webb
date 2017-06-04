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

	require_once "./config/pageref.php";
	require_once "./config/settings.php";
	require_once "./sections/main.php";
	require_once "./sections/views.php";
	require_once "./sections/post.php";
	require_once "./sections/dateformat.php";
	require_once "./database/database.php";
	require_once "./database/post.php";
	require_once "./session/authorization.php";
	require_once "./session/main.php";
	require_once "./session/requests.php";
	require_once "./security/helper.php";

	autoloadDAO();
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


?>
