// const fs = require('fs');
const express = require("express");
const cors = require("cors");
const axios = require("axios");
// const fetch = require("node-fetch");
// Socket.IO
const http = require("http");
// const https = require('https');
const socketIo = require("socket.io");
// Socket.IO

//local data base
const mysql = require("mysql");

//local data base
//server data base

// const baseUrl = "http://localhost/sayhello/";

const baseUrl = 'https://dev.codemeg.com/sayhello/';

// const connection = mysql.createConnection({
//     host: "localhost",
//     user: "root",
//     password: "",
//     database: "SayHello",
//     charset: "utf8mb4",
// });
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'codemegsoft_main',
    password: '8GwDPUpH',
    database: 'SayHello',
    charset: 'utf8mb4'
});
//server data base

const app = express();
const port = 6500;

app.use(express.json());
app.use(
    cors({
        methods: ["GET", "POST", "DELETE", "UPDATE", "PUT", "PATCH", "OPTION"],
        origin: "*",
    })
);

// const options = {
//     pingInterval: 1000000000,
//     pingTimeout: 500000000,
//         key: fs.readFileSync('/etc/ssl/sayehello/private.key'),
//         cert: fs.readFileSync('/etc/ssl/sayehello/ssl.crt')
// };

// Socket.IO
// const server = http.createServer(options,app);
const server = http.createServer(app);

const io = socketIo(server, {
    cors: {
        origin: "*",
    },
});
const imagePath = baseUrl + "uploads/chat/";
io.on("connection", (socket) => {
    console.log(`User Connected: ${socket.id}`);
    socket.emit("chatList", (data) => {
        socket.join(data);
        console.log(`User with ID: ${socket.id} joined room: ${data}`);
    });
    socket.on("chat", (data) => {
        console.log(data);
        if (
            data.sender_id !== undefined ||
            data.sender_id != "undefined" ||
            data.receiver_id !== undefined ||
            data.receiver_id != "undefined"
        ) {
            var currentDate = new Date();
            var sender_id = data.sender_id;
            var receiver_id = data.receiver_id;
            var year = currentDate.getFullYear();
            var month = String(currentDate.getMonth() + 1).padStart(2, "0");
            var day = String(currentDate.getDate()).padStart(2, "0");

            var formattedDate = year + "-" + month + "-" + day;
            let sqlExist = `SELECT * FROM chats WHERE (sender_id = ${sender_id} OR receiver_id = ${sender_id}) AND (sender_id = ${receiver_id} OR receiver_id = ${receiver_id}) ORDER BY created_at DESC LIMIT 1`;
            if (sqlExist) {
                connection.query(sqlExist, function (err, result) {
                    if (err) {
                        console.error("Error executing query: ", err);
                        return;
                    }
                    if (result.length > 0) {
                        const chat = result[0];
                        var room_id = chat.room_id;
                        console.log(data.image);
                        var sql = `INSERT INTO chats (sender_id, receiver_id, message, image, start_chat_date, chat_type , room_id, message_type) VALUES (?, ?, ?, ?, ?, ?,?, ?)`;
                        var values = [
                            sender_id,
                            receiver_id,
                            data.message,
                            data.image,
                            formattedDate,
                            data.chat_type,
                            room_id,
                            data.message_type,
                        ];
                        connection.query(sql, values, function (err, result) {
                            if (!err) {
                                let response = [];
                                if (result && result.insertId) {
                                    let temp = {
                                        id: result.insertId,
                                        sender_id: data.sender_id,
                                        receiver_id: data.receiver_id,
                                        message: data.message,
                                        created_at: data.created_at,
                                        image: data.image == null ? null : imagePath + data.image,
                                        message_type: data.message_type,
                                        created_at: result.created_at,
                                    };
                                    response.push(temp);
                                    const newData = {
                                        sender_id: data.sender_id,
                                        receiver_id:  data.receiver_id,
                                        room_id:  room_id,
                                        message: data.message == null ? "Image" : data.message,
                                        tem_or_permanent: "permanent",
                                      };
                                    axios.post('https://dev.codemeg.com/sayhello/api/chatNotification', newData)
                                    .then(response => {
                                        console.log('Response:', response.data);
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                    });
                                    var chat_message_counts_sql = `INSERT INTO chat_message_counts (receiver_id,room_id) VALUES (?, ?)`;
                            
                                    var chat_message_counts_values = [
                                        receiver_id,
                                        room_id,
                                    ];
                                    connection.query(chat_message_counts_sql, chat_message_counts_values, function (err, result) {
                                        if (!err) {

                                        }else{
                                            console.log(err);
                                        }
                                    });
                                }
                                io.emit("single_data", {
                                    status: true,
                                    message: "success",
                                    data: response,
                                });
                            } else {
                                console.log(err);
                            }
                        });
                    } else {
                        var room_id =
                            Math.floor(Math.random() * 900000000000) +
                            100000000000;
                            console.log(data.image);
                        var sql = `INSERT INTO chats (sender_id, receiver_id, message,image, start_chat_date, chat_type , room_id , message_type) VALUES (?,?, ?, ?, ?, ?,?, ?)`;
                        var values = [
                            sender_id,
                            receiver_id,
                            data.message,
                            data.image,
                            formattedDate,
                            data.chat_type,
                            room_id,
                            data.message_type,
                        ];
                        connection.query(sql, values, function (err, result) {
                            if (!err) {
                                let response = [];
                                if (result && result.insertId) {
                                    let temp = {
                                        id: result.insertId,
                                        sender_id: data.sender_id,
                                        receiver_id: data.receiver_id,
                                        message: data.message,
                                        created_at: data.created_at,
                                        image: data.image == null ? null : imagePath + data.image,
                                        message_type: data.message_type,
                                        created_at: result.created_at,
                                    };
                                    response.push(temp);
                                    const newData = {
                                        sender_id: data.sender_id,
                                        receiver_id:  data.receiver_id,
                                        room_id:  room_id,
                                        message: data.message == null ? "Image" : data.message,
                                        tem_or_permanent: "permanent",
                                      };
                                    axios.post('https://dev.codemeg.com/sayhello/api/chatNotification', newData)
                                    .then(response => {
                                        console.log('Response:', response.data);
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                    });

                                    var chat_message_counts_sql = `INSERT INTO chat_message_counts (receiver_id,room_id) VALUES (?, ?)`;
                                  
                                    var chat_message_counts_values = [
                                        receiver_id,
                                        room_id,
                                    ];
                                    connection.query(chat_message_counts_sql, chat_message_counts_values, function (err, result) {
                                        if (!err) {
                                            
                                        }else{
                                            console.log(err);
                                        }
                                    });
                                }
                                io.emit("single_data", {
                                    status: true,
                                    message: "success",
                                    data: response,
                                });
                            } else {
                                console.log(err);
                            }
                        });
                    }
                });
            }
        } else {
            console.log("insert  ids properly");
        }
    });
    socket.on("chat_between_two_users", function (data) {
        console.log("chat_between_two_users-------------");

        var room_id1 = data.room_id;
        console.log(room_id1);
        var sql = ` SELECT id, room_id, message, sender_id, receiver_id, chat_type FROM chats WHERE chat_type = 1 AND room_id = '${room_id1}' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY created_at DESC LIMIT 1`;
        // var sql = `SELECT * FROM chats WHERE chat_type = 1 AND room_id = '${room_id1}' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY created_at DESC`;

        connection.query(sql, function (err, result) {
            if (err) throw err;
            let response = [];
            result.forEach(function (item) {
                let temp = {
                    id: item.id,
                    sender_id: item.sender_id,
                    receiver_id: item.receiver_id,
                    message: item.message,
                    image: item.image == null ? null : imagePath + item.image,
                    message_type: item.message_type,
                    room_id: item.room_id,
                    created_at: item.created_at,
                };
                response.push(temp);
            });
            console.log(response);
            console.log("chat_receiver3----------------------------");
            io.emit("chat_receiver", {
                status: true,
                message: "success",
                data: response,
            });
        });
    });
    socket.on("chat_between_two_users_agian", function (data) {
        console.log("chat_between_two_users_agian----------------");
        var room_id1 = data.room_id;
        console.log(room_id1);
        console.log(data.user_id);
        // var sql = ` SELECT id, room_id, message, sender_id, receiver_id, chat_type FROM chats WHERE chat_type = 1 AND room_id = '${room_id1}' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY created_at DESC LIMIT 1`;
        var sql = `SELECT * FROM chats WHERE chat_type = 1 AND room_id = '${room_id1}' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY id ASC`;

        connection.query(sql, function (err, result) {
            if (err) throw err;
            let response = [];
            result.forEach(function (item) {
                const image = item.image;
                let temp = {
                    id: item.id,
                    sender_id: item.sender_id,
                    receiver_id: item.receiver_id,
                    message: item.message,
                    image: image == null ? null : imagePath + image,
                    message_type: item.message_type,
                    room_id: item.room_id,
                    created_at: item.created_at,
                };
                response.push(temp);
            });
            console.log(response);
            var DeleteThis = `DELETE FROM chat_message_counts WHERE receiver_id = '${data.user_id}' AND room_id = '${data.room_id}'`;
            connection.query(DeleteThis, function (err, result) {
                if(err){
                    console.log('success');
                }else{
                    console.log('success');
                }
            });
            io.emit("chat_between_two_users_agian", {
                status: true,
                message: "success",
                data: response,
            });
        });
    });
    // 
    socket.on("temporary_chat", (data) => {
        if (
            data.sender_id !== undefined ||
            data.sender_id != "undefined" ||
            data.receiver_id !== undefined ||
            data.receiver_id != "undefined"
        ) {
            var currentDate = new Date();
            var sender_id = data.sender_id;
            var receiver_id = data.receiver_id;
            var year = currentDate.getFullYear();
            var month = String(currentDate.getMonth() + 1).padStart(2, "0");
            var day = String(currentDate.getDate()).padStart(2, "0");

            var formattedDate = year + "-" + month + "-" + day;
            let sqlExist = `SELECT * FROM temporary_chats WHERE (sender_id = ${sender_id} OR receiver_id = ${sender_id}) AND (sender_id = ${receiver_id} OR receiver_id = ${receiver_id}) ORDER BY created_at DESC LIMIT 1`;
            if (sqlExist) {
                connection.query(sqlExist, function (err, result) {
                    if (err) {
                        console.error("Error executing query: ", err);
                        return;
                    }
                    if (result.length > 0) {
                        const chat = result[0];
                        var room_id = chat.room_id;
                        var sql = `INSERT INTO temporary_chats (sender_id, receiver_id, message, image, start_chat_date , room_id, message_type) VALUES ( ?, ?, ?, ?, ?,?, ?)`;
                        var values = [
                            sender_id,
                            receiver_id,
                            data.message,
                            data.image,
                            formattedDate,
                            room_id,
                            data.message_type,
                        ];
                        connection.query(sql, values, function (err, result) {
                            if (!err) {
                                let response = [];
                                if (result && result.insertId) {
                                    let temp = {
                                        id: result.insertId,
                                        sender_id: data.sender_id,
                                        receiver_id: data.receiver_id,
                                        message: data.message,
                                        created_at: data.created_at,
                                        image: data.image == null ? null : imagePath + data.image,
                                        message_type: data.message_type,
                                        created_at: result.created_at,
                                    };
                                    response.push(temp);
                                    const newData = {
                                        sender_id: data.sender_id,
                                        receiver_id:  data.receiver_id,
                                        room_id:  room_id,
                                        message: data.message == null ? "Image" : data.message,
                                        tem_or_permanent: "temporary",
                                      };
                                    axios.post('https://dev.codemeg.com/sayhello/api/chatNotification', newData)
                                    .then(response => {
                                        console.log('Response:', response.data);
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                    });
                                }
                                io.emit("temporary_single_data", {
                                    status: true,
                                    message: "success",
                                    data: response,
                                });
                            } else {
                                console.log(err);
                            }
                        });
                    } else {
                        var room_id =
                            Math.floor(Math.random() * 900000000000) +
                            100000000000;
                        var sql = `INSERT INTO temporary_chats (sender_id, receiver_id, message,image, start_chat_date, room_id , message_type) VALUES (?, ?, ?, ?, ?,?, ?)`;
                        var values = [
                            sender_id,
                            receiver_id,
                            data.message,
                            data.image,
                            formattedDate,
                            room_id,
                            data.message_type,
                        ];
                        connection.query(sql, values, function (err, result) {
                            if (!err) {
                                let response = [];
                                if (result && result.insertId) {
                                    let temp = {
                                        id: result.insertId,
                                        sender_id: data.sender_id,
                                        receiver_id: data.receiver_id,
                                        message: data.message,
                                        created_at: data.created_at,
                                        image: data.image == null ? null : imagePath + data.image,
                                        message_type: data.message_type,
                                        created_at: result.created_at,
                                    };
                                    response.push(temp);
                                    const newData = {
                                        sender_id: data.sender_id,
                                        receiver_id:  data.receiver_id,
                                        room_id:  room_id,
                                        message: data.message == null ? "Image" : data.message,
                                        tem_or_permanent: "temporary",
                                    };
                                    axios.post('https://dev.codemeg.com/sayhello/api/chatNotification', newData)
                                    .then(response => {
                                        console.log('Response:', response.data);
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                    });
                                }
                                io.emit("temporary_single_data", {
                                    status: true,
                                    message: "success",
                                    data: response,
                                });
                            } else {
                                console.log(err);
                            }
                        });
                    }
                });
            }
        } else {
            console.log("insert  ids properly");
        }
    });
    socket.on("temporary_chat_between_two_users", function (data) {
        var room_id1 = data.room_id;
        var sql = ` SELECT id, room_id, message, image,sender_id, message_type, receiver_id FROM temporary_chats WHERE room_id = '${room_id1}' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY created_at DESC LIMIT 1`;
        connection.query(sql, function (err, result) {
            if (err) throw err;
            let response = [];
            result.forEach(function (item) {
                let temp = {
                    id: item.id,
                    sender_id: item.sender_id,
                    receiver_id: item.receiver_id,
                    message: item.message,
                    image: item.image == null ? null : imagePath + item.image,
                    message_type: item.message_type,
                    room_id: item.room_id,
                    created_at: item.created_at,
                };
                response.push(temp);
            });
            io.emit("temporary_chat_receiver", {
                status: true,
                message: "success",
                data: response,
            });
        });
    });
    socket.on("temporary_chat_between_two_users_agian", function (data) {
        var room_id1 = data.room_id;
        var sql = `SELECT * FROM temporary_chats WHERE room_id = '${room_id1}' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY id ASC`;
        connection.query(sql, function (err, result) {
            if (err) throw err;
            let response = [];
            result.forEach(function (item) {
                const image = item.image;
                let temp = {
                    id: item.id,
                    sender_id: item.sender_id,
                    receiver_id: item.receiver_id,
                    message: item.message,
                    image: image == null ? null : imagePath + image,
                    message_type: item.message_type,
                    room_id: item.room_id,
                    created_at: item.created_at,
                };
                response.push(temp);
            });
            io.emit("temporary_chat_between_two_users_agian", {
                status: true,
                message: "success",
                data: response,
            });
        });
    });
});
server.listen(6500, () => {
    console.log("listening on :6500");
});