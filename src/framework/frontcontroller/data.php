<?php
/**
* Created by PhpStorm.
* User: mikael
* Date: 9/11/17
* Time: 8:32 PM
*/

namespace Web\FrontController;

require_once PATH_ROOT_ABS."database/database.php";
require_once PATH_ROOT_ABS."loader.php";
require_once PATH_ROOT_ABS."pageref.php";
require_once PATH_ROOT_ABS."session/main.phtml";
require_once PATH_ROOT_ABS."session/config/settings.php";

$_SESSION['settings'] = new \Web\Session\Config\Settings(
    "../config/settings.conf"
);
if (! isset($_SESSION['settings'])) {
    $_SESSION['settings'] = new \Web\Session\Config\Settings(
        "../config/settings.conf"
    );
}

\Web\loadSections("request");
\Web\loadSections("import");

$display = false;
try {
    \Web\loadSections("listener");
    $display = true;
} catch (\Exception $e) {
    #TODO create 404 exception
}

function head()
{
    \Web\loadSections("head");
}

function header()
{
    \Web\loadSection("header", "main");
}

function main()
{
    global $display;
    if ($display) {
        \Web\loadSection("main", getPage());
    } else {
        \Web\loadContentView("page404_notfound", "main");
    }
}

function footer()
{
    \Web\loadSection("footer", "main");
    \Web\loadSection("script", getPage());
}
