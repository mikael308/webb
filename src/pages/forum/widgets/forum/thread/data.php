<?php
namespace Web\Pages\Forum\Widgets\Forum\Thread;

require_once "./database/post.php";
require_once "./database/Read.php";

use \Web\Database\Read;
use \Web\Database\Count;

use function Web\Framework\Request\getSettings;
use function Web\Framework\Request\getPageIndex;

class Data extends \Web\Framework\Data\Widget
{
    protected $thread;

    protected $posts;

    public function __construct()
    {
        $this->thread = Read::thread(getThreadIndex());
        $this->posts = Read::postsFromThread($this->thread->getPrimaryKey());
    }

    public function getThread()
    {
        return $this->thread;
    }

    public function getPosts()
    {
        return $this->posts;
    }

    public function getPost()
    {
        return Read::forumPost(getPostIndex());
    }

    public function getMaxPosts()
    {
        $maxlim = ($this->postsPerPage() + $this->getStartOffset());
        $n_posts = $this->getThread()->getSize();
        $maxPosts = min($maxlim, $n_posts);

        return $maxPosts;
    }

    public function getStartOffset()
    {
        $n_postsPerPage = $this->postsPerPage();
        $start_offset = (getPageIndex() - 1) * $n_postsPerPage;

        return $start_offset;
    }

    public function postsPerPage()
    {
        return $_SESSION['settings']->value("posts_per_page");
    }

    public function getMaxPages()
    {
        return Count::maxPagesThread($this->thread);
    }

}