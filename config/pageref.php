<?php
  /**
   * meta data:
   * contains global vars: page reference filepaths to pages
   * @author Mikael Holmbom
   */
  
  # absolute path to web root
  $GLOBALS["site-root"] = "/webb/";
  
  # references to actual page files
  $GLOBALS["pageref"] = array(
    "about"         => "about.php",
    "admin"         => "admin.php",
    "contact"       => "contact.php",
    "forum"         => "forum",
    "index"         => "index.php",
    "post"          => "post.php",
    "register"      => "registeruser.php",
    "user"          => "user.php"
  );

  # references to page rewrites
  $GLOBALS["pagelink"] = array(
    "about_about"   => $GLOBALS['site-root']."about",
    "about_faq"     => $GLOBALS['site-root']."about/faq",
    "forum"         => $GLOBALS['site-root']."forum",
    "index"         => $GLOBALS['site-root']."",
    "register"      => $GLOBALS['site-root']."register",
    "user"          => $GLOBALS['site-root']."user"
  );

  # generate pagelinks from objects

  /**
   * generate pagelink to forum subject
   */
  function pagelinkForumSubject(
    $subject_id,
    $page_id = 1
  ) {
    return $GLOBALS['pagelink']['forum']
      ."/". $subject_id
      ."/". $page_id;
  }

  /**
   * generate pagelink to forum thread
   */
  function pagelinkForumThread(
    $subject_id,
    $thread_id,
    $page_id = 1
  ) {
    return $GLOBALS['pagelink']['forum']
      ."/". $subject_id
      ."/". $thread_id
      ."/". $page_id;
  }

  /**
   * generate pagelink to userpage
   */
  function pagelinkUser(
    $user_id
  ) {
    return $GLOBALS['pagelink']['user']
      ."/". $user_id;
  }
