<?php

namespace Web\Framework\Factory;

require_once PATH_ROOT_ABS."framework/format.php";

class Widgets
{

    private static function widgetRootPath(
        $filename,
        $page = "main"
    ) {
        return \Web\Framework\Format::nsToPath(
            PATH_ROOT_ABS."pages/$page/widgets/$filename"
        );
    }

    /**
     * create a widgets dataobject
     * @param $widgetName string name of the widgets
     * @param string $page name of the page containing the widgets
     * @return \Web\Framework\Data\Widget widgets datainstance
     * @throws \Exception if data is not found
     */
    public static function create(
        $widgetName,
        $page = 'main'
    ) {
        $widgetRoot = Widgets::widgetRootPath($widgetName, $page);
        $dataPath = "$widgetRoot/data.php";
        if (!is_file($dataPath)) {
            throw new \Exception("could not find data $dataPath");
        }
        require_once $dataPath;

        $obj = \Web\Framework\Format::pathToNS(
            "\\web\\Pages\\$page\\widgets\\$widgetName\\data"
        );

        return new $obj();
    }

    /**
     * Output dataobject
     * @param $dataObj \Web\Framework\Data\Widget the dataobject to output
     * @throws \Exception if view is not found
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
        $page = 'main'
    ) {
        try {
            $dataObject = Widgets::create($widgetName, $page);
            Widgets::output($dataObject);

        } catch(\Web\Framework\Exception\InfoException $e) {
            echo $e->toHtml();
        } catch(\Web\Framework\Exception\InvalidPageException $e) {
            echo $e->toHtml();
        } catch(\Exception $e) {
            $viewPath = Widgets::widgetRootPath($widgetName, $page) . '/view.phtml';
            if (is_file($viewPath)) {
                include $viewPath;
            } else {
                throw new \Exception("could not find file $viewPath");
            }     
        }
    }

}
