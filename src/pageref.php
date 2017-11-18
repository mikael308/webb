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
$GLOBALS['pagelink'] = array(
    'about_about'   => '/about/about',
    'about_faq'     => '/about/faq',
    'admin'         => '/admin',
    'contact'       => '/contact',
    'forum'         => '/forum',
    'index'         => '/',
    'news'          => '/news',
    'register'      => '/register',
    'search'        => '/search',
    'post'          => '/post',
    'user'          => '/user',
    'user_info'     => '/user/info',
    'user_settings' => '/user/settings'
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
 * @param string $subject_id
 * @param int $page_id
 * @return string url path to forumsubject page
 */
function pagelinkForumSubject(
    $subject_id,
    $page_id = 1
) {
    $root = $GLOBALS['pagelink']['forum'];
    return "$root/subject/$subject_id/$page_id";
}

/**
 * generate pagelink to forum thread
 * @param string $thread_id
 * @param int $page_id
 * @return string url path to forumthread page
 */
function pagelinkForumThread(
    $thread_id,
    $page_id = 1
) {
    $root = $GLOBALS['pagelink']['forum'];
    return "$root/thread/$thread_id/$page_id";
}

/**
 * generate pagelink to userpage
 * @param ForumUser $user
 * @param null|string $subPage
 * @return string
 */
function pagelinkUser(
    ForumUser $user = null,
    $subPage = null
) {
    $url = $GLOBALS['pagelink']['user'];
    if ($user != null) {
        $url .= '/' . $user->getName();
        if ($subPage != null) {
            $url .= "/$subPage";
        }
    }

    return $url;
}
