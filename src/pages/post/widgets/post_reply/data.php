<?php

namespace Web\Pages\Post\Widgets\Post_reply;

require_once "./database/Read.php";
require_once "./database/post.php";

use Web\Database\Read;

class Data extends \Web\Framework\Data\Widget
{
    private $thread;
    
    public function __construct()
    {

        if (getThreadIndex() == null) {
            #TODO how to handle this throw exception
            return \Web\Helper\Message::error("invalid thread");
        }

        $this->thread = \Web\Database\Read::thread(getThreadIndex());
    }

    public function getThread()
    {
        return $this->thread;
    }

}