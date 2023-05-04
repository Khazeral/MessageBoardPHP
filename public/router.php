<?php
header('Content-Type: application/json');
$allowedOrigins = array(
    "http://localhost:8080",
    "http://localhost"
);
// Get the request's origin
$requestOrigin = $_SERVER['HTTP_ORIGIN'];
if (in_array($requestOrigin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $requestOrigin");
}
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once './classes/ChatORM.php';
require_once './classes/ChatUser.php';
require_once './classes/ChatRoom.php';
require_once './classes/ChatMessage.php';
session_start();

if (!isset($_SESSION['orm']) || $_SESSION['orm'] == null) {
    $_SESSION['orm'] = new ChatORM();
    $_SESSION['idusermanager'] = 0;
    $_SESSION['idroommanager'] = 0;
    $_SESSION['idmessagemanager'] = 0;
}
$orm = $_SESSION['orm'];
// Worst way to do it, but it works for the evaluation purpose
$usercounter = $_SESSION['idusermanager'];
$roomcounter = $_SESSION['idroommanager'];
$messagecounter = $_SESSION['idmessagemanager'];

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$response = ['success' => false, 'data' => null];

$param = strstr($uri, "?");
$param = substr($param, 1);

switch ($uri) {
    case '/':
        $response['success'] = true;
        $response['data'] = 'Welcome to the API';
        break;
    case '/users':
        if ($method == 'GET') {
            $response = $orm->getUsers();
        }
        break;
    case '/users?' . $param:
        if ($method == 'GET') {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $response = $orm->getUserById($id);
            } else if (isset($_GET['username'])) {
                $username = $_GET['username'];
                $response = $orm->getUserByUsername($username);
            } else {
                $response['data'] = "Missing id or username parameter";
                $response['success'] = false;
            }
        }
        break;
    case '/rooms':
        if ($method == 'GET') {
            $response = $orm->getRooms();
        }
        break;
    case '/rooms?' . $param:
        if ($method == 'GET') {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $response = $orm->getRoomById($id);
            } else if (isset($_GET['room_name'])) {
                $room_name = $_GET['room_name'];
                $response = $orm->getRoomByName($room_name);
            } else {
                $response['data'] = "Missing id or room_name parameter";
                $response['success'] = false;
            }
        }
        break;
    case '/messages?' . $param:
        if ($method == 'GET') {
            if (isset($_GET['id_room'])) {
                $id_room = $_GET['id_room'];
                $response = $orm->getMessagesByRoomId($id_room);
            } else {
                $response['data'] = "Missing id_room parameter";
                $response['success'] = false;
            }
        }
        break;
    case '/createuser':
        if ($method == 'POST') {
            $username = $_POST['username'];
            $user = new ChatUser($username, $usercounter);
            $response = $orm->addUser($user);
            $_SESSION['idusermanager'] = $usercounter + 1;
        }
        break;
    case '/createroom':
        if ($method == 'POST') {
            $room_name = $_POST['room_name'];
            $room = new ChatRoom($room_name, $roomcounter);
            $response = $orm->addRoom($room);
            $_SESSION['idroommanager'] = $roomcounter + 1;
        }
        break;
    case '/post-message':
        if ($method == 'POST') {
            $id_user = $_POST['user_id'];
            $id_room = $_POST['room_id'];
            $message_content = $_POST['content'];

            $message = new ChatMessage($id_user, $id_room, $message_content, $messagecounter);
            $response = $orm->addMessage($message);
            $_SESSION['idmessagemanager'] = $messagecounter + 1;
        }
        break;
    case '/disconnect':
        if ($method == 'GET') {
            session_destroy();
            $response['success'] = true;
            $response['data'] = 'Disconnected';
        }
        break;
    default:
        $response['success'] = false;
        $response['data'] = 'Invalid endpoint';
        break;
}

echo json_encode($response);