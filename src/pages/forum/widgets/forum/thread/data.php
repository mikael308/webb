<?php

namespace Widgets\Forum\Forum\Thread;

require_once "./database/post.php";
require_once "./database/Read.php";

#TODO rm
use \Database\Post;
use \Database\Read;

class Data extends \Framework\Data\Widget
{
	public function getThread()
	{
		return \Database\Read::thread(getThreadIndex());
	}

	public function getPost()
	{
		return \Databse\Read::post(getPostIndex());
	}
}