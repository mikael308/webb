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
		$bann_user_id = $_POST['bann_user_arg'];
		$bann_user = readForumUser($bann_user_id);
			
		if(isset($_POST['bann_user'])){
			updateBanned($bann_user, "TRUE");
			
		} elseif(isset($_POST['unbann_user'])){
			updateBanned($bann_user, "FALSE");			
		}
		$_GET['u'] = $bann_user_id;
	}
	
	$view_user = readForumUser(getReqUser());

?>

<html>
<head>
<?php	

	echo getMainHeadContent();
	
;?>	
</head>
<body>
	<header>
		<?php echo getMainHeaderContent(); ?>

	</header>
	<main>
		<?php
		
			if($view_user != NULL){
				echo getUserInfo($view_user);
				$authorized_user = $_SESSION['authorized_user'];
				if($authorized_user->isAdmin() || $authorized_user->isModerator()){
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
	$cont .= "banned: " 
		. $view_user->isBanned() ? "true" : "false";
	
	$cont .= '<form id="bannuserform" method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">'
			. 	'<input type="hidden" name="bann_user_arg" value="' . $view_user->getPrimaryKey() . '" />';
	if($view_user->isBanned()){
		$cont .= '<input type="submit" class="button" value="unbann user"	name="unbann_user">';
	} else {
		$cont .= '<input type="submit" class="button" value="bann user"	name="bann_user">';
	}
	$cont .= '</form>';
	
	return $cont;
}

?>
