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

        if ($this->getThreadsLength() < 1) {
            throw new \Web\Framework\Exception\InfoException('subject contains no threads yet');
        }

        $page = getPageIndex();
        if ($page < 1 || $page > $this->getMaxPages()) {
            throw new \Web\Framework\Exception\InvalidPageException("could not display page");
        }

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

    /**
     * get pagination links to forumthread
     * @param $thread \Web\Database\DAO\ForumThread
     * @return array|null list of pagination indexes
     */
    function getStartEndPags(
        $thread
    ) {
        if ($thread == null)
            return array();
        $pagInterval = $this->getThreadlinkPaginationInterval();
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
