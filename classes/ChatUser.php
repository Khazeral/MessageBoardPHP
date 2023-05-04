<?php

class ChatUser
{
    private $userId;
    private $username;

    public function __construct($username, $userId)
    {
        $this->userId = $userId;
        $this->username = $username;
    }

    public function getId()
    {
        return $this->userId;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function toArray()
    {
        return [
            'userId' => $this->userId,
            'username' => $this->username,
        ];
    }
}



