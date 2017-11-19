<?php
/**
* Created by PhpStorm.
* User: mikael
* Date: 9/11/17
* Time: 8:32 PM
*/

namespace Web\FrontController;

require_once PATH_ROOT_ABS."database/database.php";
require_once PATH_ROOT_ABS."framework/loader.php";
#require_once PATH_ROOT_ABS."reference.php";
require_once PATH_ROOT_ABS."framework/session/main.php";
require_once PATH_ROOT_ABS."framework/request/page.php";
require_once PATH_ROOT_ABS."framework/config/settings.php";

use function Web\Framework\Request\getPage;
use function \Web\Framework\loadSection;
use function \Web\Framework\loadContentView;


loadSection("request");
loadSection("request", getPage());
loadSection("import");
loadSection("import", getPage());

$display = false;
try {
    loadSection("listener");
    loadSection("listener", getPage());
    $display = true;
} catch (\Exception $e) {
    #TODO create 404 exception
}

function head()
{
    loadSection("head");
    loadSection("head", getPage());
}

function header()
{
    loadSection("header", "main");
}

function main()
{
    global $display;
    if ($display) {
        loadSection("main", getPage());
    } else {
        loadContentView("page404_notfound", "main");
    }
}

function footer()
{
    loadSection("footer", "main");
    loadSection("script", getPage());
}
