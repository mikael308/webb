<?php

namespace Web\Framework\Request;

require_once PATH_ROOT_ABS."framework/session/main.php";

\Web\Framework\Session\start();


/**
 * get the current requested page
 */
function getPage()
{
    return $_REQUEST['page'] ?? '';
}

/**
 * get the current requested subpage
 */
function getSubpage()
{
    return $_REQUEST['subpage'] ?? '';
}

/**
 * get the current domain sub url
 * @return string
 */
function getSubUrl()
{
    return $_SERVER["REQUEST_URI"] ?? '';
}

function getPageIndex()
{
    return getIndex("page_index");
}
