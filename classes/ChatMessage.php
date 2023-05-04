<?php

class ChatMessage
{
    private $msgId;
    private $roomId;
    private $content;
    private $userId;
    private $timeStamp;

    public function __construct($userId, $roomId, $content,$msgId)
    {

        if (strlen($content) < 2 || strlen($content) > 2048) {
            throw new \InvalidArgumentException("Message content must be between 2 and 2048 characters.");
        }

        $this->msgId = $msgId;
        $this->roomId = $roomId;
        $this->content = $content;
        $this->userId = $userId;
        $this->timeStamp = new DateTime();
    }

    public function getMsgId()
    {
        return $this->msgId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getRoomId()
    {
        return $this->roomId;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getTimeStamp()
    {
        return $this->timeStamp;
    }

    public function toArray()
    {
        return [
            'msgId' => $this->msgId,
            'userId' => $this->userId,
            'roomId' => $this->roomId,
            'content' => $this->content,
            'timeStamp' => $this->timeStamp->format(DateTime::ATOM),
        ];
    }
}