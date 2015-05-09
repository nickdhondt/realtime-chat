<?php

require_once("../resources/config.php");
require_once LIBRARY_PATH . "/user/user.php";

$request_legal = false;
$data = array();
$response = array();
$errors = array();

$user = new User();

$user->is_logged_in();
$user->get_username();

if ($user->user_id !== false && $user->username !== false) {
    $request_legal = true;

    $data = array(
        "user_id" => $user->user_id,
        "username" => $user->username
    );
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