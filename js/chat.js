"use strict";

window.addEventListener("load", function() {init()});

function init() {
    document.getElementsByTagName("textarea")[0].addEventListener("keydown", function(e) {chat.keyDown(e)});
    user.isLoggedIn();
    application.preventFormSubmit();
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
    },
    parseJSON: function(jsonData) {
        var parsedResponse = null;
        try {
            parsedResponse = JSON.parse(jsonData);
        } catch (ex) {
            console.log("Er is een serverfout opgetreden, kan data niet verwerken: " + ex);
            console.log("Server meldt: " + jsonData);
        }

        return parsedResponse;
    }
};

var chat = chat || {};

chat = {
    interpretSendMessageResponse: function(response) {
        if (xhr.requestSuccessful(response)) {
            if (xhr.requestSuccessful(response)) {
                var parsedResponse = xhr.parseJSON(response);
            }
        }
    },
    keyDown: function(e) {
        if (e.keyCode === 13 && e.shiftKey === false) {
            e.preventDefault();
            var chatTextarea = document.getElementsByTagName("textarea")[0];
            var messageData = {
                "message": chatTextarea.value
            };
            xhr.sendRequest("post", JSON.stringify(messageData), "http/send_message.php", chat.interpretSendMessageResponse);
            chatTextarea.value = "";
        }
    }
};

var user = user || {};

user = {
    username: false,
    userId: false,
    isLoggedIn: function() {
        xhr.sendRequest("get", "", "http/is_logged_in.php", user.interpretIsLoggedInResponse);
    },
    interpretIsLoggedInResponse: function(response) {
        if (xhr.requestSuccessful(response)) {
            var parsedResponse = xhr.parseJSON(response);

            if (parsedResponse.request_legal === true) {
                console.log("Loggend in");
                application.disableEnterUsername();
                stream.openStream();
            } else {
                console.log("Not logged in");
                user.askUsername();
                application.enableEnterUsername();
            }
        }
    },
    askUsername: function () {
        var inputElements = document.getElementsByTagName("input");

        for(var i = 0; i < inputElements.length; i++) {
            console.log(inputElements[i].type);
            if (inputElements[i].type === "button") inputElements[i].addEventListener("click", function() {
                var usernameTextfield;

                for (var j = 0; j < inputElements.length; j++) {
                    if (inputElements[j].type === "text") usernameTextfield = inputElements[j];
                }
                var loginData = {
                    username: usernameTextfield.value
                };
                xhr.sendRequest("post", JSON.stringify(loginData), "http/login.php", user.interpretLoginResponse);
            });
        }
    },
    interpretLoginResponse: function(response) {
        if (xhr.requestSuccessful(response)) {
            var parsedResponse = xhr.parseJSON(response);

            if (parsedResponse.request_legal === true) {
                application.disableEnterUsername();
                stream.openStream();
            }
        }
    }
};

var application = application || {};

application = {
    enableEnterUsername: function() {
        var loginForm = document.getElementById("login");
        loginForm.style.display = "inline-flex";
    },
    disableEnterUsername: function() {
        var loginForm = document.getElementById("login");
        loginForm.style.display = "none";
    },
    preventFormSubmit: function() {
        var inputfields = document.getElementsByTagName("input");

        for(var i = 0; i < inputfields.length; i++) {
            inputfields[i].addEventListener("keypress", function(e) {
                if (e.keyCode === 13)
                    e.preventDefault()
            });
        }
    }
};

var stream = stream || {};

stream = {
    openStream: function () {
        var eventSource = new EventSource("stream/chatstream.php");

        eventSource.addEventListener("message", function(e) {
            var parsedResponse = xhr.parseJSON(e.data);
            var chatbox = document.getElementById("chatbox");

            chatbox.innerHTML += parsedResponse[0].message + "<br/>";
        }, false);
    }
};