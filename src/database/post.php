<?php

namespace Web\Database;

/**
 * facade functions used for working with database
 * @author Mikael Holmbom
 * @version 1.0
 */

require_once PATH_ROOT_ABS."database/database.php";
require_once PATH_ROOT_ABS."database/Read.php";
require_once PATH_ROOT_ABS."database/Delete.php";
require_once PATH_ROOT_ABS."database/Update.php";
require_once PATH_ROOT_ABS."database/Persist.php";
require_once PATH_ROOT_ABS."session/main.phtml";
require_once PATH_ROOT_ABS."helper/security.php";

use \Web\Database\DAO\ForumPost;
use \Web\Database\DAO\ForumThread;
use \Web\Database\DAO\News;
use \Web\Session\Security\Authorizer;

autoloadDAO();

/**
 * get form to create forumthread as html string\n
 * get the last page if requested index is over thread bound
 * or param pageidx is null
 * @param thread ForumThread concerned thread
 * @param pageIdx int requested page index
 * @return string forum page with requested pageindex link as string
 */
function getThreadPageLink(
    ForumThread $thread,
    $pageIdx = null
) {
    $lastPage = $thread->getLastPageIndex();

    if ($pageIdx == null || $pageIdx > $lastPage)
        $pageIdx = $lastPage;

    return \Web\pagelinkForumThread(
        $thread->getId(),
        $pageIdx
    );
}

/**
* create a thread with POST arguments
*/
function createThread()
{
    $topic = $_POST['forumthread_topic'];
    $msg = htmlentities($_POST['forumpost_message']);
    if (getSubjectIndex() == null) {
        #TODO inte returnera html sträng?? returnera exeption
        echo \Web\Helper\Message::error('could not read subject');
    }

    $subj = Read::subject(getSubjectIndex());
    $_SESSION['subject'] = null;

    #TODO validate topic and msg

    $user = Authorizer::getAuthorizedUser();
    $thread = new ForumThread($topic);
    $thread->setSubjectFK($subj->getPrimaryKey());
    # now
    $timestamp = date($GLOBALS['timestamp_format']); 

    $post = new ForumPost();
    $post->setAuthorFK($user->getPrimaryKey());
    $post->setMessage($msg);
    $post->setCreated($timestamp);

    # database
    $thread = Persist::forumThread($thread);
    $post->setThreadFK($thread->getPrimaryKey());
    Persist::forumPost($post);

    # redirect to thread page
    $pagelink = getThreadPageLink($thread, 1);
    header("Location: $pagelink");
    exit();
}
/**
* post a reply with data from POST\n
* redirects to forums last page
*/
function postReply()
{
    $msg = htmlentities($_REQUEST['forumpost_message']);
    
    $post = new ForumPost();
    $post->setThreadFK(getThreadIndex());
    $post->setAuthor(Authorizer::getAuthorizedUser());
    $post->setMessage($msg);

    Persist::forumPost($post);

    # redirect to thread page
    $pagelink = getThreadPageLink($post->getThread());
    header("Location: $pagelink");
    exit();
}
/**
 * edit post of POST['post'] with message of POST['forumpost_message']\n
 * redirects to forums page of edited post
 */
function editPost()
{
    $newmsg = htmlentities($_REQUEST['forumpost_message']);
    $post = Read::forumPost($_REQUEST['post']);
    $post->setMessage($newmsg);

    Update::forumPost($post);

    $pageIndex = isset($_REQUEST['page_index']) 
        ? $_REQUEST['page_index'] 
        : 1; 
    $pagelink = getThreadPageLink($post->getThread(), $pageIndex);
    header("Location: $pagelink");
    exit();
    
}
/**
 * delete post from database
 * reads header requests:
 *  post - the post to delete
 */
function deletePost() 
{
    $post = \Database\Read::forumPost($_REQUEST['post']);
    $thread = $post->getThread();
    if (Delete::forumPost($post)) {
        # redirect to thread page
        $pageIndex = $_REQUEST['page_index'];
        $pagelink = getThreadPageLink($thread, $pageIndex);
        header("Location: $pagelink");
        exit(); 
    }       
}
/**
 * create news and persist to database from post indexes\n
 * on successful creation redirects to index page,
 * else error message is shown
 */
function createNews()
{
    $title = $_POST['news_title'];
    #$msg = cleanupMessage($_POST['news_message']);
    $msg = $_REQUEST['news_message'];
    
    $news = new News();
    
    $news->setAuthorPK(Authorizer::getAuthorizedUser()->getPrimaryKey());
    $news->setTitle($title);
    $news->setMessage($msg);

    if (Persist::news($news)) {
        $pagelink = $GLOBALS['pagelink']['index'];
        header("Location: $pagelink");
        exit();
    } else {
        echo \Web\Helper\Message::error("could not create news");
    }

}

/**
 * determine if current authorized user can edit param post
 * @return True if post can be edited by current authorized user
 */
function editable(ForumPost $post)
{
    if ($post == null){
        return False;
    }

    $authUser = Authorizer::getAuthorizedUser();
    if ($authUser->isAdmin() || $authUser->isModerator()) {
        return True;
    }

    # if user is author of message
    if ($authUser->getPrimaryKey() == $post->getAuthor()->getPrimaryKey()) {
        $a = date($GLOBALS['timestamp_format']);
        $b = $post->getCreated();

        $diff = strtotime($a) - strtotime($b);
        # if message was created within past 15min
        if ($diff < (60 * 15)) {
            return True;
        }
    }

    return False;
}
