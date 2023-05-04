<?php

use PHPUnit\Framework\TestCase;

class RoomTest extends TestCase
{
    private $room;

    protected function setUp(): void
    {
        $this->room = new ChatRoom('General', 1);
    }

    public function testRoomCreation()
    {
        $id = 1;

        $this->assertEquals($id, $this->room->getId());
        $this->assertEquals('General', $this->room->getName());
    }

    public function testRoomNameChange()
    {
        $newName = 'Updated room';

        $this->room->setName($newName);

        $this->assertEquals($newName, $this->room->getName());
    }

    public function testRoomToArray()
    {
        $array = $this->room->toArray();

        $this->assertArrayHasKey('roomId', $array);
        $this->assertArrayHasKey('name', $array);

        $this->assertEquals(1, $array['roomId']);
        $this->assertEquals('General', $array['name']);
    }
}