<?php

namespace Framework\Data;

class Widget
{
	/**
	 * get the path to the childclass
	 */
    public function path()
    {
        $reflector = new \ReflectionClass(get_class($this));
        return $reflector->getFileName();
    }

    public function loadContent(
    	$content,
    	$args
    ) {
    	$widgetpath = dirname($this->path($this));
        $fullPath = "$widgetpath/content/$content.phtml";

        if (is_file($fullPath)) {
    	   include $fullPath;
           return false;
        }
        return true;
    }

}
