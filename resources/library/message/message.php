<?php

require_once LIBRARY_PATH . "/connect/connect.php";

class message {
    function add_message($user_id, $message, $recipient_id = 0) {
        global $conn;

        $timestamp = microtime(true);

        $stmt = $conn->prepare("INSERT INTO message (user_id, message, timestamp, recipient_id) VALUES(?,?,?,?)");
        $stmt->bind_param("isdi", $user_id, $message, $timestamp, $recipient_id);
        $stmt->execute();

        if (!$stmt) {
            return $conn->error;
        } else {
            return true;
        }
    }
}