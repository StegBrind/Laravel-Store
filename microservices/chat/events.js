const extras = require('./extras.js');
const fs = require('fs');

let conversations = {};

module.exports = {
    onRequest: async function (request) {
        const connection = request.accept('', request.origin);
        let conversation_num = extras.GETParameter(request.resource, "companions");
        let user_ids_arr = conversation_num.split("-");
        /**
         * user_ids_arr[0] who is sending messages
         * user_ids_arr[1] who is receiving messages
         * length of user_ids_arr must be 2
         */
        if (user_ids_arr.length != 2) {
            connection.close();
            return;
        }

        if (! await identifyUser(user_ids_arr, extras.GETParameter(request.resource, "token")))
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

        /**
         * Connection events
         */

        /**
         * OnMessage
         */
        connection.on("message", message =>
        {
            let msg = message[message.type + "Data"];
            try {
                msg = JSON.parse(msg);
            }
            catch (e) {
                connection.close();
                return;
            }

            if (msg.text === undefined || msg.text == '')
            {
                connection.close();
                return;
            }

            msg.text = msg.text.split("\n").join("<br>");

            if (msg.files !== undefined)
            {
                handleMessageFile(msg);
                return;
            }

            if (conversations[conversation_num][user_ids_arr[1]] !== undefined)
                conversations[conversation_num][user_ids_arr[1]].send(JSON.stringify([msg.text]));

            extras.sendMySQL("UPDATE conversations SET messages = JSON_ARRAY_APPEND(messages, '$', JSON_ARRAY(" + user_ids_arr[0] + ", \"" + msg.text + "\", " + Date.now() +
                ")) WHERE user_ids = '" + conversation_num + "'");
        });

        /**
         * OnClose
         */
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

        /**
         * Other functions
         */
        async function identifyUser (user_ids, token) {
            let identified;
            let mysql_conn = extras.mysql.createConnection(extras.mysql_config);
            await (() => {
                return new Promise(resolve =>
                {
                    mysql_conn.query(
                    "SELECT token, user_ids FROM conversations WHERE user_ids = '" + user_ids[0] + "-" + user_ids[1] + "' OR user_ids = '" + user_ids[1] + "-" + user_ids[0] + "'",
                    (error, results, fields) =>
                    {
                        conversation_num = results[0]['user_ids'];
                        try {
                            if ((identified = results[0]['token'] == token))
                                extras.sendMySQL(
                                    "UPDATE conversations SET token = '" + extras.generateToken() + "' WHERE user_ids = '" + conversation_num + "'");
                        }
                        catch (e) {
                            identified = false;
                        }
                        resolve();
                    });
                });
            })();
            mysql_conn.end();
            return identified;
        }
        
        function handleMessageFile(message) {
            const path = "../../storage/app/private/conversations_content/" + conversation_num;
            let filesList = '';
            if (!fs.existsSync(path))
                fs.mkdirSync(path);

            for (let [file_name, content] of Object.entries(message.files))
            {
                file_name = process.hrtime.bigint() + "_" + file_name;
                fs.writeFile(
                    path + "/" + file_name, content, err => {
                        if (err) throw err;
                    });
                filesList += '\n/conversation/content/' + conversation_num + '/' + file_name;
            }
            filesList = filesList.slice(1); //first \n is unnecessary

            if (conversations[conversation_num][user_ids_arr[1]/*user id who is receiving*/] !== undefined)
                conversations[conversation_num][user_ids_arr[1]].send(JSON.stringify([message.text, filesList]));

            extras.sendMySQL("UPDATE conversations SET messages = JSON_ARRAY_APPEND(messages, '$', JSON_ARRAY(" + user_ids_arr[0] + ", \"" + message.text + "\", " + Date.now() +
                ", \"" + filesList + "\")) WHERE user_ids = '" + conversation_num + "'"
            );

            connection.close(1000, 'Files transferred'); //files successfully transferred
        }
    }
};