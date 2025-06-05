<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Repositories\UserRepository;

class UserRepositoryTest extends TestCase
{
    public function testGetAllUsers()
    {
        User::factory()->count(5)->create();
        $repository = new UserRepository();
        $users = $repository->all();
        $this->assertCount(5, $users);
    }

}