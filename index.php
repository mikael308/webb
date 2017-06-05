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

	require_once "./database/database.php";
	require_once "./sections/main.php";
	require_once "./sections/views.php";
	require_once "./sections/forum/information.php";
	require_once "./session/authorization.php";
	require_once "./session/main.php";

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
		echo getStylesheet("information.css");
		echo setTitle("");
	?>
</head>
<body>
	<header>
		<?php
			echo getMainHeaderContent();

		?>
	</header>
		<main>
			<?php
				echo newsFeed();

			?>
			<aside>
				<?php
					echo getAuthorizationContent();
					echo displayLatestThreads();
				?>
			</aside>
		</main>

	<footer>
		<?php
			echo getMainFooterContent();
		?>
	</footer>
</body>
</html>

<?php
/**
 * @return newsfeed as html string
 * ordered descending on created attribute
 */
function newsFeed(){
	$s = "";
	$arr = Read::news(" ORDER BY news.created DESC ");

	foreach($arr as $news){
		$s .=
			'<article>'
			. 	newsfeedView($news)
			. '</article>';
	}

	return
		'<div id="newsfeed">'
		. $s
		. '</div>';

}

?>
