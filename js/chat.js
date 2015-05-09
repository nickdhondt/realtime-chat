"use strict";

var username, userId;

window.addEventListener("load", function() {init()});

function init() {
    document.getElementsByTagName("textarea")[0].addEventListener("keydown", function(e) {chat.keyDown(e)});
    user.isLoggedIn();
}

var xhr = xhr || {};

xhr = {
    sendRequest: function(action, message, url, callback) {
        var xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            var response;
            if (xhr.readyState === 4 && xhr.status === 200) {
                response = xhr.responseText;
                callback(response);
            } else if (xhr.readyState === 4 && xhr.status !== 200) {
                response = xhr.responseText;
                callback();
            }
        };

        xhr.onerror = function() {
            callback();
        };

        xhr.open(action, url);

        if (action === "post") {
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send(message);
        } else {
            xhr.send();
        }
    },
    requestSuccessful: function(response) {
        if (typeof response !== "undefined") {
            console.log(response);
            return true;
        } else {
            console.log("Fout");
            return false;
        }
    }
};

var chat = chat || {};

chat = {
    interpretSendMessageResponse: function(response) {
        if (xhr.requestSuccessful(response)) {
            console.log("Success");
        }
    },
    keyDown: function(e) {
        if (e.keyCode === 13 && e.shiftKey === false) {
            e.preventDefault();
            var chatTextarea = document.getElementsByTagName("textarea")[0];
            xhr.sendRequest("post", chatTextarea.value, "http/send_message.php", chat.interpretSendMessageResponse);
            chatTextarea.value = "";
        }
    }
};

var user = user || {};

user = {
    isLoggedIn: function() {
        xhr.sendRequest("get", "", "http/is_logged_in.php", user.interpretIsLoggedInResponse);
    },
    interpretIsLoggedInResponse: function(response) {
        if (xhr.requestSuccessful(response)) {
            console.log("Success");
        }
    },
    askUsername: function () {

    }
};

var stream = stream || {};

stream = {

};