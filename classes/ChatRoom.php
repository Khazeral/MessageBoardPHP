<?php

class ChatRoom
{
    private $roomId;
    private $name;

    public function __construct($name, $roomId)
    {
        $this->roomId = $roomId;
        $this->name = $name;
    }

    public function getId()
    {
        return $this->roomId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function toArray()
    {
        return [
            'roomId' => $this->roomId,
            'name' => $this->name,
        ];
    }
}