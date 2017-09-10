<?php

namespace Widgets\Forum\Forum;

use \Database\Read;

class Data extends \Framework\Data\Widget
{

    private $contentObj = null;

    public function __construct()
    {
        switch(getSubPage())
        {
            case 'subject':
            $this->contentObj = Read::subjects(getSubjectIndex())[0];
            break;
            case 'thread':
            $this->contentObj = Read::thread(getThreadIndex());
            break;
        }
    }

    public function getHeader()
    {
        if ($this->contentObj == null) return '';

        return $this->contentObj->getTopic();
    }

    public function getContent()
    {
        $widgetName = '';
        switch(getSubPage())
        {
            case 'subject':
                $widgetName = 'forum/subject';
            break;
            case 'thread':
                $widgetName = 'forum/thread';
            break;
            case 'main':
                $widgetName = 'forum/main';
            break;
            default:
                return;
            break;
        }
        \Framework\Factory\Widgets::include($widgetName, 'forum');
    }

}