<?php
namespace Web\Ajax;

/**
 * use request 'id' for searchstring \n
 * echos list of links to users that match with searchstring\n
 * max amount of results displayed set in settings.md as var searchres_user_amount
 * @author Mikael Holmbom
 * @version 1.0
 */
header('Content-Type: application/json');

#require_once "./pageref.php";
#require_once "./config/settings.php";
#require_once "./webb/src/database/database.php";
#require_once "../database/database.php";
#$serverroot = $_SERVER['DOCUMENT_ROOT'];
#$databasePath = "$serverroot/webb/src/database/database.php";
#require_once $_SERVER['DOCUMENT_ROOT'] ."/webb/src/database/database.php";
#require_once $_SERVER['DOCUMENT_ROOT'] ."/webb/src/database/search.php";
#require_once "./sections/forum/main.php";

#TODO denna fil hittas. men den kraschar.. istllet för att fixa alla elements här i
# returnera en JSON bara och skriv ut den i searchsidepanel... bara bra om den fixar all frontend...

echo "<p>searchres ajax found</p>";
/**
 *
 * search result for ForumPost type
 * @param post \Database\DAO\ForumPost the search result
 * @return string searchresult as html
 *//*
function searchresPost(
    \Database\DAO\ForumPost $post
) {
    $maxlen = 40;
    $label = \Helper\Format::textToLength($post->getMessage(), $maxlen);
    $p = \Database\Count::postPageIndex($post->getPrimaryKey());
    $t = $post->getThread();
    $author = $post->getAuthor();
    return
        "<a href='". pagelinkForumThread($t, $p) ."'>"
        .   "<div class='post main'>"
        .     $label
        .   "</div>"
        . "</a>"
        . "<div class='post extra'>"
        .   "<div class='author'>"
        .     "author: <a href='". pagelinkUser($author) ."'>"
        .       $author->getName() . "</a>"
        .   "</div>"
        .   "<div class='topic' >"
        .     "topic: <a href='".pagelinkForumThread($t, 1)."'>"
        .       $t->getTopic() ."</a>"
        .   "<div>"
        . "</div>";
}*/

/**
 *
 * search result for ForumUser type
 * @param user 
 * @return string result as html
 *//*
function searchresForumUser(
    \Database\DAO\ForumUser $user
) {
    $cont = "<div class='name'>". $user->getName() ."</div>"
        . "<div class='role'>". $user->getRole() . "</div>";
    return
            "<a href='" . pagelinkUser($user) . "' class='user main'>"
        . "<div class='user extra'>"
        .   $cont
        . "</div>"
        . "</a>"
        ;
}*/

/**
 * get data as searchres listitem
 * @param data string listitem data
 * @return string li as html
 *//*
function search_li(
    $data
) {
    return "<li class='searchres_item'>". $data ."</li>";
}*/

$searchstr = isset($_REQUEST["value"]) ? $_REQUEST["value"] : "";
$searchType = isset($_REQUEST["type"]) ? $_REQUEST["type"] : "";

$nothingfoundres = "<li class='searchres_item'>no results</li>";
$suggest = "";
if ($searchstr != "") {
    $reslist = "";

    $json_array = [];
    # determine the type of the search
    switch($searchType) {
        case "post":
            $reslist = \Database\searchPost($searchstr);

            /*$reslist = \Database\searchPost($searchstr);
            if($reslist == NULL){
                $suggest = $nothingfoundres;
            } else {
                foreach($reslist as $item){
                    $suggest .= search_li(searchresPost($item));
                }
            }*/
            break;
        case "user":
            $reslist = \Database\searchForumUser($searchstr);

            /*if($reslist == NULL){
                $suggest = $nothingfoundres;
            } else {
                foreach($reslist as $item){
                    $suggest .= search_li(searchresForumUser($item));
                }
            }*/
        break;
    }
    $json_array = json_encode($reslist);
    $suggest = $json_array;
}

echo $suggest == ""
    ? ""
    : $suggest;
