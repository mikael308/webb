<?php
namespace Widgets\Forum\Forum\Thread;

require_once "./database/post.php";
require_once "./database/Read.php";

use \Database\Post;

class Data extends \Framework\Data\Widget
{
    protected $thread;

    protected $posts;

    public function __construct()
    {
        $this->thread = \Database\Read::thread(getThreadIndex());
        $this->posts = \Database\Read::postsFromThread($this->thread->getPrimaryKey());
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
        return \Database\Read::post(getPostIndex());
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
        return \Database\Count::maxPagesThread($this->thread);
    }

}