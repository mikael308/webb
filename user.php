<!DOCTYPE html>
<?php
/**
 * defines page to view a users information\n
 * 
 * to define what user to view: set _GET['user'] to the user to view
 * 
 * @author Mikael Holmbom
 * @version 1.0
 */
 
 	require_once "./config/pageref.php";
  require_once "./database/database.php";
	require_once "./sections/main.php";
  require_once "./sections/messages.php";
	require_once "./sections/views.php";
  require_once "./session/authorization.php";
  require_once "./session/main.php";

	startSession();
	logoutListener(); 
	restrictedToAuthorized($GLOBALS["register_page"] );

	function getReq($index){
		if(isset($_GET[$index])){
			return $_GET[$index];
		}
		return NULL;
	}
	function getReqUser(){
		return getReq("u");
	}
	try{
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			$user = read::forumUser($_POST['u']);

			if($_POST["update_user"]){
				# SET NEW ATTRS
				$b = null;
				if(isset($_POST["update_user_banned"]))
					$b = $_POST["update_user_banned"] == "on" ? 1 : 0;
				else
					$b = 0;
				
				$user->setBanned($b);
				
				update::forumUser($user);
			}

			$_GET["u"] = $user->getPrimaryKey();

		}
		
		$view_user = read::forumUser(getReqUser());
	} catch(RuntimeException $e){
		echo "could not display user";
	}
?>

<html>
<head>
<?php	

	echo getMainHeadContent();
	if($view_user != NULL){
		echo setTitle($view_user->getName());
	} else {
		echo setTitle("could not find user");
	}
	echo getStylesheet("user.css");
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
		
			if($view_user != NULL){
				echo getUserInfo($view_user);
				$authorized_user = $_SESSION["authorized_user"];
				if($authorized_user->isAdmin()
					|| ($authorized_user->isModerator() 
						&& (!$view_user->isAdmin() || $view_user->isModerator()))
				){
					echo getAdminTools();
				}
			} else {
				echo errorMessage("user was not found");
			}

		?>
	</main>
	<footer>
		<?php echo getMainFooterContent(); ?>
	</footer>
</body>
</html>
<?php

function getAdminTools(){
	global $view_user;

	# the user settings is concerning
	$hiddenuser_row =
		"<input type='hidden' name='u' value='" . $view_user->getPrimaryKey() . "' />";

	$bannuser_row="";
	if(! $view_user->isAdmin()){
		# BANN USER setting
		$bannuser_tooltip = "user is currently: " 
			. ($view_user->isBanned()? "true" : "false");
		$bannuser_row = tr(
					td("<label>banned user</label>")
					.td(
						toolTip(switchButton("update_user_banned", 
						$view_user->isBanned()),
						$bannuser_tooltip)));
	}

	# SAVE BUTTON
	$submit = "<input type='submit' id='submit' class='icon_button material-icons' value='save' name='update_user'>";
	$submit_row = tr(
		td("")
		.td(toolTip($submit, "save changes")));

	return "<div id='admin_tools'>"
	. "<div class='header'>admin tools</div>"
	. 	"<form id='admintools' method='POST' action='".htmlspecialchars($_SERVER['PHP_SELF'])."'>"
	. 		"<table>"
	. 		$hiddenuser_row
	. 		$bannuser_row
	. 		$submit_row
	. 		"</table>"
	. 	"</form>"
	. "</div>";
}

?>
