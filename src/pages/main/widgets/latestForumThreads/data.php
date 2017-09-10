<?php

namespace Widgets\main\latestForumThreads;

require_once "helper.php";
require_once "../config/settings.php";
require_once "./database/Extract.php";
require_once "./helper/format.php";
require_once "./framework/data/Widget.php";

class Data extends \Framework\Data\Widget
{

    public function banner()
    {
        return "latest posts";
    }


    public function latestThreads()
    {
        $amount = (int) \Settings\read("n_latest_threads");
        return \Database\Extract::latestThreads($amount);
    }

}
