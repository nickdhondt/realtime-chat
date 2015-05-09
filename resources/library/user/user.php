<?php

require_once LIBRARY_PATH . "/connect/connect.php";

class User {
    public $username;
    public $user_id;

    public function is_logged_in() {
        if (!empty($_SESSION["chat_user_id"])) {
            $user_id = $_SESSION["chat_user_id"];
        } else {
            $user_id = "";
        }

        if (!empty($user_id)) {
            $this->user_id = $user_id;
        } else {
            $this->user_id = false;
        }
    }

    public function get_username() {
        if ($this->user_id !== false) {
            global $conn;

            $stmt = $conn->query("SELECT username FROM user WHERE user_id=$this->user_id");

            $row = $stmt->fetch_assoc();

            $this->username = $row["username"];
        } else {
            $this->username = false;
        }
    }

    public function user_exists($username) {
        global $conn;

        $stmt = $conn->prepare("SELECT user_id FROM user WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if(!$result) {
            return $conn->error;
        } else {
            $matches_found = 0;

            while($row = $result->fetch_assoc()) {
                $matches_found++;
            }

            if($matches_found > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function login($username, $ip) {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO user (username, ip) VALUES(?, ?)");
        $stmt->bind_param("ss", $username, $ip);
        $stmt->execute();

        if (!$stmt) {
            return $conn->error;
        } else {
            $this->user_id = $stmt->insert_id;
            $this->username = $username;
            return true;
        }
    }
}
