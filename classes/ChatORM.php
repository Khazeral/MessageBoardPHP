<?php

class ChatORM
{
    private $chatRooms;
    private $chatMessages;
    private $chatUsers;
    public $chatResponse = ['success' => false, 'data' => null];

    public function __construct()
    {
        $this->chatUsers = [];
        $this->chatRooms = [];
        $this->chatMessages = [];
    }

    // User-related methods
    public function getUsers()
    {
        if (count($this->chatUsers) == 0) {
            $chatResponse['data'] = "No users found";
            $chatResponse['success'] = false;
            return $chatResponse;
        }
        return array_map(function ($user) {
            $chatResponse['data'] = $user->toArray();
            $chatResponse['success'] = true;
            return $chatResponse;
        }, $this->chatUsers);
    }

    public function getUserById($id)
    {
        foreach ($this->chatUsers as $user) {
            if ($user->getId() == $id) {
                $chatResponse['data'] = $user->toArray();
                $chatResponse['success'] = true;
                return $chatResponse;
            }
        }
        $chatResponse['data'] = "User not found";
        $chatResponse['success'] = false;
        return $chatResponse;
    }

    public function getUserByUsername($username)
    {
        foreach ($this->chatUsers as $user) {
            if ($user->getUsername() == $username) {
                $chatResponse['data'] = $user->toArray();
                $chatResponse['success'] = true;
                return $chatResponse;
            }
        }
        $chatResponse['data'] = "User not found";
        $chatResponse['success'] = false;
        return $chatResponse;
    }

    public function addUser($user)
    {
        foreach ($this->chatUsers as $baseuser) {
            if ($baseuser->getUsername() == $user->getUsername()) {
                $chatResponse['data'] = "Username already exist";
                $chatResponse['success'] = false;
                return $chatResponse;
            }
        }
        $this->chatUsers[] = $user;
        $chatResponse['data'] = $user->toArray();
        $chatResponse['success'] = true;
        return $chatResponse;
    }

    // Room-related methods
    public function getRooms()
    {
        if (count($this->chatRooms) == 0) {
            $chatResponse['data'] = "No rooms found";
            $chatResponse['success'] = false;
            return $chatResponse;
        }
        return array_map(function ($room) {
            $chatResponse['data'] = $room->toArray();
            $chatResponse['success'] = true;
            return $chatResponse;
        }, $this->chatRooms);
    }

    public function getRoomByName($name)
    {
        foreach ($this->chatRooms as $room) {
            if ($room->getName() == $name) {
                $chatResponse['data'] = $room->toArray();
                $chatResponse['success'] = true;
                return $chatResponse;
            }
        }
        $chatResponse['data'] = "Room not found";
        $chatResponse['success'] = false;
        return $chatResponse;
    }

    public function getRoomById($id)
    {
        foreach ($this->chatRooms as $room) {
            if ($room->getId() == $id) {
                $chatResponse['data'] = $room->toArray();
            $chatResponse['success'] = true;
            return $chatResponse;
        }
    }
    $chatResponse['data'] = "Room not found";
    $chatResponse['success'] = false;
    return $chatResponse;
}

public function addRoom(ChatRoom $room)
{
    if ($this->getRoomByName($room->getName())['success'] == true) {
        $chatResponse['data'] = "A room with the same name already exists.";
        $chatResponse['success'] = false;
        return $chatResponse;
    }
    $this->chatRooms[] = $room;
    $chatResponse['data'] = $room->toArray();
    $chatResponse['success'] = true;
    return $chatResponse;
}

// Message-related methods
public function getMessagesByRoomId($roomId)
{
    if ($this->getRoomById($roomId)['success'] == false) {
        return $this->getRoomById($roomId);
    }
    $roomMessages = [];
    foreach ($this->chatMessages as $message) {
        if ($message->getRoomId() == $roomId) {
            $roomMessages[] = $message->toArray();
        }
    }

    usort($roomMessages, function ($a, $b) {
        return $a['timeStamp'] <=> $b['timeStamp'];
    });

    $chatResponse['data'] = $roomMessages;
    $chatResponse['success'] = true;
    return $chatResponse;
}


public function addMessage(ChatMessage $message)
{
    if ($this->getUserById($message->getUserId())['success'] == false) {
        return $this->getUserById($message->getUserId());
    }
    if ($this->getRoomById($message->getRoomId())['success'] == false) {
        return $this->getRoomByName($message->getRoomId());
    }
    $lastMessage = $this->getLastMessageByUserId($message->getUserId());
    if ($lastMessage !== null) {
        $lastMessageTimestamp = $lastMessage->getTimeStamp();
        $interval = $lastMessageTimestamp->diff($message->getTimeStamp());
        if ($interval->days == 0 && $interval->h == 0 && $interval->i < 24 && $lastMessage === end($this->chatMessages) && $lastMessage->getRoomId() === $message->getRoomId()) {
            $chatResponse['data'] = "You cannot post two consecutive messages within 24 hours in the same room.";
            $chatResponse['success'] = false;
            return $chatResponse;
        }
    }
    $this->chatMessages[] = $message;
    $chatResponse['data'] = $message->toArray();
    $chatResponse['success'] = true;
    return $chatResponse;
}



public function getLastMessageByUserId($userId)
{
    $lastMessage = null;
    if ($this->getUserById($userId)['success'] == false) {
        return $this->getUserById($userId);
    }
    foreach ($this->chatMessages as $message) {
        if ($message->getUserId() == $userId) {
            if ($lastMessage === null || $message->getTimeStamp() > $lastMessage->getTimeStamp()) {
                $lastMessage = $message;
            }
        }
    }
    return $lastMessage;
}

}