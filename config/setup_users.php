<?php

/**
 * config script used to setup forumusers in database
 */


require_once "./database/persist.php";
require_once "./config/pageref.php";
require_once "./format/display.php";

	# autoload classes
	spl_autoload_register(function($class) {
		include 'classes/' . $class . '.class.php';
	});

$tom = new ForumUser();
$tom->setName('tom');
$tom->setEmail('tom@gmail.com');
$tom->setRole(1);
$tom->setBanned(False);
$tom->setRegistered(date($GLOBALS['timestamp_format']));

#persist::forumUser($tom, 'abc');

$tim = new ForumUser();
$tim->setName('tim');
$tim->setEmail('t@gmail.com');
$tim->setRole(0);
$tim->setBanned(False);
$tim->setRegistered(date($GLOBALS['timestamp_format']));


$mik = new ForumUser();
$mik->setName('mikael');
$mik->setEmail('mik@gmail.com');
$mik->setRole(2);
$mik->setBanned(False);
$mik->setRegistered(date($GLOBALS['timestamp_format']));


if(persist::forumUser($tom, 'abc') &&
	persist::forumUser($tim, 'hej') && 
	persist::forumUser($mik, 'asd')){

	header("Location: index.php");
	exit();
	
} else{
	echo pg_last_error($db_conn);
}








?>