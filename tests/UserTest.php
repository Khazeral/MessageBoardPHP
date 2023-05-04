<?php

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        $username = 'JohnDoe';
        $id = 1;

        $this->user = new ChatUser($username, $id);
    }

    public function testUserCreation()
    {
        $username = 'JohnDoe';
        $id = 1;

        $user = new ChatUser($username, $id);

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($username, $user->getUsername());
    }

    public function testUsernameChange()
    {
        $newUsername = 'JaneDoe';

        $this->user->setUsername($newUsername);

        $this->assertEquals($newUsername, $this->user->getUsername());
    }

    public function testUserToArray()
    {
        $username = 'JohnDoe';
        $id = 1;

        $user = new ChatUser($username, $id);
        $array = $user->toArray();

        $this->assertArrayHasKey('userId', $array);
        $this->assertArrayHasKey('username', $array);

        $this->assertEquals($id, $array['userId']);
        $this->assertEquals($username, $array['username']);
    }
}

