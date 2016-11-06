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
 
	require_once "sections.php";
	require_once "database.php";
	require_once "display_format.php";	
	
	startSession();
	logoutListener(); 
	restrictedToAuthorized("registeruser_page.php");

	function getReq($index){
		if(isset($_GET[$index])){
			return $_GET[$index];
		}
		return NULL;
	}
	function getReqUser(){
		return getReq("u");
	}
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$user = read::forumUser($_POST['u']);
		
		if($_POST['update_user']){
			# SET NEW ATTRS
			$b = null;
			if(isset($_POST['update_user_banned']))
				$b = $_POST['update_user_banned'] == "on" ? 1 : 0;
			else
				$b = 0;
			
			$user->setBanned($b);
			
			update::forumUser($user);
		}
		
		$_GET['u'] = $user->getPrimaryKey();

	}
	
	$view_user = read::forumUser(getReqUser());

?>

<html>
<head>
<?php	

	echo getMainHeadContent();
	echo setTitle($view_user->getName());
	echo getStylesheet("viewuser.css");
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
				$authorized_user = $_SESSION['authorized_user'];
				if($authorized_user->isAdmin()
					|| ($authorized_user->isModerator() 
						&& (!$view_user->isAdmin() || $view_user->isModerator())
						)
				){
					echo getAdminTools();
				}
			} else {
				echo '<p>user was not found</p>';
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
	$cont = "<div class='header'>admin tools</div>";
	
	# POST FORM
	$cont .= '<form id="admintools" method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">'
			. 	'<input type="hidden" name="u" value="' . $view_user->getPrimaryKey() . '" />';
	
	# BANN USER
	$banned_checkedval = $view_user->isBanned() ? "checked" : "unchecked";
	$cont .=
			"<table>" 
			. tr(
				td("<label>banned user</label>")
				. td("<div class='tooltip'>" 
				. "<span class='tooltiptext'>user is currently: " . ($view_user->isBanned()?"true":"false") . "</span>"
				. 	"<label class='switch'>"
  					. "<input type='checkbox' ".$banned_checkedval." name='update_user_banned'>"
  					. "<div class='slider'></div>"
				. 	"</label>") 
				. "</div>")
			;
	
	# SAVE BUTTON
	$submit = '<input type="submit" id="submit" class="icon_button material-icons" value="save" name="update_user">';

	$cont .= tr(td("").td(getTooltip($submit, "save changes")));
	$cont .= "</table>";
	$cont .= '</form>';
	
	return '<div id="admin_tools">' . $cont . '</div>';
}

?>
