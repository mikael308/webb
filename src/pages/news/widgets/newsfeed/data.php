<?php

namespace Widgets\News\Newsfeed;

require_once "./database/Read.php";
require_once "./framework/data/Widget.php";

class Data extends \Framework\Data\Widget
{

    function getNews()
    {
        return \Database\Read::news(" ORDER BY news.created DESC");
    }

}