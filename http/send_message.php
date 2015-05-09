<?php

session_start();

require_once("../resources/config.php");
require_once LIBRARY_PATH . "/user/user.php";
require_once LIBRARY_PATH . "/message/message.php";

$request_legal = false;
$data = array();
$response = array();
$errors = array();

$user = new User();

$post_data = json_decode(file_get_contents('php://input'), true);

$user->is_logged_in();

if ($user->user_id !== false && $user->username !== false) {

    if (strlen($post_data["message"]) > 0) {
        $request_legal = true;
        $message = new message();

        $message->add_message($user->user_id, $post_data["message"]);
    }
} else {
    $errors[] = "Gebruiker niet ingelogd";
}

if ($request_legal === true) {
    $response = array(
        "request_legal" => $request_legal
    );
} else {
    $response = array(
        "request_legal" => $request_legal,
        "errors" => $errors
    );
}

echo json_encode($response);