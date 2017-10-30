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


function loadContentView (
    $name,
    $page = 'main'
) {
    $path = '';
    if ($page == null) {
        echo "<h1>deprecated A001: use </h1>";
        #TODO widgets?
        $path = "widgets/$name.phtml";
    } else {
        $path = "pages/$page/content/$name.phtml";
    }

    #try {
        if (! is_file($path)) {
            echo "<p>could not find contentview file \"$path\"</p>";
        } else {
            include $path;
        }
    #} catch (Exception $e) {
    #    echo "<p>ERROR: ".$e->getMessage()."</p>";
    #}
}
