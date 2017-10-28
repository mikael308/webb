<?php
namespace Web;

/**
* meta data:
* contains global vars: page reference filepaths to pages
* @author Mikael Holmbom
*/



define('PATH_ROOT_ABS', '/vagrant/src/');#$_SERVER['DOCUMENT_ROOT']."/src/");
define('PATH_ROOT_REL', "/src/");

# references to page rewrites
# key => URL
$GLOBALS["pagelink"] = array(
    "about_about"   => $page_root."about/about",
    "about_faq"     => $page_root."about/faq",
    "admin"         => $page_root."admin",
    "contact"       => $page_root."contact",
    "forum"         => $page_root."forum",
    "index"         => $page_root,
    "news"          => $page_root."news",
    "register"      => $page_root."register",
    "search"        => $page_root."search",
    "post"          => $page_root."post",
    "user"          => $page_root."user",
    "user_info"     => $page_root."user/info",
    "user_settings" => $page_root."user/settings"
);

# generate pagelinks from objects

function pagelinkPost(
    $subpage
) {
    $root = $GLOBALS['pagelink']['post'];
    return "$root/$subpage";
}

/**
* generate pagelink to forum subject
*/
function pagelinkForumSubject(
    $subject_id,
    $page_id = 1
) {
    $root = $GLOBALS["pagelink"]["forum"];
    return "$root/subject/$subject_id/$page_id";
}

/**
* generate pagelink to forum thread
*/
function pagelinkForumThread(
    $thread_id,
    $page_id = 1
) {
    $root = $GLOBALS["pagelink"]["forum"];
    return "$root/thread/$thread_id/$page_id";
}

/**
* generate pagelink to userpage
*/
function pagelinkUser(
    $user_id,
    $subPage = null
) {
    $root = $GLOBALS["pagelink"]["user"];
    $url = "$root";
    if ($user_id != null && !empty($user_id)) {
        $url .= "/$user_id";
    }

    if ($subPage != null && !empty($user_id)) {
        $url = "$root/$user_id/$subPage";
    }

    return $url;
}
