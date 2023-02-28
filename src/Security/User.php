<?php

namespace App\Security;

use App\Dto\Response\LoginResponse;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private int $id;
    private string $email;
    private string $firstname;
    /** @var string[] */
    private array $roles;
    private string $token;

    /**
     * @param string[] $roles
     */
    public function __construct(int $id, string $email, string $firstname, array $roles, string $token)
    {
        $this->id = $id;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->roles = $roles;
        $this->token = $token;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstname;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public static function createFromLoginResponse(LoginResponse $loginResponse): self
    {
        return new self(
            $loginResponse->user->id,
            $loginResponse->user->email,
            $loginResponse->user->firstname,
            $loginResponse->user->roles,
            $loginResponse->token
        );
    }
}
