<?php

namespace App\Domain\Auth\service;

use App\Domain\Auth\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function checkIfPasswordMatch(string $oldPassword,User $user):bool
    {
        return $this->passwordHasher->isPasswordValid($user,$oldPassword);
    }

    public function hashPassword(string $newPassword,User $user):string
    {
        return $this->passwordHasher->hashPassword($user,$newPassword);
    }

}