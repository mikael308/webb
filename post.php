<!DOCTYPE html>
<?php
/**
 * page used to create forumthread or forumpost\n
 * RESTRICTED to authorized user
 *
 * the GET variable has index of op:
 * 	# reply : reply to existing thread\n
 *					must include GET attr:
 *						- t: the thread PK to reply to
 * # createthread : create a new thread to a subject\n
 *					must include GET attr:
 *						- s: the subject to add thread to
 * # news : create a news article. user must be admin
 * # edit_post : edit a existing post\n
 *					must include GET attr:\n
 *						- the PK of post to edit
 *
 * @author Mikael Holmbom
 * @version 1.0
 */	

	require_once "./config/pageref.php";
	require_once "./config/settings.php";
	require_once "./database/database.php";
	require_once "./database/post.php";
	require_once "./sections/dateformat.php";
	require_once "./sections/main.php";
	require_once "./sections/messages.php";
	require_once "./sections/post.php";
	require_once "./sections/views.php";
	require_once "./security/helper.php";
	require_once "./session/authorization.php";
	require_once "./session/main.php";
	require_once "./session/requests.php";

	autoloadDAO();
	startSession();
	restrictedToAuthorized($GLOBALS['index_page']);
	
		
	/*
	 * listens for submissions from forms on this page
	 */
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["create_forumthread"])){
			createThread();
		} elseif (isset($_POST["post_reply"])){
			postReply();
		} elseif (isset($_POST["update_post"])){
			updatePost();
		} elseif(isset($_POST["delete_post"])){
			deletePost();
		} elseif(isset($_POST["create_news"])){
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
		<?php
			echo getMainHeaderContent();
			echo getStylesheet("post.css");
		?>
	</header>
	<main>
		<?php
			$user = getAuthorizedUser();
		
			try{
				if($_SERVER["REQUEST_METHOD"] == "GET"){
					handleGetOp();
		
				} else if($_SERVER["REQUEST_METHOD"] == "POST"){

				}
			} catch(RuntimeException $e){
				echo errorMessage("could not handle request");
				#echo $e->message;
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

		if(isset($_GET["op"])) {
			switch($_GET["op"]){
				case "reply":
					echo postReplyView();
					break;
				case "createthread":
					echo createThreadView();
					break;
				case "news":
					echo createNewsView();
					break;
				case "edit_post":
					echo updatePostView();
					break;
				default:
					echo errorMessage("unknown operation");
					return False;
					break;
			} # ! switch op
			return True;

		} else {
			# ! isset GET op
			echo errorMessage("invalid operation");
		}

		return True;
	}


?>
