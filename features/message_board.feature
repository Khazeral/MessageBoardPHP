Feature: Message Board
  As a user
  I want to be able to post messages and see them in rooms

  Scenario: Create a user
    Given I am a new user
    When I create a user with the name "John"
    Then a user with the name "John" should exist

  Scenario: Create a room
    Given I am a user
    When I create a room with the name "Room1"
    Then a room with the name "Room1" should exist

  Scenario: Post a message in a room
    Given I am a user named "John"
    And a room named "Room1" exists
    When I post a message "Hello, everyone!" in the room "Room1"
    Then the message "Hello, everyone!" should be visible in the room "Room1"

  Scenario: Read messages in a room
    Given I am a user2
    And a room named "Room1" exists with the following messages:
      | user     | message               |
      | John     | Hello, everyone!      |
      | Dylan    | Hi John!              |
    When I visit the room "Room1"
    Then I should see the following messages in order:
      | user     | message               |
      | John     | Hello, everyone!      |
      | Dylan    | Hi John !             |
