<?php

#TODO används ens denna ???
namespace Widgets\latestForumThreads;

/**
 * get a summary view of a threads latest post
 * @return as html
 */
function threadsLatestPostView(
  ForumThread $thread
){
  if($thread == NULL){
    return "";
  }

  $lastAuthor = $thread->getLastAttributor();
  #TODO få bort html i strängar
  return
    "<div class='latestThreadViewRef'>"
    . "<a href='".pagelinkForumThread(
          $thread->getPrimaryKey(), 
          1
        )."'>"
    .   "<div class='topic'>"
    .     textToLength(
            $thread->getTopic(),
            20
          )
    .   "</div>"
    . "</a>"
     . "<a href='".pagelinkForumThread(
          $thread->getPrimaryKey(),
          $thread->getLastPageIndex()
        )."'>"
    .   "<div class='message'>"
    .     textToLength(
            $thread->getLastPost()->getMessage(),
            40
          )
    .   "</div>"
    . "</a>"
    .   "<a href='".pagelinkUser($lastAuthor->getPrimaryKey())."'>"
    .     "<div class='author'>"
    .     textToLength(
            $lastAuthor->getName(),
            20
          )
    .     "</div>"
    .   "</a>"
    . "</div>";
}
