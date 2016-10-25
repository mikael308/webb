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
	
	
	startSession();
	logoutListener();
	restrictedToAuthorized("registeruser_page.php"); 
	
	function getReq($index){
		if(isset($_GET[$index])){
			return $_GET[$index];
		}
		return NULL;
	}
	function getReqSubject(){
		return getReq("s");
	}
	function getReqPage(){
		$p = getReq("p");
		if($p == NULL) $p = 1;
		return $p;
	}

	
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
			echo setTitle("forum");
		 ?>
	</header>
	<main>
		<?php
		$subject = readSubject(getReqSubject());
		if($subject != NULL){
			echo getBreadcrum($subject, NULL);
			echo "<article id='forum_content'>";
			echo '<div id="topic" class="header"  >' . $subject->getTopic() . '</div>';
			echo '<a class="button" href="post_page.php?s='.$subject->getPrimaryKey().'">new thread</a>';
			echo '<div id="forum_content">'
				.	 subjects($subject)
				. '</div>';	
			echo "</article>";
		}
		
			
		?>

	</main>
	<footer>
		<?php echo getMainFooterContent(); ?>
	</footer>
</body>
</html>
<?php

	function subjects($subject){
		
		$cont = "";
		
		$cont .= "<div id='forum_navigator_list'>";
		$threads = readAllThreadsWhere("thread.subject=" . $subject->getPrimaryKey());
		
		if($threads == NULL){
			$cont .= '<p>subject contains 0 threads</p>';
			
		} else { # query OK
			
			foreach($threads as $thread){
				$cont .= displayThreadLink($thread);
			}	
		}
		$cont .= "</div>"; # ! threadlist
		
		return $cont;
	}



?>
