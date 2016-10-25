<?php
/**
 * use request 'id' for searchstring \n
 * echos list of links to users that match with searchstring\n
 * max amount of results displayed set in settings.md as var searchres_user_amount
 * @author Mikael Holmbom
 * @version 1.0
 */
	require_once "database.php";
	require_once "settings.php";

	$searchstr = $_REQUEST['id'];
	$display_n = readSettings("searchres_users_amount");
	$suggest = "";
	
	if ($searchstr != ""){
		$userlist = searchForumUser($searchstr);
		$suggest = "";
		$max = min($display_n, sizeof($userlist));
		
		for($i = 0; $i < $max; $i++){
			$user = $userlist[$i];
			$s = 
				"<a href='viewuser_page.php?u=".$user->getName()."'> "
				. 	"<div class='suggestionlist_searchres_user'>"
	 			.  		$user->getName()
	 			. 	"</div>"
				. "</a> ";	
	 		
			$suggest .= $s;
		}
		
	} 
	
	echo $suggest == "" ? "no suggestions" : $suggest;
	

?>
