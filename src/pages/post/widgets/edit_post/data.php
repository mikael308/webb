<?php

namespace Widgets\Post\Edit_post;

require_once "./database/Read.php";
require_once "./database/post.php";

use Database\Read;

class Data extends \Framework\Data\Widget
{
    private $post;

    public function __construct()
    {
        $this->post = \Database\Read::forumPost(getPostIndex());
        
        # post must exist or user have authority to edit
        if ($this->post == null || ! \Database\editable($this->post)) {
            echo \Helper\Message::error("could not update post");
        }
    }

    public function getPost()
    {
        return $this->post;
    }
}