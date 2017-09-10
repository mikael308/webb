<?php

namespace Widgets\Post\Post_reply;

require_once "./database/Read.php";
require_once "./database/post.php";

use Database\Read;

class Data extends \Framework\Data\Widget
{
    private $thread;
    
    public function __construct()
    {

        if (getThreadIndex() == null) {
            #TODO how to handle this throw exception
            return \Helper\Message::error("invalid thread");
        }

        $this->thread = \Database\Read::thread(getThreadIndex());
    }

    public function getThread()
    {
        return $this->thread;
    }

}