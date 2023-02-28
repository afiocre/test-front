<?php

namespace App\Dto\Response;

class CommentResponse
{
    public int $id;
    public string $content;
    public UserResponse $createdBy;
}
