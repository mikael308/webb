<?php

namespace Web\Pages\News\Widgets\Newsfeed;

require_once "./database/Read.php";
require_once "./framework/data/Widget.php";

class Data extends \Web\Framework\Data\Widget
{

    function getNews()
    {
        return \Web\Database\Read::news();
    }

}
