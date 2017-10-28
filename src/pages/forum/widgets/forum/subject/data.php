<?php

namespace Web\Pages\Forum\Widgets\Forum\Subject;

use \Web\Database\Count;
use \Web\Database\Read;

class Data extends \Web\Framework\Data\Widget
{

    private $subject = null;

    private $threads = null;

    private $maxPages = null;

    public function __construct()
    {
        $this->subject = Read::subject(getSubjectIndex());
        $this->threads = $this->subject->getThreads();

        $this->maxPages = Count::maxPagesSubject($this->subject);
    }

    public function getSubject()
    {
        return $this->subject;  
    }

    /**
     * get the threads of subject
     * @return array
     */
    public function getThreads() 
    {
        return $this->threads;
    }

    public function getThreadsLength()
    {
        return count($this->getThreads());
    }

    /**
     * @return int settings value: amount of threads per page
     */
    public function getThreadsPerPage()
    {
        return (int) $_SESSION['settings']->value("threads_per_page");
    }

    public function getStartOffset()
    {
        return $this->getThreadsPerPage() * (getPageIndex() - 1);
    }

    /**
     * @return int settings value: threadlink max pagination groupinterval
     */
    public function getThreadlinkPaginationInterval()
    {
        return (int) $_SESSION['settings']->value('pag_max_interval_threadlink');
    }

    public function getMaxPages()
    {
        return $this->maxPages;
    }
    /**
     * @param \Web\Database\DAO\ForumThread $thread
     * @return int max amount of pages in thread
     */
    public function getThreadMaxPages(
        \Web\Database\DAO\ForumThread $thread
    ) {
        $n_posts = $thread->getSize();
        $posts_per_page = $_SESSION['settings']->value('posts_per_page');
        $maxThreadPages = ceil($n_posts / $posts_per_page);
        return $maxThreadPages;
    }

    public function threadInnerPag(
        \Web\Database\DAO\ForumThread $thread
    ) {
        $cont = "";
        $pagInterval = $this->getThreadlinkPaginationInterval();
        $lastPagIdx = 0;
        $pags = $this->getStartEndPags($thread, $pagInterval);
        foreach ($pags as $i => $pag){
            if ($i != ($lastPagIdx + 1)){
                # previous page was not current (index -1)
                # add a mark for stepping in index
                $cont .= " ... ";
            }
            $lastPagIdx=$i;
            $cont .= $pag;
        }
        return $cont;
    }

    /**
     * get pagination links to forumthread
     * @param $thread \Web\Database\DAO\ForumThread
     * @param $pagInterval int the size of pagination group
     * @return array|null list of pagination indexes
     */
    function getStartEndPags(
        $thread,
        $pagInterval = 1
    ) {
        if ($thread == null)
            return array();

        $maxPages = $this->getThreadMaxPages($thread);
        $maxlim = min($maxPages, $pagInterval);
        $pags = array();
        $i = 1;
        # beginning indexes
        for (; $i <= $maxlim; $i++){
            $pags[] = $i;
        }
        # get the end offset
        $end_offset = ($maxPages - $pagInterval) +1;
        if ($i < $end_offset){
            $i = $end_offset;
        }
        # ending indexes
        for (; $i <= $maxPages; $i++){
            $pags[] = $i;
        }
        return $pags;
    }

}
