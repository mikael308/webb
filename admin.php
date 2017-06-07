<?php
	/**
	 *	admin page
	 *
	 *  can only be accessed if $_SESSION['authorized_user'] is set
	 *  and role is "admin"
	 *
	 *  @author Mikael Holmbom
	 *  @version 1.0
	 */

	require_once "Page.php";
	require_once "./config/pageref.php";
	require_once "./database/database.php";
	require_once "./sections/main.php";
	require_once "./session/authorization.php";
	require_once "./session/main.php";


	autoloadDAO();
	startSession();
	restrictedToAdmin($GLOBALS['index_page']);

	$page = new Page();

	# HEAD
	##########################
	$page->setHead(
		getStylesheet("admin.css")
		. setTitle("admin")
	);
	# HEADER
	##########################
	$page->setHeader(
		
	);
	# MAIN
	##########################
	$page->setMain(
		"<h2>admin page</h2>"
		. adminMenu()
	);
	# FOOTER
	##########################
	$page->setFooter(

	);

	echo $page->toHtml();




	######################################
	# page functions
	#####################################

	/**
	 * display admin menu\n
	 * list of admin tools
	 */
	function adminMenu(){
		return "<ul id='adminmenu_list'>"
			. 	listitem(
						"<a class='button' href='".$GLOBALS["post_page"]
							."?op=news'>post news</a>"
					)
			. "</ul>";
	}

?>
