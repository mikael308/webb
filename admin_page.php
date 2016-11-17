<!DOCTYPE html>
<?php
/**
	admin page

	can only be accessed if $_SESSION['authorized_user'] is set and role is "admin"
	
	@author Mikael Holmbom
*/

	# autoload classes
	spl_autoload_register(function($class) {
		include 'classes/' . $class . '.class.php';
	});
	
	require_once "pageref.php";
	require_once "sections.php";
	require_once "listeners.php";
	
	startSession();
	restrictedToAdmin($GLOBALS['index_page']);
	


?>
<html>
<head>
	<?php 
		echo getMainHeadContent();
		setTitle("admin");
	?>
</head>
<body>
	<header>
		<?php echo getMainHeaderContent(); ?>
	</header>
	<main>
		<?php
			echo '<p>admin page</p>';
		
		?>
	</main>
	<footer>
		<?php echo getMainFooterContent(); ?>
	</footer>
</body>
</html>

