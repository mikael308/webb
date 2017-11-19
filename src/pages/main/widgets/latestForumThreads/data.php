<?php

namespace Web\Pages\Main\Widgets\LatestForumThreads;

use function Web\Framework\Request\getSettings;

require_once "helper.php";
require_once PATH_ROOT_ABS."framework/config/settings.php";
require_once PATH_ROOT_ABS."database/Extract.php";
require_once PATH_ROOT_ABS."framework/format.php";
require_once PATH_ROOT_ABS."framework/data/Widget.php";

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
