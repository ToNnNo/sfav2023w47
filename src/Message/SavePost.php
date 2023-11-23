<?php

namespace App\Message;

use App\Entity\Post;

final class SavePost
{

    public function __construct(
        private readonly Post $post
    )
    {
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
