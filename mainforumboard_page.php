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
			echo setTitle("forum");
		 ?>
	</header>
	<main>
		<?php
			echo getBreadcrum();
			echo '<div id="forum_content">'
				. 	mainForum()
				. '</div>';
			
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
		
		$threads_arr = readAllThreadsWhere(NULL);
		$n_threads = count($threads_arr);
		$cont .= '<article id="subjects">';
		
		$s = readSubjects();
		$cont .="<div id='forum_navigator_list'>"
			. 	displayForumSubjects($s)
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



?>
