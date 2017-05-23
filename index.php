<!DOCTYPE html>
<?php
/**
 * index page\n
 * available for non-auhtorized users\n
 * functions: 
 * <ul>
 * <li>newsfeed</li>
 * <li>login</li>
 * <li>link to register new user</li>
 * </ul>
 * @author Mikael Holmbom
 * @version 1.0
 */

	require_once "sections.php";
	require_once "./database/database.php";
	require_once "listeners.php";
	require_once "display_format.php";
	
	startSession();

	# listen for login submit
	$_SESSION['login_errmsg'] = "";
	authorizationListener();

	
?>	
<html>
<head>
	<?php
		echo getMainHeadContent();
		echo getStylesheet("index.css");
		echo setTitle("");
	?>
</head>
<body>
	<header>
		<?php
			echo getMainHeaderContent();
			
		?>
	</header>
	<aside>
		<?php
		
			echo getAuthorizationContent();
		?>
	</aside>
	<main>
		<?php
			$user = getAuthorizedUser();
	 		
			echo '<div id="newsfeed">'
					. getNewsFeed()
				. '</div>';
		
		?>
	</main>
	<footer>
		<?php			echo getMainFooterContent();		?>
	</footer>
</body>
</html>

<?php
/**
 * get newsfeed as html string ordered descending on created attribute
 */
function getNewsFeed(){
	$s = "";
	$arr = read::news(" ORDER BY news.created DESC ");

	foreach($arr as $news)
		$s .= '<article>' . newsfeedView($news) . '</article>';


	return $s;
}


?>



