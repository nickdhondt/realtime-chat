<?php

$config = array(
    "host" => "localhost",
    "username" => "root",
    "password" => "",
    "dbname" => "chat"
);

$conn = new mysqli($config["host"], $config["username"], $config["password"], $config["dbname"]);

if (!$conn) {
    die("Verbinding met database mislukt: " . $conn->connect_errno);
}