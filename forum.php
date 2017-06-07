<?php
	/**
	* @author Mikael Holmbom
	* @version 1.0
	*/

	require_once "Page.php";
	require_once "./config/pageref.php";
	require_once "./config/settings.php";
	require_once "./database/database.php";
	require_once "./sections/main.php";
	require_once "./sections/views.php";
	require_once "./sections/dateformat.php";
	require_once "./sections/forum/information.php";
	require_once "./sections/forum/main.php";
	require_once "./sections/forum/overview.php";
	require_once "./sections/forum/subject.php";
	require_once "./sections/forum/thread.php";
	require_once "./session/authorization.php";
	require_once "./session/requests.php";


	logoutListener();
	restrictedToAuthorized($GLOBALS["register_page"] );
	$page = new Page();

<<<<<<< HEAD

	# HEAD
	##########################
	$page->setHead(
		getStylesheet("forum.css")
		.getStylesheet("information.css")
		# read from request and set topic name as this title
		.readTitle()
	);
	# HEADER
	##########################
	$page->setHeader(
		""
	);

	# MAIN
	##########################
	$mainContent ="";
	# preset index settings
	# if no index is set in request: show main
	$index = "main";
	$index_val = "";

	# read thread/subject index
	if (isset($_GET["t"])){
		$index = "thread";
		$index_val = get_index("t");
	} elseif (isset($_GET["s"])){
		$index = "subject";
		$index_val = get_index("s");
	}
	# display the forum
	$mainContent .=
		forum(
			$index,
			$index_val,
			get_index("p")
		);
	switch($index){
		case "main":
		$mainContent .=
			"<aside>"
			. 	displayLatestThreads()
			. "</aside>";

		break;
	}
	$page->setMain($mainContent);

	echo $page->toHtml();




	######################################
	# page functions
	#####################################

	function readTitle(){
		$title = "forum";
		$topic = NULL;
		try{
			if(get_index("s") != NULL){
				$topic = Read::subjects(get_index("s"))[0];
			} elseif (get_index("t") != NULL){
				$topic = Read::thread(get_index("t"));
			}
		} catch(RuntimeException $e){

		}

		if($topic != NULL){
				$title .= ":" . $topic->getTopic();
		}
		return setTitle($title);
	}

 ?>
