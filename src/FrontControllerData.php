<?php
/**
* Created by PhpStorm.
* User: mikael
* Date: 9/11/17
* Time: 8:32 PM
*/

namespace Web;

require_once "./database/database.php";
require_once "./loader.php";
require_once "./pageref.php";
require_once "./session/main.php";
require_once "./session/config/settings.php";

$_SESSION['settings'] = new \Web\Session\Config\Settings(
    "../config/settings.conf"
);
if(! isset($_SESSION['settings'])){
    $_SESSION['settings'] = new \Web\Session\Config\Settings(
        "../config/settings.conf"
    );
}

loadSections("request");
loadSections("import");

$display = false;
try {
    loadSections("listener");
    $display = true;
} catch (\Exception $e) {
    #TODO create 404 exception
}

function head()
{
    loadSections("head");
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
