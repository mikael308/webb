<?php

namespace Widgets\Forum\Forum\Main;

use Database\Read;

class Data extends \Framework\Data\Widget
{

	private $forumObject = null;

	public function __construct()
	{
		
	}

	public function getSubjects()
	{
		return Read::subjects();
	}	

}