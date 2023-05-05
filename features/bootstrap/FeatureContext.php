<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;

require_once './classes/ChatORM.php';
require_once './classes/ChatUser.php';
require_once './classes/ChatRoom.php';
require_once './classes/ChatMessage.php';

class FeatureContext implements Context
{
    private ChatORM $orm;
    private ?ChatUser $currentUser;

    public function __construct()
    {
        $this->orm = new ChatORM();
        $this->currentUser = null;
    }

    /**
     * @Given I am a new user
     */
    public function iAmANewUser(): void
    {
        $this->currentUser = null;
    }

    /**
     * @When I create a user with the name :arg1
     */
    public function iCreateAUserWithTheName(string $username): void
    {
        $id = count($this->orm->getUsers());
        $this->currentUser = new ChatUser($username, $id);
        $this->orm->addUser($this->currentUser);
    }

    /**
     * @Then a user with the name :arg1 should exist
     */
    public function aUserWithTheNameShouldExist(string $username): void
    {
        $response = $this->orm->getUserByUsername($username);
        if (!$response['success']) {
            throw new Exception("User not found");
        }
    }

    /**
     * @Given I am a user
     */
    public function iAmAUser(): void
    {
        $this->currentUser = new ChatUser("test", 0);
        $this->orm->addUser($this->currentUser);
    }

    /**
     * @When I create a room with the name :arg1
     */
    public function iCreateARoomWithTheName(string $roomName): void
    {
        $id = count($this->orm->getRooms());
        $room = new ChatRoom($roomName, $id);
        $this->orm->addRoom($room);
    }

    /**
     * @Then a room with the name :arg1 should exist
     */
    public function aRoomWithTheNameShouldExist(string $roomName): void
    {
        $response = $this->orm->getRoomByName($roomName);
        if (!$response['success']) {
            throw new Exception("Room not found");
        }
    }

    /**
     * @Given I am a user named :arg1
     */
    public function iAmAUserNamed(string $username): void
    {
        $this->currentUser = new ChatUser($username, 0);
        $this->orm->addUser($this->currentUser);
    }

    /**
     * @Given a room named :arg1 exists
     */
    public function aRoomNamedExists(string $roomName): void
    {
        $id = 5;
        $room = new ChatRoom($roomName, $id);
        $this->orm->addRoom($room);
    }

    /**
     * @When I post a message :arg1 in the room :arg2
     */
    public function iPostAMessageInTheRoom($arg1, $arg2): void
    {
        $room = $this->orm->getRoomByName($arg2)['data'];
        $id = 0;
        $message = new ChatMessage($this->currentUser->getUsername(), $room, $arg1, $id);
        $this->orm->addMessage($message);
    }


    /**
     * @Then the message :arg1 should be visible in the room :arg2
     */
    public function theMessageShouldBeInTheRoom($arg1, $arg2)
    {
        $room = $this->orm->getRoomByName($arg2)['data'];
        $messages = $this->orm->getMessagesByRoomId($room["id"]);
        $found = false;
        foreach ($messages as $message) {
            if ($message == $arg1) {
                $found = true;
            }
        }
        if ($found == false) {
            throw new Exception("Message not found");
        }
    }

    /**
     * @Given I am a user2
     */
    public function iAmAUser2()
    {
        $this->currentUser = new ChatUser("test2", 0);
        $this->orm->addUser($this->currentUser);
    }

    /**
     * @Given a room named :arg1 exists with the following messages:
     */
    public function aRoomNamedExistsWithTheFollowingMessages($arg1, TableNode $table)
    {
        $id = 5;
        $room = new ChatRoom($arg1, $id);
        $this->orm->addRoom($room);
        foreach ($table->getRows() as $row) {
            $user = $this->orm->addUser(new ChatUser($row[0], count($this->orm->getUsers())+1));
            $message = new ChatMessage($user['data']['id'], $id, $row['message'], count($this->orm->getMessagesByRoomId($id))+1);
            $this->orm->addMessage($message);
        }
    }

    /**
     * @When I visit the room :arg1
     */
    public function iVisitTheRoom($arg1)
    {
        $this->orm->getRoomByName($arg1);
    }

    /**
     * @Then I should see the following messages in order:
     */
    public function iShouldSeeTheFollowingMessagesInOrder(TableNode $table)
    {
        $room = $this->orm->getRoomByName($table->getRows()[0][0])['data'];
        $messages = $this->orm->getMessagesByRoomId($room['id']);
        $i = 0;
        foreach ($table->getRows() as $row) {
            if ($messages['data'][$i]['content'] != $row[1] || $messages['data'][$i]['user'] != $row[0]) {
                throw new Exception("Messages not in order");
            }
            $i++;
        }
    }
}