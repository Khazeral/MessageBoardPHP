<?php

use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testMessageCreation()
    {
        $userId = 1;
        $roomId = 1;
        $messageContent = 'Hello, world!';
        $messageId = 1;

        $message = new ChatMessage($userId, $roomId, $messageContent, $messageId);

        $this->assertEquals($messageId, $message->getMsgId());
        $this->assertEquals($userId, $message->getUserId());
        $this->assertEquals($roomId, $message->getRoomId());
        $this->assertEquals($messageContent, $message->getContent());
    }

    public function testMessageInvalidContentLength()
    {
        $this->expectException(\InvalidArgumentException::class);

        $userId = 1;
        $roomId = 1;
        $messageContent = 'H'; // Invalid content length (1 character)
        $messageId = 1;

        new ChatMessage($userId, $roomId, $messageContent, $messageId);
    }

    public function testMessageExceedsMaxLength()
    {
        $this->expectException(\InvalidArgumentException::class);

        $userId = 1;
        $roomId = 1;
        $messageContent = str_repeat('A', 2049); // Invalid content length (2049 characters)
        $messageId = 1;

        new ChatMessage($userId, $roomId, $messageContent, $messageId);
    }

    public function testMessageToArray()
    {
        $userId = 1;
        $roomId = 1;
        $messageContent = 'Hello, world!';
        $messageId = 1;

        $message = new ChatMessage($userId, $roomId, $messageContent, $messageId);
        $array = $message->toArray();

        $this->assertArrayHasKey('msgId', $array);
        $this->assertArrayHasKey('userId', $array);
        $this->assertArrayHasKey('roomId', $array);
        $this->assertArrayHasKey('content', $array);
        $this->assertArrayHasKey('timeStamp', $array);

        $this->assertEquals($messageId, $array['msgId']);
        $this->assertEquals($userId, $array['userId']);
        $this->assertEquals($roomId, $array['roomId']);
        $this->assertEquals($messageContent, $array['content']);
    }
}