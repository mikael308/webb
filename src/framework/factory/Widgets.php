<?php

namespace Framework\Factory;

require_once "./helper/format.php";

class Widgets
{

    private static function widgetRootPath(
        $widgetName,
        $page = "main"
    ) {
        return \Helper\Format::nsToPath(
            "./pages/$page/widgets/$widgetName"
        );
    }
    
    public static function create(
        $widgetName,
        $page = "main"
    ) {
        $dataPath = Widgets::widgetRootPath(
            $widgetName,
            $page
        ) . "/data.php";
        
        if (!is_file($dataPath))
            throw new \Exception("could not read data $dataPath");
        try {
            require_once $dataPath;
            
        } catch(\Exception $e) {
            echo "<h1>".$e->getMessage()."</h1>";
        }

        $obj = \Helper\Format::pathToNS(
            "\\widgets\\$page\\$widgetName\\Data"
        );
        return new $obj();
    }

    public static function output(
        $dataObj
    ) {
        $widgetRoot = dirname($dataObj->path());

        $data = $dataObj;
        $viewPath = "$widgetRoot/view.phtml";
        
        if (is_file($viewPath)) {
            include $viewPath;
        } else {
            throw new \Exception("could not read file $viewPath");
        }

    }

    public static function include(
        $widgetName,
        $page = "main"
    ) {
        try {
            Widgets::output(
                Widgets::create($widgetName, $page)
            );
        } catch(\Exception $e) {
            $viewPath = Widgets::widgetRootPath($widgetName, $page) . "/view.phtml";
            
            if (is_file($viewPath)) {
                include $viewPath;
            
            } else {
                throw new \Exception("could not read file $viewPath");
            }     
        }
    }

}
