	<?php

	require_once "Page.php";
	require_once "./database/database.php";
	require_once "./sections/main.php";
	require_once "./session/authorization.php";
	require_once "./session/main.php";

	autoloadDAO();
	startSession();


	$page = new Page();

	# HEAD
	##########################
	$page->setHead(
		getStylesheet("widgets.css")
		. setTitle("contact")
	);
	# HEADER
	##########################
	$page->setHeader(

	);
	# MAIN
	##########################
	
	$page->setMain(

	);
	# FOOTER
	##########################
	$page->setFooter(

	);

	echo $page->toHtml();



	#####################################
	# page functions
	#####################################

?>
