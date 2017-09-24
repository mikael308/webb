<?php

namespace Web\Pages\Forum\Widgets\Forum\Main;

use Web\Database\Read;

class Data extends \Web\Framework\Data\Widget
{

	public function __construct()
	{
		
	}

	public function getSubjects()
	{
		return Read::subjects();
	}	

}