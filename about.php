<!DOCTYPE html>
<?php
/**
 *	admin page
 *
 *	can only be accessed if $_SESSION['authorized_user'] is set
 *  and role is "admin"
 *
 *	@author Mikael Holmbom
 */

	require_once "./database/database.php";
	require_once "./sections/main.php";
	require_once "./session/authorization.php";
	require_once "./session/main.php";

	autoloadDAO();
	startSession();


?>
<html>
<head>
	<?php 
		echo getMainHeadContent();
		echo getStyleSheet("widgets.css");
		echo getStyleSheet("about.css");
		echo setTitle("about");
	?>
</head>
<body>
	<header>
		<?php echo getMainHeaderContent(); ?>
	</header>
	<main>
		<?php
			echo '<p>about page</p>';
			
			if(isset($_GET['d'])){
				switch($_GET['d']){
					case "faq":
						echo faqContent();
						break;
					case "about":
						echo aboutContent();
						break;
				}
			} else { # the default page content
				echo aboutContent();
			}
			
		
		?>
	</main>
	<footer>
		<?php 
			echo getScript("accordion.js");
			echo getMainFooterContent(); 
		?>
	</footer>
</body>
</html>
<?php

	function aboutContent(){
		return '<p>this explains the reason for this page</p>';
	}
	
	function faqContent(){
		$xml = simplexml_load_file("res/faq.xml") or die("could not load faq");
		
		$cont = '<div id="faq">';		
		foreach($xml->children() as $x){
			$cont .= '<button class="accordion">'.$x->question.'</button>'
				. '<div class="panel">'
				. 	'<p>' . $x->answer . '</p>'
				. '</div>';
		}
		$cont .= '</div>';
		return $cont;
	}

?>
