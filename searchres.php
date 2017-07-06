<?php
  /**
   * use request 'id' for searchstring \n
   * echos list of links to users that match with searchstring\n
   * max amount of results displayed set in settings.md as var searchres_user_amount
   * @author Mikael Holmbom
   * @version 1.0
   */

  require_once "./config/pageref.php";
  require_once "./config/settings.php";
  require_once "./database/database.php";
  require_once "./sections/views.php";
  require_once "./sections/forum/main.php";
  
  /**
   *
   * search result for ForumPost type
   * @param post the search result
   * @return searchresult as html
   */
  function searchresPost(ForumPost $post){
    $maxlen = 40;
    $label = strlen($post->getMessage()) > $maxlen ? 
      substr($post->getMessage(), 0, $maxlen) . ' ... ' :
      $post->getMessage();
    $p = Count::postPageIndex($post->getPrimaryKey());
    $t = $post->getThread();
    $author = $post->getAuthor();
    return
      "<a href='". getDisplayThreadLink($t, $p) ."'>"
      .   "<div class='post main'>"
      .     $label
      .   "</div>"
      . "</a>"
      . "<div class='post extra'>"
      .   "<div class='author'>"
      .     "author: <a href='". getDisplayUserLink($author->getPrimaryKey()) ."'>"
      .       $author->getName() . "</a>"
      .   "</div>"
      .   "<div class='topic' >"
      .     "topic: <a href='".getDisplayThreadLink($t, 1)."'>"
      .       $t->getTopic() ."</a>"
      .   "<div>"
      . "</div>";
	}
  /**
   *
   * search result for ForumUser type
   * @param user 
   * @return rearsch result as html
   */
  function searchresForumUser(ForumUser $user){
    $cont = "<div class='name'>". $user->getName() ."</div>"
      . "<div class='role'>". $user->getRole() . "</div>";
    return
        "<a href='" . pagelinkUser($user->getPrimaryKey()) . "' class='user main'>"
      . "<div class='user extra'>"
      .   $cont
      . "</div>"
      . "</a>"
      ;
  }
  /**
   * get data as searchres listitem
   * @param data listitem data
   * @return li as html
   */
  function search_li($data){
    return "<li class='searchres_item'>". $data ."</li>";
  }
  $searchstr = $_REQUEST["id"];
  $searchType = $_REQUEST["type"];
  $nothingfoundres = "<li class='searchres_item'>no results</li>";
  $suggest = "";
  if ($searchstr != ""){
    $reslist = "";
    $suggest = "";
    # determine the type of the search
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
    "<ul id='searchres_list'>"
    .   $suggest
    ."</ul>";
