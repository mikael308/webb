<!DOCTYPE html>
<?php
/**
 * Page displaying information about a user.\n
 * Restricted to authorized users
 * 
 * @author Mikael Holmbom
 * @version 1.0
 */

	# autoload classes
	spl_autoload_register(function($class) {
		include 'classes/' . $class . '.class.php';
	});
	
	require_once "sections.php";
	require_once "database.php";
	require_once "listeners.php";
	require_once "display_format.php";
	
	startSession();
	logoutListener(); 
	restrictedToAuthorized("registeruser_page.php");
	

?>

<html>
<head>
<?php	
	echo getMainHeadContent();
	echo setTitle("my page")
?>	
</head>
<body>
	<header>
		<?php echo getMainHeaderContent(); ?>
	</header>
	<main>
		<?php
		
			$user = $_SESSION['authorized_user'];
			
			if(isset($_POST['deleteuser'])){
				if(deleteForumUser($user)){
					$_SESSION['authorized_user'] = NULL;
					header("Location: index.php");
				}
				
				
			}
			echo getUserInfo($user);
			echo getRemoveUser();
			
		?>

	</main>
	<footer>
		<?php echo getMainFooterContent(); ?>
	</footer>
</body>
</html>
<?php
/**
 * get form used to remove user
 */
function getRemoveUser(){
	return
		'<form method="post" >'
			. '<input type="submit" class="button" name="deleteuser" value="delete user">'
		. '</form>';
}



?>
