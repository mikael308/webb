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
			if(isset($_GET['s'])){
				$title .= ":" . $_GET['s'];
			}
			echo setTitle($title);
		 ?>
	</header>
	<main>
		<?php
		
			echo getBreadcrum(readSubject(getReqSubject()), NULL);
			echo '<div id="forum_content">';
			if(isset($_GET['s'])){
				echo subject($_GET['s']);
				
			} else {
				echo mainForum();
				
			}
			echo '</div>';
			
			
		?>

	</main>
	<footer>
		<?php echo getMainFooterContent(); ?>
	</footer>
</body>
</html>
<?php


	function mainForum(){
		
		$cont = "";
		
		$threads_arr = readThreads(NULL);
		$n_threads = count($threads_arr);
		$cont .= '<article id="subjects">';
		
		$cont .="<div id='forum_navigator_list'>"
			. 	displayForumSubjects(readSubjects())
			. "</div>";
		
		# find the lowest amount to display
		/*$n_display_threads = min(array(
			(int)readSettings("threads_per_page"), 
			$n_threads));
		
		for ($i = 0; $i < $n_display_threads; $i++){
			$thr = $threads_arr[$i];
			$cont .= displayThreadLink($thr);
		}
		 
		 */
		$cont .= '</article>';
		return $cont;
	}
	
	function subject($subject_id){
		$subject = readSubject(getReqSubject());
		if($subject != NULL){
			echo "<article id='forum_content'>";
			echo '<div id="topic" class="header"  >' . $subject->getTopic() . '</div>';
			echo '<a class="button" href="post_page.php?s='.$subject->getPrimaryKey().'">new thread</a>';
			echo '<div id="forum_content">'
				.	 subjects($subject)
				. '</div>';	
			echo "</article>";
		}
	}
	
		function subjects($subject){
		
		$cont = "";
		
		$cont .= "<div id='forum_navigator_list'>";
		$threads = readThreads(" WHERE thread.subject=" . $subject->getPrimaryKey());
		
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
