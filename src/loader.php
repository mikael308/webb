<?php

namespace Web;

require_once "./helper/format.php";

function loadSection(
    $name,
    $page = "main"
) {
    #try {
        $path = "./pages/$page/sections/$name.phtml";

        if (is_file($path)) {
            include $path;
        } else {
            #TODO kasta något annat fel, skriv ett eget?
            #throw new Exception("file $path not found");
        }
    #} catch (\Exception $e) {
    #    #TODO log
    #    #echo "<p>ERROR: ".$e->getMessage()."</p>";
    #}
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
    $page = "main"
) {
    $path = "";
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

#TODO rm
/*
function loadWidget(
    $name,
    $page = null
) {
    echo "<h3>$name is deprecated, use widgetFactory instead</h3>";
    $dataPath = "";
    $dataObjPath = "";
    $viewPath = "";

    if ($page == null) {
        $root = "./widgets/$name";
        $name = \Helper\Format::pathToNs($name);
        $dataObjPath = "widgets\\$name\\Data";
        $dataPath = "$root/data.php";
        $viewPath = "$root/view.phtml";
    } else {
        $root = "./pages/$page/widgets/$name";
        $page = \Helper\Format::pathToNs($page);
        $name = \Helper\Format::pathToNs($name);
        $dataObjPath = "widgets\\$page\\$name\\Data";
        $dataPath = "$root/data.php";
        $viewPath = "$root/view.phtml";
    }

    #try {
        if (is_file($dataObjPath)){
            require_once $dataPath;
            $data = new $dataObjPath();
        } else {
        #TODO write to logger
        #TODO else throw exc
        #echo "<p>could not find contentview file \"$path\"</p>";
        }

    #} catch (Exception $e){ }

    #try {
        if (is_file($viewPath)) {
            include $viewPath;
        }
        #TODO else throw exc

    #} catch (Exception $e) { 
    #    #TODO write to logger
    #    echo "<p>could not find contentview file \"$path\"</p>";
    #}
}
*/
