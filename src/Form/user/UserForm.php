<?php

namespace App\Form\user;
use Symfony\Component\Validator\Constraints as Assert;

class UserForm
{
    #[Assert\Email]
    public string $email;

    #[Assert\Length(min: 5,max: 255)]
    public string $password;
}