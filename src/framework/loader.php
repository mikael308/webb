<?php
/**
 * helperfunctions to load page sections
 * @author Mikael Holmbom
 */

namespace Web\Framework;

require_once PATH_ROOT_ABS."framework/format.php";

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
