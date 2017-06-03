<?php

/**
 * config script used to setup forumusers in database
 */

require_once "./database/database.php";
require_once "./database/Persist.php";
require_once "./sections/dateformat.php";

autoloadDAO();


$tom = new ForumUser();
$tom->setName('tom');
$tom->setEmail('tom@gmail.com');
$tom->setRole(1);
$tom->setBanned(False);
$tom->setRegistered(date($GLOBALS['timestamp_format']));

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

$hil = new ForumUser();
$hil->setName('hilda');
$hil->setEmail('hilda@gmail.com');
$hil->setRole(0);
$hil->setBanned(False);
$hil->setRegistered(date($GLOBALS['timestamp_format']));

$bern = new ForumUser();
$bern->setName('bernhardt');
$bern->setEmail('bernhardt@gmail.com');
$bern->setRole(2);
$bern->setBanned(False);
$bern->setRegistered(date($GLOBALS['timestamp_format']));

$klaatu = new ForumUser();
$klaatu->setName('klaatu');
$klaatu->setEmail('klaatu@gmail.com');
$klaatu->setRole(2);
$klaatu->setBanned(False);
$klaatu->setRegistered(date($GLOBALS['timestamp_format']));

$hel = new ForumUser();
$hel->setName('helen');
$hel->setEmail('helen@gmail.com');
$hel->setRole(2);
$hel->setBanned(False);
$hel->setRegistered(date($GLOBALS['timestamp_format']));

if(Persist::forumUser($tom, 'abc') &&
	Persist::forumUser($tim, 'hej') && 
	Persist::forumUser($mik, 'asd') &&
	Persist::forumUser($hil, 'hhh') &&
	Persist::forumUser($hel, 'hhh') &&
	Persist::forumUser($bern, 'bbb') &&
	Persist::forumUser($klaatu, 'k')){

	echo "setup users successful\n";
	exit();
	
} else{
	echo pg_last_error($db_conn);
    echo "See setup_readme.md for information on setup db\n";
}








?>
