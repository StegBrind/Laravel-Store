/**
 * Server Config
 */

require("dotenv").config({ path:"./.env" });
const Websocket = require("websocket").server;
const http = require("http");
const mysql = require("mysql");

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
        autoAcceptConnections: false
    });

const mysql_config =
{
    host : process.env.DB_HOST,
    user: process.env.DB_USERNAME,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE
};

let conversations = {};

/**
 * Connections Handling
 */

WS.on("request", async request =>
{
    const connection = request.accept("", request.origin);
    let conversation_num = GETParameter(request.resource, "companions");
    let user_ids_arr = conversation_num.split("-");

    async function identifyUser(user_ids, token)
    {
        let mysql_conn = mysql.createConnection(mysql_config);
        let identified;
        let sync_call = () =>
        {
            return new Promise((resolve) =>
            {
                mysql_conn.query(
                    "SELECT token, user_ids FROM conversations WHERE user_ids = '" + user_ids[0] + "-" + user_ids[1] + "' OR user_ids = '" + user_ids[1] + "-" + user_ids[0] + "'",
                    function (error, results, fields)
                    {
                        conversation_num = results[0]['user_ids'];
                        try
                        {
                            if ((identified = results[0]['token'] == token))
                            {
                                mysql_conn.query(
                                    "UPDATE conversations SET token = '" + generateToken() + "' WHERE user_ids = '" + conversation_num + "'"
                                );
                            }
                        }
                        catch (e)
                        {
                            identified = false;
                        }
                        resolve();
                    });
            });
        };
        await sync_call();
        mysql_conn.end();
        return identified;
    }

    if (! await identifyUser(user_ids_arr, GETParameter(request.resource, "token")))
    {
        connection.close();
        console.log("User " + connection.remoteAddress + " couldn't connect due to bad identification");
        return;
    }
    if (conversations[conversation_num] === undefined)
    {
        console.log("User " + user_ids_arr[0] + " has created conversation " + conversation_num);
        conversations[conversation_num] = {};
        conversations[conversation_num][user_ids_arr[0]] = connection;
        conversations[conversation_num][user_ids_arr[1]] = undefined;
    }
    else
    {
        console.log("User " + user_ids_arr[0] + " has connected to conversation " + conversation_num);
        conversations[conversation_num][user_ids_arr[0]] = connection;
    }

    console.log("New client connected: " + connection.remoteAddress);

    connection.on("message", message =>
    {
        let msg = {};
        msg[user_ids_arr[0]] = message[message.type + "Data"].split("\n").join("<br>");
        msg["t"] = Date.now();
        if (conversations[conversation_num][user_ids_arr[1]] !== undefined)
            conversations[conversation_num][user_ids_arr[1]].send(msg[user_ids_arr[0]]);
        let mysql_conn = mysql.createConnection(mysql_config);
        mysql_conn.query(
            "UPDATE conversations SET messages = CONCAT(messages, '" + JSON.stringify(msg) + "\n') WHERE user_ids = '" + conversation_num + "'"
        );
        mysql_conn.end();
    });

    connection.on("close", (reasonCode) =>
    {
        if (conversations[conversation_num][user_ids_arr[1]] === undefined)
        {
            delete conversations[conversation_num];
            console.log("Conversation " + conversation_num + " has been deleted");
        }
        else
        {
            delete conversations[conversation_num][user_ids_arr[0]];
            console.log("User " + user_ids_arr[0] + " has left conversation " + conversation_num);
        }
        console.log("Client has disconnected: " + connection.remoteAddress + " | Reason code: " + reasonCode);
    });
});

/**
 * Custom Functions
 */

function GETParameter(uri, get_parameter)
{
    return decodeURIComponent((new RegExp('[?&]'+encodeURIComponent(get_parameter)+'=([^&]*)')).exec(uri)[1]);
}

function generateToken()
{
    let token = '';
    let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    for (let i = 0; i < 50; i++ ) token += characters.charAt(Math.floor(Math.random() * 62));
    return token;
}