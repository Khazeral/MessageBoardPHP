<?php
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
require_once './classes/ORM.php';
require_once './classes/User.php';
require_once './classes/Room.php';
require_once './classes/Message.php';

class FeatureContext implements Context
{
    private $orm;
    private $currentUser;

    public function __construct()
    {
        $this->orm = new ORM();
    }

    /**
     * @Given I am a new user
     */
    public function iAmANewUser()
    {
        $this->currentUser = null;
    }

    /**
     * @When I create a user with the name :arg1
     */
    public function iCreateAUserWithTheName($arg1)
    {
        $id = count($this->orm->getUsers());
        $this->currentUser = new User($arg1, $id);
        $this->orm->addUser($this->currentUser);
    }

    /**
     * @Then a user with the name :arg1 should exist
     */
    public function aUserWithTheNameShouldExist($arg1)
    {
        $response = $this->orm->getUserByUsername($arg1);
        if (!$response['success']) {
            throw new Exception("User not found");
        }
    } 

    /**
     * @Given I am a user
     */
    public function iAmAUser()
    {
        $this->currentUser = new User("test", 0);
        $this->orm->addUser($this->currentUser);
    }

    /**
     * @When I create a room with the name :arg1
     */
    public function iCreateARoomWithTheName($arg1)
    {
        $id = count($this->orm->getRooms());
        $room = new Room($arg1, $id);
        $this->orm->addRoom($room);
    }

    /**
     * @Then a room with the name :arg1 should exist
     */
    public function aRoomWithTheNameShouldExist($arg1)
    {
        $response = $this->orm->getRoomByName($arg1);
        if (!$response['success']) {
            throw new Exception("Room not found");
        }
    }

    /**
     * @Given I am a user named :arg1
     */
    public function iAmAUserNamed($arg1)
    {
        $this->currentUser = new User($arg1, 0);
        $this->orm->addUser($this->currentUser);
    }

    /**
     * @Given a room named :arg1 exists
     */
    public function aRoomNamedExists($arg1)
    {
        $id = 5;
        $room = new Room($arg1, $id);
        $this->orm->addRoom($room);
    }

    /**
     * @When I post a message :arg1 in the room :arg2
     */
    public function iPostAMessageInTheRoom($arg1, $arg2)
    {
        $room = $this->orm->getRoomByName($arg2)['data'];
        $id = count($this->orm->getMessagesByRoomId($room['id']));
        $message = new Message($this->currentUser->getId(), $room['id'], $arg1, $id);
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
        foreach ($messages['data'] as $message) {
            if ($message['content'] == $arg1) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            throw new Exception("Message not found");
        }
    }

    /**
     * @Given I am a user2
     */
    public function iAmAUser2()
    {
        $this->currentUser = new User("test2", 0);
        $this->orm->addUser($this->currentUser);
    }

    /**
     * @Given a room named :arg1 exists with the following messages:
     */
    public function aRoomNamedExistsWithTheFollowingMessages($arg1, TableNode $table)
    {
        $id = count($this->orm->getRooms()) + 1;
        $room = new Room($arg1, $id);
        $this->orm->addRoom($room);
        foreach ($table as $row) {
            $user = $this->orm->addUser(new User($row['user'], count($this->orm->getUsers())+1));
            $message = new Message($user['data']['id'], $id, $row['message'], count($this->orm->getMessagesByRoomId($id)));
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
        foreach ($table as $row) {
            if ($messages['data'][$i]['content'] != $row['message'] || $messages['data'][$i]['user'] != $row['user']) {
                throw new Exception("Messages not in order");
            }
            $i++;
        }
    }
}