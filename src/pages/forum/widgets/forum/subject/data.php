<?php

namespace Widgets\Forum\Forum\Subject;

use \Database\Count;
use \Database\Read;

class Data extends \Framework\Data\Widget
{

    private $subject = null;

    private $threads = null;

    private $maxPages = null;

    public function __construct()
    {
        $this->subject = \Database\Read::subjects(getSubjectIndex())[0];
        $this->threads = $this->subject->getThreads();

        $this->maxPages = \Database\Count::maxPagesSubject($this->subject);
    }

    public function getSubject()
    {
        return $this->subject;  
    }

    public function getThreads() 
    {
        return $this->threads;
    }

    public function getThreadsLength()
    {
        return count($this->getThreads());
    }

    public function getThreadsPerPage()
    {
        return (int) \Settings\Read("threads_per_page");
    }

    public function getStartOffset()
    {
        return ($this->getThreadsPerPage() * (getPageIndex() - 1));
    }

    public function getMaxPages()
    {
        return $this->maxPages;
    }

    public function threadInnerPag(ForumThread $thread)
    {
        #$cont = "";
        $pagInterval = Settings\read("pag_max_interval_threadlink");
        $lastPagIdx = 0;
        $pags = getStartEndPags($thread, $pagInterval);
        foreach($pags as $i => $pag){
            if($i != ($lastPagIdx+1)){
                # previous page was not current (index -1)
                # add a mark for stepping in index
                $cont .= " ... ";
            }
            $lastPagIdx=$i;
            $cont .= $pag;
        }
        return
              "<div class='threadlink_pagination'>"
            .   $cont
            . "</div>";
    }

    /**
     * get pagination links to
     * @return list of pagination links
     */
    function getStartEndPags($thread, $pagInterval)
    {
        if($thread == NULL)
            return "";
        
        $maxPages = $this->getMaxPages();//Count::maxPagesThread($thread);
        $maxlim = min($maxPages, $pagInterval);
        $pags = array();
        $i = 1;
        # beginning indexes
        for(; $i <= $maxlim; $i++){
            $pags[$i] = threadlinkPagButton($thread, $i);
        }
        # get the end offset
        $end_offset = ($maxPages - $pagInterval) +1;
        if($i < $end_offset){
            $i = $end_offset;
        }
        # ending indexes
        for(;$i <= $maxPages; $i++){
            $pags[$i] = threadlinkPagButton($thread, $i);
        }
        return $pags;
    }

}