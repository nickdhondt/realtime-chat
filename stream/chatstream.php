<?php

session_start();
header("Content-Type: text/event-stream\n\n");
header("Cache-Control: no-cache");

require_once("../resources/config.php");
require_once LIBRARY_PATH . "/user/user.php";
require_once LIBRARY_PATH . "/message/message.php";

$user_id = $_SESSION["chat_user_id"];

session_write_close();

$script_beginning = microtime(true);

$last_ping = 0;
$last_message = microtime(true);

ini_set('max_execution_time', 300);

$timestamp = 0;


ob_implicit_flush(true);
ob_end_flush();

$first_loop = true;

$message = new message();

while ($script_beginning >= (microtime(true) - 280)) {
    if ($last_ping < (microtime(true) - 5)) {
        echo "event: ping\n";
        echo "retry: 2000\n";
        $ping_event_time = json_encode(array("time" => microtime(true)));
        echo "data: " . $ping_event_time . "\n\n";

        $last_ping = microtime(true);
    }

    $new_messages = $message->get_messages($last_message);
    if (count($new_messages) > 0) {
        echo "event: message\n";
        echo "retry: 2000\n";
        $new_messages_json = json_encode($new_messages);
        echo "data: " . $new_messages_json . "\n\n";

        $last_message = microtime(true);
    }

    flush();

    if ($first_loop === true) {
        $first_loop = false;
    } else {
        usleep(250000);
    }
}