<?php
	
	require_once "/vagrant/src/pageref.php";

	require_once PATH_ROOT_ABS."database/database.php";
	require_once PATH_ROOT_ABS."database/Persist.php";
	#require_once "/vagrant/src/database/Read.php";
	require_once PATH_ROOT_ABS."database/dao/ForumPost.class.php";

	use \Web\Database\Persist as Persist;
	use \Web\Database\DAO\ForumPost as ForumPost;

	\Web\Database\autoloadDAO();

	#$t = Read::ForumThread(2);

	$p1 = new ForumPost();
	$p1->setThreadFK(2);
	$p1->setAuthorFK("helen");
	$p1->setMessage("I - I thought you were...");

	$p2 = new ForumPost();
	$p2->setThreadFK(2);
	$p2->setAuthorFK("klaatu");
	$p2->setMessage("I was.");

	$p3 = new ForumPost();
	$p3->setThreadFK(2);
	$p3->setAuthorFK("helen");
	$p3->setMessage("You mean... he has the power of life and death?");

	$p4 = new ForumPost();
	$p4->setThreadFK(2);
	$p4->setAuthorFK("klaatu");
	$p4->setMessage("No. That power is reserved to the Almighty Spirit. This technique, in some cases, can restore life for a limited period.");

	$p5 = new ForumPost();
	$p5->setThreadFK(2);
	$p5->setAuthorFK("helen");
	$p5->setMessage("But... how long?");

	$p6 = new ForumPost();
	$p6->setThreadFK(2);
	$p6->setAuthorFK("klaatu");
	$p6->setMessage("You mean how long will I live? That no one can tell. ");

	if (Persist::forumPost($p1) &&
		Persist::forumPost($p2) &&
		Persist::forumPost($p3) &&
		Persist::forumPost($p4) &&
		Persist::forumPost($p5) &&
		Persist::forumPost($p6)){
			echo "persisting ForumPost instances successfully\n";
	} else {
		throw new Exception("could not persist ForumPost instances");
	}

?>
