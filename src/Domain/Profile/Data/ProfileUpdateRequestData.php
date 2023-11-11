<?php

namespace App\Domain\profile\Dto;

use App\Domain\Auth\User;
use App\validator\Unique;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;


#[Unique(field: 'email', entityClass: User::class)]
class ProfileUpdateRequestData
{

    #[Assert\NotBlank]
    #[Assert\Email(message: "L'email ne correspond pas")]
    public string $email;

    #[Assert\Length(min: 5,max: 40)]
    public string $password;
    #[SecurityAssert\UserPassword(message: 'Le mot de passe ne correspond pas')]
    public string $oldPassword;

    public ?File $avatar = null;

    public ?string $filename = null;

    public User $user;

    public function __construct(User $user)
    {
        $this->email = $user->getEmail();
        $this->filename = $user->getFilename();
        $this->imageFile = $user->getFilename();
        $this->user = $user;
    }

    public function hydrate()
    {
        $this->user->setEmail($this->email);
        $this->user->setAvatar($this->avatar);
    }

    public function getId():int
    {
        return $this->user->getId();
    }
}