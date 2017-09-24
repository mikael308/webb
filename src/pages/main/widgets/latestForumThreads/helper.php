<?php

namespace Web\Pages\Main\Widgets\LatestForumThreads;

/**
 * get a summary view of a threads latest post
 * @return string as html
 */
function threadsLatestPostView(
  \Web\Database\DAO\ForumThread $thread
){
  if($thread == NULL){
    return "";
  }

  $lastAuthor = $thread->getLastAttributor();
  #TODO få bort html i strängar
  return
    "<div class='latestThreadViewRef'>"
    . "<a href='".\Web\pagelinkForumThread(
          $thread->getPrimaryKey(), 
          1
        )."'>"
    .   "<div class='topic'>"
    .     \Web\Helper\Format::textToLength(
            $thread->getTopic(),
            20
          )
    .   "</div>"
    . "</a>"
     . "<a href='".\Web\pagelinkForumThread(
          $thread->getPrimaryKey(),
          $thread->getLastPageIndex()
        )."'>"
    .   "<div class='message'>"
    .     \Web\Helper\Format::textToLength(
            $thread->getLastPost()->getMessage(),
            40
          )
    .   "</div>"
    . "</a>"
    .   "<a href='".\Web\pagelinkUser($lastAuthor->getPrimaryKey())."'>"
    .     "<div class='author'>"
    .     \Web\Helper\Format::textToLength(
            $lastAuthor->getName(),
            20
          )
    .     "</div>"
    .   "</a>"
    . "</div>";
}
