<?php

namespace App\Dto\Request;

class CommentRequest
{
    public function __construct(
        public ?string $content = null,
        public ?int $postId = null,
    ) {
    }
}
