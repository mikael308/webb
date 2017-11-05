<?php

namespace Web;

require_once PATH_ROOT_ABS."helper/format.php";

function loadSection(
    $name,
    $page = 'main'
) {
    $filepath = "./pages/$page/sections/$name.phtml";

    if (is_file($filepath)) {
        include $filepath;
    }
}

/**
 * loads the main and the page section of $name
 * @param $name
 */
function loadSections($name)
{
    loadSection($name);
    loadSection($name, getPage());  
}

/**
 * includes content view
 * @param string $name name of content to include
 * @param string $page the page containing the view
 */
function loadContentView (
    $name,
    $page = 'main'
) {
    $path = "pages/$page/content/$name.phtml";

    if (! is_file($path)) {
        echo "<p>could not find contentview file \"$path\"</p>";
    } else {
        include $path;
    }
}
