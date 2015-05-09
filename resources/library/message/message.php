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

    function get_messages($timestamp, $recipient_id = 0) {
        global $conn;

        $stmt = $conn->prepare("SELECT message FROM message WHERE (recipient_id=0 OR recipient_id=?) && timestamp >=$timestamp");
        $stmt->bind_param("i", $recipient_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$stmt) {
            return $conn->error;
        } else {
            $list = array();

            while($row = $result->fetch_assoc()) {
                $list[] = $row;
            }

            return $list;
        }
    }
}