<?php

require_once("../resources/config.php");
require_once LIBRARY_PATH . "/user/user.php";

$request_legal = false;
$data = array();
$response = array();
$errors = array();

$user = new User();

$post_data = json_decode(file_get_contents('php://input'), true);

if (!empty($post_data["username"])) {
    if (strlen($post_data["username"]) < 3 || strlen($post_data["username"]) > 32) {
        $errors[] = "De gebruikersnaam moet tussend de 2 en 33 tekens lang zijn";
    } else {
        if ($user->user_exists($post_data["username"]) === false) {
            if($user->login($post_data["username"], $_SERVER['REMOTE_ADDR'])) {
                $request_legal = true;
                $data = array(
                    "username" => $user->username,
                    "user_id" => $user->user_id
                );
                $_SESSION["chat_user_id"] = $user->user_id;
            } else {
                $errors[] = "Er is een fout opgetreden bij het aanmaken van de gebruiker";
            }
        } else {
            $errors[] = "De gebruiker bestaat al";
        }
    }
} else {
    $errors[] = "Vul een gebruikersnaam in";
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