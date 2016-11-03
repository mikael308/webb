<!DOCTYPE html>
<?php
/**
 * @author Mikael Holmbom	
 * @version 1.0
 */

	# autoload classes
	spl_autoload_register(function($class) {
		require_once 'classes/' . $class . '.class.php';
	});
	
	require_once "sections.php";
	require_once "database.php";
	require_once "listeners.php";
	require_once "display_format.php";
	require_once "settings.php";
	require_once "forumformat.php";
	require_once "forumformat_main.php";
	require_once "forumformat_subject.php";
	require_once "forumformat_thread.php";
	
	startSession();
	logoutListener();
	restrictedToAuthorized("registeruser_page.php");
	
	/**
	 * index of request variables
	 */
	$requestIndex = array(
		"page" => "p",
		"subject" => "s",
		"thread" => "t"
	);
	
?>

<html>
<head>
<?php	
	echo getMainHeadContent();
	echo getStylesheet("forum.css");
	
;?>	
</head>
<body>
	<header>
		<?php 
			echo getMainHeaderContent();
			
			$title = "forum";
			$targ = NULL;
			if(issetGet("subject")){
				$targ = readSubject(get("subject"));
				
			} elseif(issetGet("thread")){
				$targ = readThread(get("thread"));
			}
			if($targ != NULL)
					$title .= ":" . $targ->getTopic();
			echo setTitle($title);
		 ?>
	</header>
	<main>
		<?php
		
			$index 		= 'main';
			$indexVal 	= '';
			
			if(issetGet("thread")){
				$index 		= "thread";
				$indexVal 	= get("thread");
			
			} elseif (issetGet("subject")){
				$index 		= "subject";
				$indexVal 	= get("subject");
			}
			
			echo forum($index, $indexVal, get("page"));
			
			
		
		?>

	</main>
	<footer>
		<?php echo getMainFooterContent(); ?>
	</footer>
</body>
</html>
<?php





?>
