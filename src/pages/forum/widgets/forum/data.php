<?php

namespace Web\Pages\Forum\Widgets\Forum;

use \Web\Database\Read;
use function Web\Framework\Request\getSubpage;

class Data extends \Web\Framework\Data\Widget
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
        return $this->contentObj == null
            ? ''
            : $this->contentObj->getTopic();
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
        \Web\Framework\Factory\Widgets::addToPage($widgetName, 'forum');
    }

}