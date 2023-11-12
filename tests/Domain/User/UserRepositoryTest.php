<?php

namespace Domain\User;

use App\Domain\Auth\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase {

    public function testCount()
    {
        self::bootKernel();
        $users = self::getContainer()->get(UserRepository::class)->count();
        $this->assertEquals(25,$users);
    }

}