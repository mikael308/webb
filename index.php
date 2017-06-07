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

	require_once "Page.php";
	require_once "./database/database.php";
	require_once "./sections/main.php";
	require_once "./sections/views.php";
	require_once "./sections/forum/information.php";
	require_once "./session/authorization.php";
	require_once "./session/main.php";

	startSession();

	# listen for login submit
	$_SESSION["login_errmsg"] = "";
	authorizationListener();


	$page = new Page();

	# HEAD
	##########################
	$page->setHead(
		getStylesheet("index.css")
		.	getStylesheet("information.css")
		.	setTitle("")
	);
	# HEADER
	##########################
	$page->setHeader(
		getLogoutForm()
	);
	# MAIN
	##########################
	$page->setMain(
		newsFeed()
		.	"<aside>"
		.		getAuthorizationContent()
		.		displayLatestThreads()
		.	"</aside>"
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
	 * @return newsfeed as html string
	 * ordered descending on created attribute
	 */
	function newsFeed(){
		$s = "";
		$arr = Read::news(" ORDER BY news.created DESC ");

		foreach($arr as $news){
			$s .=
				"<article>"
				. 	newsfeedView($news)
				. "</article>";
		}

		return
			"<div id='newsfeed'>"
			. $s
			. "</div>";

	}

?>
