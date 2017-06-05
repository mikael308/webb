<?php
  /**
   * generate breadcrum links to current subject and thread
   * @param subject the current subject
   * @param thread the current thread
   * @return as html
   */
  function getBreadcrum(ForumSubject $subject = NULL, ForumThread $thread = NULL){
    function getBreadcrumLink($link, $title){
      return
        "<div class='parallellogram'>"
          . "<a class='clickable breadcrum_link' href='".$link."'>".$title."</a>"
        ."</div>";
    }

    $cont = getBreadcrumLink($GLOBALS["forum_page"], "main");
    if($subject != NULL){
      $cont .= getBreadcrumLink($GLOBALS["forum_page"] ."?s=".$subject->getPrimaryKey()."&p=1", $subject->getTopic());
    }
    if($thread != NULL){
      $cont .= getBreadcrumLink($GLOBALS["forum_page"] ."?t=".$thread->getPrimaryKey()."&p=1", $thread->getTopic());
    }

    return
      "<div id='breadcrum'>"
      . 	$cont
      . "</div>";
  }


 ?>
