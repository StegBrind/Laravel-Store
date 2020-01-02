<template>
    <div class="col-sm-3 col-sm-offset-4 frame" style="max-width: 400px; height: 620px">
        <h4 ref="warning_message" style="margin-bottom: 30px"></h4>
        <div class="messages" ref="chatBody">
            <ul v-html="messagesHtml"></ul>
        </div>
        <div class="msj-rta macro" style="margin-left:10px;width:380px">
            <div class="text text-r" style="background:whitesmoke !important;display:inline-block;text-align:justify">
                <textarea class="my_message" ref="my_message" placeholder="Вводите сообщение"></textarea>
                <input type="file" multiple v-on:change="handleFiles()" ref="attached_files" id="files" class="fileAttach">
                <label for="files" class="fileLabel">
                    <svg version="1.1" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve"><g><g transform="translate(0.000000,511.000000) scale(0.100000,-0.100000)"><path d="M7167.6,4994c-279-33.4-542.2-131.6-787.8-294.7c-88.4-57-941.1-897.9-2807.5-2766.2C1311-332.1,870.9-780.1,770.7-929.4c-190.6-280.9-286.8-495.1-373.3-825.1C330.6-2017.8,313-2452,358.1-2731c135.6-827.1,677.8-1526.5,1440.1-1860.5c772.1-335.9,1705.3-239.7,2400.8,245.6c149.3,106.1,5389,5326.2,5438.2,5420.5c64.8,123.8,19.6,290.8-98.2,365.4c-49.1,29.5-86.4,37.3-167,31.4l-106.1-7.9L6611.6-1188.8c-1750.5-1746.6-2695.5-2677.8-2770.2-2725c-778-502.9-1760.3-406.7-2400.8,233.8c-544.2,542.2-705.3,1351.7-414.5,2066.8c141.5,347.8,84.5,286.8,2952.9,3161.2c2508.9,2516.7,2675.9,2679.8,2805.5,2744.6c245.6,121.8,328.1,141.5,589.4,139.5c204.3,0,255.4-7.9,392.9-55c477.4-165,864.5-581.5,980.4-1055c39.3-161.1,39.3-420.4,2-585.5c-17.7-68.8-62.9-188.6-102.2-265.2c-66.8-127.7-261.3-328.1-2438.1-2520.7c-1300.6-1310.4-2424.4-2436.2-2495.1-2503c-233.8-218.1-469.6-277-707.3-174.9c-225.9,98.2-375.2,345.8-351.7,589.4c21.6,239.7-41.3,169,1760.3,1974.5C5332.7,756.3,6093,1526.4,6104.8,1550c11.8,23.6,21.6,82.5,21.6,131.6c0,169-182.7,300.6-347.7,251.5c-60.9-17.7-394.9-343.8-1776-1726.9C2442.6-1357.7,2297.3-1510.9,2232.4-1636.7c-108.1-210.2-139.5-355.6-127.7-599.2c5.9-141.5,21.6-243.6,49.1-322.2c123.8-341.9,385.1-601.2,730.9-721c102.2-35.4,172.9-45.2,343.8-45.2c267.2-2,440.1,47.2,648.3,186.6c200.4,135.6,5029.5,4978.5,5163.1,5178.9c214.2,320.2,292.7,581.5,294.7,968.6c2,326.1-41.3,524.6-180.8,815.3c-308.4,644.4-923.4,1092.4-1599.2,1167C7348.4,5015.6,7354.3,5015.6,7167.6,4994z"/></g></g></svg>
                </label>
                <small ref="filesList" style="width: 100%"></small>
                <br>
                <button class="btn btn-dark" style="margin-top: 10px" ref="send_message_btn" @click="sendMessage()">Отправить</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ConversationComponent",
        data: function () {
            return {
                messagesHtml: "",
                WS: new WebSocket("ws://" + document.location.host + ":8000/chat?companions=" + this.companion_ids + "&token=" + this.token),
                attached_files: undefined
            };
        },
        props: {
            historyConversationData: "",
            companion_ids: "",
            companion_name: "",
            token: ""
        },
        mounted() {
            this.WS.onmessage = this.onMessage;
            this.WS.onclose = this.onClose;
            this.historyMessagesToHTML();
        },
        updated() {
            // scroll chat to bottom
            this.$refs['chatBody'].scrollTo(0, this.$refs['chatBody'].scrollHeight);
        },
        methods: {
            historyMessagesToHTML: function () {
                const messages = JSON.parse(this.historyConversationData);
                if (messages.length != 0)
                {
                    const me = this.companion_ids.split("-")[0];
                    for (let i = 0; i < messages.length; i++)
                    {
                        if (messages[i][0]/*user id*/ == me)
                            this.insertChat("me", messages[i][1]/*message_text*/, new Date(messages[i][2]/*message_time*/), messages[i][3]/*file_paths*/);
                        else
                            this.insertChat("not me", messages[i][1]/*message_text*/, new Date(messages[i][2]/*message_time*/), messages[i][3]/*file_paths*/);
                    }
                }
            },
            onClose: function (event) {
                if (event.reason == 'Files transferred') {
                    this.warningMessage('Файлы успешно доставлены.', 'green');
                    document.location.reload();
                    return;
                }
                if (event.code != 1000)
                    this.warningMessage('Соединение с сервером разъединено.<br>Попробуйте перезагрузить страницу.', 'red');
            },
            onMessage: function (event) {
                let data = JSON.parse(event.data);

                this.insertChat("not me", data[0]/*text*/, new Date(), data[1]/*file_paths*/);
            },
            sendMessage: function () {
                let text = this.$refs["my_message"].value.split("\n").join("<br>");
                if (text.trim() !== '')
                {
                    let json_message = {text: text};
                    if (this.attached_files != undefined)
                    {
                        json_message.files = this.attached_files;
                        this.attached_files = undefined;
                        this.blockControls();
                        this.warningMessage('Файлы отправляются... <img src="/img/loading_files.gif" width="20px" height="20px">');
                        this.WS.send(JSON.stringify(json_message));
                        return;
                    }
                    this.WS.send(JSON.stringify(json_message));
                    this.insertChat("me", text);
                    this.$refs["my_message"].value = '';
                }
            },
            insertChat: function (who = "not me", text, date = new Date(), files = undefined) {
                let filesHTML = '';
                if (files !== undefined) filesHTML = this.filePathsToHTML(files);

                if (who == "me")
                {
                    this.messagesHtml += '<li style="margin-bottom:20px">' +
                        '<div class="msj-rta macro">' +
                        '<div class="text text-l">' +
                        '<p>' + this.escapeHtml(text) + '</p>' +
                        filesHTML +
                        '<p><small>' + this.formatTime(date) + '</small></p>' +
                        '</div>' +
                        '<div class="avatar" style="padding:0px 0px 0px 10px !important"></div>' +
                        '</li>';
                }
                else
                {
                    this.messagesHtml += '<li style="margin-bottom:20px">' +
                        '<div class="msj macro">' +
                        '<div class="text text-r">' +
                        '<p>' + this.companion_name + ':</p>' +
                        '<p>' + this.escapeHtml(text) + '</p>' +
                        filesHTML +
                        '<p><small style="margin-right:100px">' + this.formatTime(date) + '</small></p>' +
                        '</div>' +
                        '</div>' +
                        '</li>';
                }
            },
            filePathsToHTML: function (files) {
                let filesHTML = '<div style="text-align:justify;color:rgb(170, 162, 162)"><small>Прикрепленные файлы:<br>';
                let arr_paths = files.split('\n');
                for (let i = 0; i < arr_paths.length; i++)
                {
                    let file_name = arr_paths[i].substring(arr_paths[i].lastIndexOf('/') + 1);
                    filesHTML += '<a href="' + arr_paths[i] + '">' + file_name + '</a>, '
                }
                return filesHTML.slice(0, filesHTML.length - 2) + '</small></div>';
            },
            handleFiles: async function ()
            {
                if (this.$refs.attached_files.files.length != 0)
                {
                    const files = this.$refs.attached_files.files;
                    this.attached_files = {};
                    this.$refs.attached_files.files = undefined;
                    let filesList = '';
                    let filesLength = files.length - 1;
                    for (let i = 0; i < filesLength; i++)
                    {
                        this.attached_files[files[i].name] = await files[i].text();
                        filesList += files[i].name + ', ';
                    }
                    this.attached_files[files[filesLength].name] = await files[filesLength].text();
                    this.$refs.filesList.innerText = filesList + files[filesLength].name;
                }
            },
            formatTime: function (date)
            {
                if (Math.floor(Date.now() / 86400000) == Math.floor(date.getTime() / 86400000)) //if today
                    return date.toLocaleTimeString() + " | Сегодня";
                return date.toLocaleTimeString() + " | " + date.toLocaleDateString();
            },
            escapeHtml: function (text) {
                const map =
                    {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    };
                return text.split("<br>").join("\n").replace(/[&<>"']/g, function(m) { return map[m]; });
            },
            warningMessage: function (text, color = 'black') {
                this.$refs['warning_message'].style.color = color;
                this.$refs['warning_message'].innerHTML = text;
            },
            blockControls: function () {
                this.$refs['send_message_btn'].disabled = true;
                this.$refs['attached_files'].disabled = true;
                this.$refs['my_message'].disabled = true;
            }
        }
    }
</script>

<style>
    .my_message{
        border:2px solid #1f1f1f;border-radius:7px;margin-top:10px;margin-bottom:10px;background:whitesmoke;resize:none;width:350px;height:100px
    }
    .text{
        width:75%;display:flex;flex-direction:column;
    }
    .text > p:first-of-type{
        width:100%;margin-top:0;margin-bottom:auto;line-height:13px;font-size:12px;
    }
    .text > p:last-of-type{
        width:100%;text-align:right;color:rgb(170, 162, 162);margin-bottom:-7px;margin-top:auto;
    }
    .text-l{
        float:left;padding-right:10px;
    }
    .text-r{
        float:right;padding-left:10px;
    }
    .avatar{
        display:flex;
        justify-content:center;
        align-items:center;
        width:25%;
        float:left;
        padding-right:10px;
    }
    .macro{
        width:95%;border-radius:5px;border: black 5px;padding:5px;display:flex;
    }
    .msj-rta{
        float:right;background:whitesmoke;
    }
    .msj{
        float:left;background:white;
    }
    .messages{
        overflow-y:scroll;height:350px;margin-bottom:100px;
    }
    .frame{
        background:#e0e0de;
        height:450px;
        overflow:hidden;
        padding:0;
    }
    .frame > div:last-of-type{
        position:absolute;bottom:5px;width:100%;display:flex;
    }
    ul {
        white-space: pre-line;
        width:100%;
        list-style-type: none;
        padding:18px;
        bottom:32px;
        margin-top: 20px;
        display: flex;
        flex-direction: column;
    }
    .msj:before{
        width: 0;
        height: 0;
        content:"";
        top:-5px;
        left:-14px;
        position:relative;
        border-style: solid;
        border-width: 0 13px 13px 0;
        border-color: transparent #ffffff transparent transparent;
    }
    .msj-rta:after{
        width: 0;
        height: 0;
        content:"";
        top:-5px;
        left:14px;
        position:relative;
        border-style: solid;
        border-width: 13px 13px 0 0;
        border-color: whitesmoke transparent transparent transparent;
    }
    input:focus{
        outline: none;
    }
    .fileAttach{
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
    }
    .fileLabel{
        width: 25px;
        height: 25px;
    }    

    ::-webkit-input-placeholder { /* Chrome/Opera/Safari */
        color: #d4d4d4;
    }
    ::-moz-placeholder { /* Firefox 19+ */
        color: #d4d4d4;
    }
    :-ms-input-placeholder { /* IE 10+ */
        color: #d4d4d4;
    }
    :-moz-placeholder { /* Firefox 18- */
        color: #d4d4d4;
    }
</style>