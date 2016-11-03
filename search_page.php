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
	require_once "settings.php";
	require_once "database.php";
	require_once "listeners.php";
	
	startSession();
	logoutListener(); 
	restrictedToAuthorized("registeruser_page.php");
	

?>

<html>
<head>
<?php	
	echo getMainHeadContent();
	echo getScript("search_user_hint.js");
	echo setTitle("search");

?>	

</head>
<body>
	<header>
		<?php 
		echo getMainHeaderContent(); 
		echo getStylesheet("search_page.css")
		?>

	</header>
	<main>
		<?php
			$user = $_SESSION['authorized_user'];
						
			echo getSearchForm();
			
			echo getSearchResult();			
		?>

	</main>
	<footer>
		<?php echo getMainFooterContent(); ?>
	</footer>
</body>
</html>
<?php

/**
 * handle search request
 */
function getSearchResult(){
	if(isset($_GET['search_type'])){
		$sval = $_GET['search_value'];
		if($_GET['search_type'] == 'user'){
			
			$res_users = searchForumUser($sval);
			
			if ($res_users == NULL){
				echo "found 0 result";
				
			} else {
				echo displaySearchResForumUsers($res_users);	
			}
			
		} elseif($_GET['search_type'] == 'post') {

			$res_posts = searchPost($sval);
			if ($res_posts == NULL){
				echo "found 0 result";
				
			} else {
				echo displaySearchResForumPosts($res_posts);
				
			}
			
		}
	}

}
/**
 * display searchresult of forumposts
 */
function displaySearchResForumPosts($forumposts){
	$res = "<div id='searchres_posts' class='searchres'>";
	foreach($forumposts as $post){
		$searchres_maxlength = readSettings("searchres_posts_maxlength");
		
		$msg = $post->getMessage();
		if(strlen($msg) > $searchres_maxlength){
			$msg = substr($msg, 0, $searchres_maxlength) . " ... ";
		}
		
		$res .= 
			"<a href='forum_page.php?t=".$post->getThread()."&p=1'>"
			. 	"<div class='searchres_post searchres_item'>"
			. 		$msg." : "."<a href='viewuser_page.php?u=".$post->getAuthor()->getPrimaryKey()."'>".$post->getAuthor()->getName() ."</a>"
			. 	"</div>"
			. "</a>";
	}
	$res .= "</div>";
	return $res;
}
/**
 * display searchresult of forumusers
 */
function displaySearchResForumUsers($forumusers){
	$res = "<div id='searchres_forumusers' class='searchres'>";
	foreach($forumusers as $user){
	 	$res .=
	 		"<a href='viewuser_page.php?u=".$user->getName()."'> " 
	 		. 	"<div class='searchres_user searchres_item'>" 
	 		. 		$user->getName()
	 		. 	"</div>"
			. "</a> ";	
	}
	$res .= '</div>';
	return $res;
}
/**
 * get form for client searching in database
 *
 */
function getSearchForm(){
	echo
		"<form method='GET' id='searchform'>"
			. "<label for='post'>post</label>"
			. "<input type='radio' value='post' name='search_type' checked >"
			. "<label for='user'>user</label>"
			. "<input type='radio' value='user' name='search_type' ><br>"
			. "<input type='text' name='search_value' onkeyup='searchUser(this.value)' autocomplete='off' autofocus >"
			. "<input type='submit' value='search'>"
		. "</form>"
		. "<div id='suggestionlist'></span></div>";
}

?>

