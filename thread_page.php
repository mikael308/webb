<!DOCTYPE html>
<?php
/**
 * defines page to display forum thread\n
 * thread is defined by get['t']\n
 * page is defined by get['p']
 *
 * @author Mikael Holmbom
 * @version 1.0
 */
 
	# autoload classes
	spl_autoload_register(function($class) {
		require_once 'classes/' . $class . '.class.php';
	});
	
 	require_once "sections.php";
	require_once "database.php";
	require_once "listeners.php";
	require_once "display_format.php";
	require_once "settings.php";
	
	startSession();
	restrictedToAuthorized("registeruser_page.php");


	function getReq($index){
		if(isset($_GET[$index])){
			return $_GET[$index];
		}
		return NULL;
	}
	function getReqThread(){
		return getReq("t");
	}
	function getReqPage(){
		$p = getReq("p");
		if($p == NULL) $p = 1;
		return $p;
	}


?>
<html>
<head>
<?php	
	echo getMainHeadContent();
	echo '<link rel="stylesheet" href="./css/forum.css" >';
	$thread = readThread(getReqThread());
	if($thread != NULL){
		echo setTitle($thread->getTopic());	
	}
	
;?>	
</head>
<body>
	<header>
		<?php echo getMainHeaderContent(); ?>
	</header>
	<main>
		<?php

			$thread_id = getReqThread();
			if($thread_id != NULL){
				$thread = readThread($thread_id);
				if($thread != NULL){
					echo getBreadcrum($thread->getSubject(), $thread);
					echo '<div id="topic" class="header">' . $thread->getTopic() . '</div>';
					echo '<div id="forum_content">'
						. 	forumposts($thread)
						. '</div>';
				} else {
					thread_not_found();
				}
			} else {
				thread_not_found();
			}
			
			function thread_not_found(){
				echo '<div id="thread_not_found">'
				. 'thread was not found'
				.'</div>';
			}

		?>

	</main>
	<footer>
		<?php echo getMainFooterContent(); ?>
	</footer>
</body>
</html>
<?php


	function forumposts(ForumThread $thread){
		$cont = "";
		if ($thread != NULL){
			$n_postsPerPage = readSettings("posts_per_page");
			
			$start_offset = 1;
			$start_offset = (getReqPage() -1) * $n_postsPerPage;

			$cont .= 
				 '<article id="posts">'
					. '<div id="postlist">'
					. 	displayForumPosts($thread, $start_offset, $n_postsPerPage)
					. '</div>'
					. replyButton()
				. '</article>'
				. indexPages(2, 3);
		} else{
			#TODO could not display thread....
			$cont .= '<p>could not display thread</p>';
		}
		return $cont;
	}

	function indexPages3($a, $b){
		$c = "<div id='indexPages'>";
		for($i = 0; $i < 5; $i++){
			$c .= '<div class="link"><a href="#">' .($i+1). '</a></div>';
		}
		return $c . '</div>';
		
	}
	
	/**
	 * show only the relevant index pages as:
	 * startpages ... current page .. last pages
	 * @param side_range amount of pageLinks from start and end
	 * @param current_range defines as the range of one side of req_page \nexample closest_range = 3, req_page = 6 gives:\n3 4 5 [6] 7 8 9
	 */
	function indexPages($side_range, $current_range){
		function pageLink($pageIdx, $label){
			$link = '<div class="link">'
				. 	'<a class="pageLink" href="thread_page.php?t='.getReqThread().'&p='. $pageIdx .'">'.$label.'</a>'
				. '</div>'; 
			if($pageIdx == getReqPage()){
				$link = #'<div id="current_pagelink">'
					#. $link
					#. '</div>';
					$link;
			} else {
				#$link = '<div class="pagelink">' . $link . '</div>';
			}
			return $link;
		}
		
		$dismissed_index_sign = '<div class="dismissed_index">...</div>';
		$dismissed_index_sign = '...';
		
		$n_postsPerPage = readSettings("posts_per_page");

		$thread = getReqThread();
		if($thread != NULL){
			$n_posts = count(readPostsFromThread($thread));
			$n_tot_pages = ceil($n_posts / $n_postsPerPage); 
			$req_page = getReqPage();
		} else {
			return;
		}
		
		####### NAVIGATION ARROWS
		###### LEFT
		$left_nav = "";
		if($req_page > 1){
			$left_nav .= ""#"<div class='index_navigation'>"
			 	. pageLink(1, "|<") . " "
			 	. pageLink(($req_page -1), "<") . " "
			 ;#. "</div>";
		}

		###### RIGHT
		$right_nav = "";
		if($req_page < $n_tot_pages){
			$right_nav .= "<div class='index_navigation'>"
		 		. pageLink(($req_page +1), ">") . " "
				. pageLink($n_tot_pages, ">|") . " "
			. "</div>";
		}

		# the index, this will echo all the indexes to page
		$i = 1;
		
		$mid_nav = "";
		###### START INDEX
		for(; $i <= $side_range; $i++){
			if($i > $n_tot_pages){
				break;
			}
			$mid_nav .= pageLink($i, $i) . " "; 
		}
		# start of mid indexes 
		$current_std_start = $req_page - $current_range;
		if($current_std_start > $i){
			$mid_nav .= $dismissed_index_sign;				
		}
		###### REQUEST INDEX (MID)
		$i = max(array(
			$i,
			$current_std_start));
		for(; $i <= ($req_page + $current_range); $i++){
			if($i > $n_tot_pages){
				break;
			}
			$mid_nav .= pageLink($i, $i) . " "; 	
		}

		###### END INDEX
		# end of mid indexes
		$end_std_start = $n_tot_pages - $side_range+1;
		if($i < $end_std_start){
			$mid_nav .= $dismissed_index_sign;				
		}
		$i = max(array(
			$i, 
			$end_std_start));

		for(; $i <= $n_tot_pages; $i++){
			if($i > $n_tot_pages){
				break;
			}
			$mid_nav .= pageLink($i, $i) . " "; 
		}
		
		return '<div id="indexPages"><ul>'
				. $left_nav
				. $mid_nav
				. $right_nav
			. '</ul></div>';
	}

	function replyButton(){
		return 
			'<a href="post_page.php?t='.getReqThread().'">'
				.'<Button>reply</Button>'
			.'</a>';
		
	}



?>
