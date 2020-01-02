<template>
    <div>
        <div data-toggle="dropdown" class="dropdown-toggle" style="cursor: pointer"
        @click="updateLastReadNotification">
            Уведомления <span v-bind:class="{'label label-warning': unread_notifications_count != undefined}"
                              v-text="unread_notifications_count"></span>
        </div>
        <ul class="dropdown-menu">
            <ul class="menu" style="max-height: 100px; overflow:auto; max-width: 300px"
                v-html="renderedNotifications" ref="notifications-menu"></ul>
        </ul>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        data: function () {
            return {
                notifications: localStorage.notifications,
                notificationsCount: 0,
                renderedNotifications: 'У вас еще нет уведомлений.',
                unread_notifications_count: localStorage.unread_notifications_count
            }
        },
        watch: {
            unread_notifications_count (newValue) {
                if (newValue == undefined)
                    localStorage.removeItem('unread_notifications_count');
                else
                    localStorage.unread_notifications_count = newValue;
            },
            notifications (newValue) {
                localStorage.notifications = newValue;
            }
        },
        mounted() {
            if (this.notifications === undefined) {
                this.getAllNotifications();
            }
            else {
                this.renderNotifications();
            }
            
            setInterval(this.getNewNotifications, 5000);
        },
        methods: {
            getAllNotifications: function () {
                axios.get('notifications/get-all').then(response => {
                    let notifications = JSON.parse(response.data.notifications);
                    this.notifications = response.data.notifications;
                    this.renderNotifications();
                    if (response.data.last_read_notification_index != null)
                    {
                        this.unread_notifications_count = notifications.length - 1 - response.data.last_read_notification_index;
                    }
                    else if (notifications.length != 0)
                    {
                        this.unread_notifications_count = notifications.length;
                    }
                    this.notificationsCount = notifications.length;
                })
            },
            renderNotifications: function (thisNotifications = []) {
                if (thisNotifications.length != 0)
                {
                    for (let i = 0; i < thisNotifications.length; i++)
                    {
                        this.renderedNotifications += '<li>' + 
                        thisNotifications[i][0]/*notification_text*/ + this.formatTime(new Date(thisNotifications[i][1]/*notification_time*/))
                        + '</li>';
                    }
                }
                else
                {
                    let notifications = JSON.parse(this.notifications);
                    this.notificationsCount = notifications.length;
                    if (notifications.length == 0)
                        return;
                    this.renderedNotifications = '';
                    for (let i = 0; i < notifications.length; i++)
                    {
                        this.renderedNotifications += '<li>' + 
                        notifications[i][0]/*notification_text*/ + this.formatTime(new Date(notifications[i][1]/*notification_time*/))
                        + '</li>';
                    }
                }
            },
            updateLastReadNotification: function () {
                //console.log('f');
                if (this.unread_notifications_count != undefined)
                {
                    axios.get('notifications/update-last-read');
                    this.unread_notifications_count = undefined;
                }
                this.$refs['notifications-menu'].scrollTo(0, 126);
            },
            addUnreadNotificationsCount: function (count) {
                if (this.unread_notifications_count == undefined)
                    this.unread_notifications_count = count;
                else
                    this.unread_notifications_count = parseInt(this.unread_notifications_count) + count;
            },
            getNewNotifications: function () {
                axios.get('notifications/get-unread' + (this.unread_notifications_count == undefined ? '' : '?indexFrom=' + this.notificationsCount))
                    .then(response => {
                        let unread_notifications = response.data;
                        if (unread_notifications.length != 0)
                        {
                            let json_notifications = JSON.parse(this.notifications);
                            Array.prototype.push.apply(json_notifications, unread_notifications);
                            this.notifications = JSON.stringify(json_notifications);
                            this.notificationsCount += unread_notifications.length;
                            this.renderNotifications(unread_notifications);
                            this.addUnreadNotificationsCount(unread_notifications.length);
                        }
                    });
            },
            formatTime: function (date) {
                return "<br><b>" + date.toLocaleTimeString() + ", " + date.toLocaleDateString() + "</b>"
            }
        }
    }
</script>