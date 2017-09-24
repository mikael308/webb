<?php

namespace Web\Pages\Post\Widgets\Edit_post;

require_once "./database/Read.php";
require_once "./database/post.php";

use Web\Database\Read;

class Data extends \Web\Framework\Data\Widget
{
    private $post;

    public function __construct()
    {
        $this->post = \Web\Database\Read::forumPost(getPostIndex());
        
        # post must exist or user have authority to edit
        if ($this->post == null || ! \Web\Database\editable($this->post)) {
            echo \Web\Helper\Message::error("could not update post");
        }
    }

    public function getPost()
    {
        return $this->post;
    }
}