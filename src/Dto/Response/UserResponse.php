<?php

namespace App\Dto\Response;

class UserResponse
{
    public int $id;
    public string $firstname;
    public string $email;
    /** @var string[] */
    public array $roles;
}
