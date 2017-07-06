<?php
  /**
   * generate breadcrum links to current subject and thread
   * @param subject the current subject
   * @param thread the current thread
   * @return as html
   */

  require_once "./config/pageref.php";

  function getBreadcrum(ForumSubject $subject = NULL, ForumThread $thread = NULL){
    function getBreadcrumLink($link, $title){
      return
        "<div class='parallellogram'>"
          . "<a class='clickable breadcrum_link' href='".$link."'>".$title."</a>"
        ."</div>";
    }

    # FORUM MAIN
    $cont = getBreadcrumLink(
      $GLOBALS["pagelink"]["forum"],
      "main"
    );
    # FORUM SUBJECT
    if($subject != NULL){
      $cont .= getBreadcrumLink(
        pagelinkForumSubject($subject->getPrimaryKey()),
        $subject->getTopic()
      );
    }
    # FORUM THREAD
    if($thread != NULL){
      $cont .= getBreadcrumLink(
        pagelinkForumThread(
          $thread->getSubjectFK(),
          $thread->getPrimaryKey()
        ),
        $thread->getTopic()
      );
    }

    return
      "<div id='breadcrum'>"
      . 	$cont
      . "</div>";
  }


 ?>
