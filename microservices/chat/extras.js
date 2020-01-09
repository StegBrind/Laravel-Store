require("dotenv").config({ path:"../../.env" });

module.exports = {
    mysql: require("mysql"),
    mysql_config: {
        host: process.env.DB_HOST,
        user: process.env.DB_USERNAME,
        password: process.env.DB_PASSWORD,
        database: process.env.DB_DATABASE
    },
    sendMySQL: function (SQL) {
        let mysql_conn = this.mysql.createConnection(this.mysql_config);
        mysql_conn.query(SQL);
        mysql_conn.end();
    },
    GETParameter: function (uri, get_parameter) {
        return decodeURIComponent((new RegExp('[?&]'+encodeURIComponent(get_parameter)+'=([^&]*)')).exec(uri)[1]);
    },
    generateToken: function () {
        let token = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        for (let i = 0; i < 50; i++) token += characters.charAt(Math.floor(Math.random() * 62));
        return token;
    }
};