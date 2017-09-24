<?php

namespace Web\Pages\Forum\Widgets\Forum\Main;

use Web\Database\Read;

class Data extends \Web\Framework\Data\Widget
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