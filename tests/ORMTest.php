<?php

use PHPUnit\Framework\TestCase;

class ORMTest extends TestCase
{
    private $orm;

    protected function setUp(): void
    {
        $this->orm = new ChatORM();
    }

    public function testUserManagement()
    {
        $username = 'JohnDoe';
        $id = 1;

        $user = new ChatUser($username, $id);
        $response = $this->orm->addUser($user);
        $this->assertTrue($response['success']);

        $retrievedUser = $this->orm->getUserById($id);
        $this->assertTrue($retrievedUser['success']);
        $this->assertEquals($username, $retrievedUser['data']['username']);

        $users = $this->orm->getUsers();
        $this->assertCount(1, $users);
    }

    public function testRoomManagement()
    {
        $room_name = 'General';
        $id = 1;

        $room = new ChatRoom($room_name, $id);
        $response = $this->orm->addRoom($room);
        $this->assertTrue($response['success']);

        $retrievedRoom = $this->orm->getRoomByName($room_name);
        $this->assertTrue($retrievedRoom['success']);
        $this->assertEquals($room_name, $retrievedRoom['data']['name']);

        $rooms = $this->orm->getRooms();
        $this->assertCount(1, $rooms);
    }

    public function testMessageManagement()
    {
        $username = 'JohnDoe';
        $user_id = 1;
        $room_name = 'General';
        $room_id = 1;
        $message_content = 'Hello, world!';
        $message_id = 1;

        $user = new ChatUser($username, $user_id);
        $this->orm->addUser($user);

        $room = new ChatRoom($room_name, $room_id);
        $this->orm->addRoom($room);

        $message = new ChatMessage($user_id, $room_id, $message_content, $message_id);
        $response = $this->orm->addMessage($message);
        $this->assertTrue($response['success']);

        $retrievedMessages = $this->orm->getMessagesByRoomId($room_id);
        $this->assertTrue($retrievedMessages['success']);
        $this->assertCount(1, $retrievedMessages['data']);

        $lastMessage = $this->orm->getLastMessageByUserId($user_id);
        $this->assertInstanceOf(ChatMessage::class, $lastMessage);
        $this->assertEquals($message_content, $lastMessage->getContent());
    }
}