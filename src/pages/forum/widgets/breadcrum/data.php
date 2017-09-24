<?php

namespace Web\Pages\Forum\Widgets\Breadcrum;

require_once "./database/Read.php";
require_once "./framework/data/Widget.php";

use Web\Database\Read;

class Data extends \Web\Framework\Data\Widget
{

    private $subject = null;

    private $thread = null;

    #THREAD_KEY = "thread";
    #SUBJECT_KEY = "subject";
    
    public function __construct()
    {
        
    }

    public function getSubject()
    {
        if (isset($_REQUEST["subject"])) {
            return Read::subject($_REQUEST["subject"]);
        } elseif (isset($_REQUEST["thread"])) {
            return Read::thread($_REQUEST["thread"])->getSubject();
        }

        return null;
    }

    public function getThread()
    {
        if (isset($_REQUEST["thread"])){
            return Read::thread($_REQUEST["thread"]);
        }
        
        return null;
    }

}