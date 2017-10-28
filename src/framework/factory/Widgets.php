<?php

namespace Web\Framework\Factory;

require_once PATH_ROOT_ABS."helper/format.php";

class Widgets
{

    private static function widgetRootPath(
        $filename,
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
        if (!is_file($dataPath)){
            throw new \Exception("could not find data $dataPath");
        }
        try {
            require_once $dataPath;

            $obj = \Web\Helper\Format::pathToNS(
                "\\web\\Pages\\$page\\widgets\\$widgetName\\Data"
            );
            return new $obj();
            
        } catch(\Exception $e) {
            echo "<h1>".$e->getMessage()."</h1>";
        }
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

        $viewPath = "$widgetRoot/view.phtml";
        
        if (is_file($viewPath)) {
            $data = $dataObj;
            include $viewPath;
        } else {
            throw new \Exception("could not find file $viewPath");
        }

    }

    public static function addToPage(
        $widgetName,
        $page = "main"
    ) {
        try {
            $dataObject = Widgets::create($widgetName, $page);
            Widgets::output($dataObject);
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
