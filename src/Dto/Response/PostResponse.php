<?php

namespace App\Dto\Response;

class PostResponse
{
    public int $id;
    public string $title;
    public string $content;
    public UserResponse $createdBy;
    /** @var CommentResponse[] */
    public array $comments;
}
