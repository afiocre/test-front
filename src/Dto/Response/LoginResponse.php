<?php

namespace App\Dto\Response;

class LoginResponse
{
    public string $token;
    public UserResponse $user;
}
