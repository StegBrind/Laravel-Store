const Websocket = require("websocket").server;
const http = require("http");
const events = require('./events');

const server = http.createServer((request, response) =>
{
    response.writeHead(200);
});

server.listen(8000, () =>
{
    console.log("Start listening on port 8000");
});

const WS = new Websocket(
    {
        httpServer: server,
        autoAcceptConnections: false,
        maxReceivedMessageSize: 20971520 /*20 MB*/,
        maxReceivedFrameSize: 20971520 /*20 MB*/
    });

WS.on("request", events.onRequest);