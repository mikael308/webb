<!DOCTYPE html>
<?php
/**
 * @author Mikael Holmbom	
 * @version 1.0
 */


	
	require_once "./config/pageref.php";
	require_once "./sections/sections.php";
	require_once "./database/database.php";
	require_once "listeners.php";
	require_once "./format/display.php";
	require_once "./config/settings.php";
	require_once "./format/forum.php";
	require_once "./format/forum_main.php";
	require_once "./format/forum_subject.php";
	require_once "./format/forum_thread.php";
	
	autoloadDAO();
	startSession();
	logoutListener();
	restrictedToAuthorized($GLOBALS['register_page'] );
	
?>

<html>
<head>
<?php	
	echo getMainHeadContent();
	echo getStylesheet("forum.css");
	# TITLE 
	$title = "forum";
	$topic = NULL;
	try{
		if(get_index("s") != NULL){
			$topic = read::subjects(get_index("s"))[0];
		} elseif (get_index("t") != NULL){
			$topic = read::thread(get_index("t"));
		}
	} catch(RuntimeException $e){
		
	}

	if($topic != NULL)
			$title .= ":" . $topic->getTopic();
	echo setTitle($title);

;?>	
</head>
<body>
	<header>
		<?php 
			echo getMainHeaderContent();
			
		 ?>
	</header>
	<main>
		<?php
		
			$index 		= 'main';
			$index_val 	= '';

			if (isset($_GET['t'])){
				$index 		= "thread";
				$index_val 	= get_index("t");
			} elseif (isset($_GET["s"])){
				$index 		= "subject";
				$index_val 	= get_index("s");
			} 

			echo forum($index, $index_val, get_index("p"));
						
		?>

	</main>
	<footer>
		<?php echo getMainFooterContent(); ?>
	</footer>
</body>
</html>
<?php





?>
