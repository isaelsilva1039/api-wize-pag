<?php

namespace App\DTO\User;


readonly class UserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $passwordConfirmation
    ) {}
}
