<?php
/**
 * use request 'id' for searchstring \n
 * echos list of links to users that match with searchstring\n
 * max amount of results displayed set in settings.md as var searchres_user_amount
 * @author Mikael Holmbom
 * @version 1.0
 */
	
	require_once "pageref.php";
	require_once "database.php";
	require_once "settings.php";
	require_once "display_format.php";

	/**
	 *
	 *
	 */
	function searchresPost(ForumPost $post){

		$maxlen = 40;
		$label = strlen($post->getMessage()) > $maxlen ? 
			substr($post->getMessage(), 0, $maxlen) . ' ... ' :
			$post->getMessage();

		$p =  count::postPageIndex($post->getPrimaryKey());
		$t = $post->getThread();

		$author = $post->getAuthor();

		return

			  '<a href="'. getDisplayThreadLink($t,$p) .'"><div class="post main">'. $label .'</div>'
			. '</a>'
			. '<div class="post extra">'
			.	'<div class="author">author: <a href="'. getDisplayUserLink($author->getPrimaryKey()) .'">'. $author->getName() .'</a></div>'
			.	'<div class="topic" >topic: <a href="'.getDisplayThreadLink($t,1).'">' . $t->getTopic() .'</a><div>'
			. '</div>'
			;
	}
	/**
	 *
	 *
	 */
	function searchresForumUser(ForumUser $user){
		$cont = '<div class="name">'. $user->getName() .'</div>'
			. '<div class="role">'. $user->getRole() . '</div>';

		return
		 	  '<a href="'.$GLOBALS['user_page'] .'?u=' . $user->getPrimaryKey() . '" class="user main">'
		 	. 	'<div class="user extra">'
			.		$cont
			. 	'</div>'
			. '</a>'
			;
	}

	function search_li($data){
		return '<li class="searchres_item">'. $data .'</li>';
	}


	$searchstr = $_REQUEST['id'];
	$searchType = $_REQUEST['type'];

	$nothingfoundres = '<li class="searchres_item">no results</li>';
	
	$suggest = "";
	if ($searchstr != ""){
		$reslist = "";
		$suggest = "";

		switch($searchType){
			case "post":
				$reslist = searchPost($searchstr);
				if($reslist == NULL){
					 $suggest = $nothingfoundres;
				} else {
					foreach($reslist as $item){
						$suggest .= search_li(searchresPost($item));
					}
				}
				break;
			case "user":
				$reslist = searchForumUser($searchstr);
				if($reslist == NULL){
					 $suggest = $nothingfoundres;
				} else {
					foreach($reslist as $item){
						$suggest .= search_li(searchresForumUser($item));	
					}
				}
			break;
		}
	} 
	
	echo $suggest == "" ? "" : 
		  '<ul id="searchres_list">'
		.	 $suggest
		 .'</ul>';



?>
