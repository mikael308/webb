<?php

namespace Web\Pages\Forum\Widgets\Pagination;

require_once "./database/database.php";

#TODO ta bort denna???
\Web\Database\autoloadDAO();

class Data extends \Web\Framework\Data\Widget
{

    private $totIndexes = -1;

    private $forumObject = null;

    public function __construct()
    {
        $t = getThreadIndex();
        if ($t != null) {
            $this->forumObject = \Web\Database\Read::thread($t);
        }
        $s = getSubjectIndex();
        if ($s != null) {
            $this->forumObject = \Web\Database\Read::subjects($s)[0];
        }
        $this->totIndexes = $this->forumObject->getLastPageIndex();
    }

    public function currentIndex()
    {
        return getPageIndex();
    }

    public function isCurrent($idx)
    {
        return $idx == $this->currentIndex();
    }

    public function prevIndex()
    {
        return max(
            $this->currentIndex() - 1,
            1
        );
    }

    public function nextIndex()
    {
        return min(
            $this->currentIndex() + 1,
            $this->totIndexes()
        );
    }

    public function firstIndex()
    {
        return 1;
    }

    public function lastIndex()
    {
        return $this->totPags();
    }

    private function totIndexes()
    {
        return $this->totIndexes;
    }

    public function indexLink($idx)
    {
        #echo "getting index link ";
        $obj = $this->forumObject;
        if($obj instanceof \Web\Database\DAO\ForumThread)
        {
            return \Web\pagelinkForumThread(
                $obj->getPrimaryKey(),
                $idx
            );
        } else if($obj instanceof \Web\Database\DAO\ForumSubject)
        {
            return \Web\pagelinkForumSubject(
                $obj->getPrimaryKey(),
                $idx
            );
        }
    }

    public function firstIndexLink()
    {
        return $this->indexLink(
            1
        );
    }

    public function prevIndexLink()
    {
        return $this->indexLink(
                $this->prevIndex()
            );
    }

    public function nextIndexLink()
    {
        return $this->indexLink(
                $this->nextIndex()
            );
    }

    public function lastIndexLink()
    {
        return $this->indexLink(
            $this->totIndexes()
        );
    }

    public function getIndexes()
    {
        $idxs = array();


        $offset = $this->getOffsets($this->currentIndex(), 10);

        # correct the interval to match the size of the page list length
        $i = max($offset["left"], 1); # start offset -> min value: 1
        $maxlim = min($offset["right"], $this->totIndexes);

        # generate index
        for (; $i <= $maxlim; $i++) {
            $idxs[] = $i;
        }




        return $idxs;
    }

    /**
     * get the index offsets of pagination
     * the interval of page links will always be max value of
     * setting value [pag_max_interval]
     * @return array
     */
    private function getOffsets(
        $currentPage, 
        $n_pages
    ) {
        $pageWidth = (int) $_SESSION['settings']->value("pag_max_interval");

        # offset of pagination indexes
        $left_offset = $currentPage - $pageWidth;
        $right_offset = $currentPage + $pageWidth;

        # expand the upper or lower interval if current page
        # is in beginning or end of page list length
        if ($left_offset < 1) {
            $right_offset = min(
                $n_pages,
                ($right_offset + abs($left_offset-1))
            );
        }
        if ($right_offset > $n_pages) {
            $left_offset = max(
                1,
                ($left_offset - abs($n_pages-$right_offset))
            );
        }

        return array(
            "left" => $left_offset,
            "right" => $right_offset
        );
    }

}