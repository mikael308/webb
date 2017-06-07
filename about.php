<?php
	/**
	 *	about page
	 *
	 *
	 *	@author Mikael Holmbom
	 *	@version 1.0
	 */

	require_once "Page.php";
	require_once "./database/database.php";
	require_once "./session/authorization.php";
	require_once "./session/main.php";


	$page = new Page();

	# HEAD
	##########################
	$page->setHead(
		getStylesheet("widgets.css")
		. getStylesheet("about.css")
		. setTitle("about")
	);

	# HEADER
	##########################
	$page->setHeader(
		""
	);

	# MAIN
	##########################
	$mainContent = "<p>about page</p>";

	if(isset($_GET["d"])){
		switch($_GET["d"]){
			case "faq":
				$mainContent .= faqContent();
			break;
			case "about":
				$mainContent .= aboutContent();
			break;
		}
	} else { # the default page content
		$mainContent .= aboutContent();
	}

	$page->setMain(
		$mainContent
	);

	# FOOTER
	##########################
	$page->setFooter(
		getScript("accordion.js")
	);

	echo $page->toHtml();




	######################################
	# page functions
	#####################################

	function aboutContent(){
		return "<p>this explains the reason for this page</p>";
	}

	function faqContent(){
		$xml = simplexml_load_file("res/faq.xml") or die("could not load faq");

		$cont = "<div id='faq'>";
		foreach($xml->children() as $x){
			$cont .= "<button class='accordion'>".$x->question."</button>"
			. "<div class='panel'>"
			. 	"<p>" . $x->answer . "</p>"
			. "</div>";
		}
		$cont .= "</div>";
		return $cont;
	}


 ?>
