<template>
    <div class="col-sm-3 col-sm-offset-4 frame">
        <div class="messages" ref="chatBody">
            <ul v-html="messagesHtml"></ul>
        </div>
        <div>
            <div class="msj-rta macro">
                <div class="text text-r" style="background:whitesmoke !important">
                    <textarea class="my_message" ref="my_message" style="resize: none" placeholder="Вводите сообщение"></textarea>
                    <button class="btn" style="margin-top: 10px" @click="sendMessage()">Отправить</button>
                </div>
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
                WS: new WebSocket("ws://" + this.getDomain() + ":8000?companions=" + this.companion_ids + "&token=" + this.token)
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
            this.historyMessagesToHTML();
        },
        updated() {
            // scroll chat to bottom
            this.$refs['chatBody'].scrollTo(0, this.$refs['chatBody'].scrollHeight);
        },
        methods: {
            historyMessagesToHTML: function () {
                const messages = this.historyConversationData.split("\n");
                messages.pop();
                if (messages.length != 0)
                {
                    const ids = this.companion_ids.split("-");
                    const me = ids[0];
                    const not_me = ids[1];
                    for (let i = 0; i < messages.length; i++)
                    {
                        messages[i] = JSON.parse(messages[i]);
                        if (messages[i][me] === undefined)
                            this.insertChat("not me", messages[i][not_me], new Date(messages[i]["t"]));
                        else
                            this.insertChat("me", messages[i][me], new Date(messages[i]["t"]));
                    }
                }
            },
            onMessage: function (event) {
                this.insertChat("not me", event.data);
            },
            sendMessage: function () {
                let text = this.$refs["my_message"].value;
                if (text.trim() !== "")
                {
                    this.WS.send(text.split("\n").join("<br>"));
                    this.insertChat("me", text.split("\n").join("<br>"));
                    this.$refs["my_message"].value = "";
                }
            },
            insertChat: function (who = "not me", text, date = new Date()) {
                let control = "";

                if (who == "me")
                {
                    control = '<li style="width:100%;margin-top: 20px">' +
                        '<div class="msj macro">' +
                        '<div class="text text-r">' +
                        '<p>' + this.escapeHtml(text) + '</p>' +
                        '<p><small>' + this.formatTime(date) + '</small></p>' +
                        '</div>' +
                        '</div>' +
                        '</li>';
                }
                else
                {
                    control = '<li style="width:100%;margin-top: 20px">' +
                        '<div class="msj-rta macro">' +
                        '<div class="text text-l">' +
                        '<p>' + this.companion_name + ':</p>' +
                        '<p>' + this.escapeHtml(text) + '</p>' +
                        '<p><small>' + this.formatTime(date) + '</small></p>' +
                        '</div>' +
                        '<div class="avatar" style="padding:0px 0px 0px 10px !important"></div>' +
                        '</li>';
                }
                this.messagesHtml += control;
            },
            formatTime: function (date)
            {
                if (Math.floor(Date.now() / 86400000) == Math.floor(date.getTime() / 86400000)) //if today
                    return date.toLocaleTimeString() + " | Сегодня";
                return date.toLocaleTimeString() + " | " + date.toLocaleDateString();
            },
            escapeHtml: function (text) {
                let map =
                    {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    };
                return text.split("<br>").join("\n").replace(/[&<>"']/g, function(m) { return map[m]; });
            },
            getDomain: function ()
            {
                let t = document.createElement('a');
                t.href = window.location.href;
                return t.hostname;
            }
        }
    }
</script>

<style>
    .my_message{
        border:0;padding:10px;background:whitesmoke;
    }
    .text{
        width:75%;display:flex;flex-direction:column;
    }
    .text > p:first-of-type{
        width:100%;margin-top:0;margin-bottom:auto;line-height: 13px;font-size: 12px;
    }
    .text > p:last-of-type{
        width:100%;text-align:right;color:silver;margin-bottom:-7px;margin-top:auto;
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
        width:85%;border-radius:5px;padding:5px;display:flex;
    }
    .msj-rta{
        float:right;background:whitesmoke;
    }
    .msj{
        float:left;background:white;
    }
    .messages{
        overflow-y: scroll; height: 300px;
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