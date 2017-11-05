<?php

namespace Web\Pages\Main\Widgets\LatestForumThreads;

require_once "helper.php";
require_once "./session/config/settings.php";
require_once "./database/Extract.php";
require_once "./helper/format.php";
require_once "./framework/data/Widget.php";

class Data extends \Web\Framework\Data\Widget
{

    public function banner()
    {
        return "latest posts";
    }

    public function latestThreads()
    {
        $amount = (int) $_SESSION['settings']->value("n_latest_threads");
        return \Web\Database\Extract::latestThreads($amount);
    }

}
