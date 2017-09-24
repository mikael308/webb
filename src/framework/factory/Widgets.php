<?php

namespace Web\Framework\Factory;

require_once "./helper/format.php";

class Widgets
{

    private static function widgetRootPath(
        $widgetName,
        $page = "main"
    ) {
        return \Web\Helper\Format::nsToPath(
            $_SERVER['DOCUMENT_ROOT']."/webb/src/pages/$page/widgets/$filename"
        );
    }

    /**
     * create a widget dataobject
     * @param $widgetName string name of the widget
     * @param string $page name of the page containing the widget
     * @return \Web\Framework\Data\Widget widget datainstance
     * @throws \Exception
     */
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

    /**
     * Output dataobject
     * @param $dataObj \Web\Framework\Data\Widget the dataobject to output
     * @throws \Exception
     */
    public static function output(
        $dataObj
    ) {
        $widgetRoot = dirname($dataObj->path());

        $data = $dataObj;
        $viewPath = "$widgetRoot/view.phtml";
        
        if (is_file($viewPath)) {
            include $viewPath;
        } else {
            throw new \Exception("could not find file $viewPath");
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
                throw new \Exception("could not find file $viewPath");
            }     
        }
    }

}
