$(function () {
    function connectWebsocket() {
        //var webSocket = WS.connect((window.location.protocol === 'https:' ? 'wss://' : 'ws://') + window.location.host + '/wss/');
        var webSocket = WS.connect("ws://127.0.0.1:8080");

        webSocket.on("socket/connect", function (session) {
            // session is an Autobahn JS WAMP session.

            console.log("Successfully Connected!");
            session.subscribe("course/notification/channel/room/1", function (uri, payload) {
                //changeUserNotificationNumber();
                console.log('Notificare noua');
                console.log(payload);
            });
        });

        webSocket.on("socket/disconnect", function (error) {
            // error provides us with some insight into the disconnection: error.reason and error.code
        });
    }

    connectWebsocket();
});
