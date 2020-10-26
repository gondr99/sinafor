const express = require('express');
const http = require('http');
const path = require('path');
const socket = require('socket.io');
const crypto = require('crypto');

const app = new express();
const server = http.createServer(app);
const io = socket(server);

const bodyParser = require('body-parser');
const session = require('express-session');
const {pool} = require('./chatServerApp/DB');
const cookieSecret = "yyskills";

let userList = [];

io.on('connect', socket => {
    let user = undefined;

    socket.on('chat', data => {
        if(user === undefined) return; //do nothing
        //여기서 메시지가 왔음을 Room에 있는 모든 유저들의 안드로이드로 보내주는 코드가 필요하다.

        const sql = "INSERT INTO messages (`room_id`, `user_id`, `message`, `read`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, NOW(), NOW())";
        if(io.to(user.room).adapter.rooms[user.room].length > 1){
            //이미 방에 사람이 들어왔다면 읽음표시 바로
            pool.query(sql, [user.room, user.id, data, 1]);
        }else{
            pool.query(sql, [user.room, user.id, data, 0]);
        }

        io.to(user.room).emit('message', {user_id: user.id, profile:user.profile, message:data});
    });

    socket.on('login', data => {
        //여기서 데이터가 DB랑 맞는지를 체크해서 인증해야 한다.

        let token = data.token;
        const payload = deHashing(token);
        if(payload === null) {
            socket.emit('error-msg', 'Not allowed');
            return;
        }
        if(payload.id !== data.user.id) {
            socket.emit('error-msg', 'Not allowed');
            return;
        }
        user = data.user;
        user.isExpert = payload.isExpert;
        user.socket = socket.id;
        userList.push(user);
    });

    socket.on('join', data => {
        //해당 방에 들어가서
        user.room = data;
        socket.join(data);
        //adaptoer의 rooms에 보면 해당 방에 있는 유저들의 소켓정보가 배열로 저장되어 있다.
        //console.log(io.to(user.room).adapter.rooms[user.room].length);
    });

    socket.on('disconnect', data => {
        //소켓 접속해제시 해야할 일을 여기에 적어야해
        if(user !== undefined){
            let idx = userList.findIndex(x => x.socket === socket.id);
            if(idx >= 0){
                userList.splice(idx, 1);
            }
            socket.leave(user.room); //방을 나가고
            user = undefined;
        }
    });
});

function deHashing(token){

    const b = Buffer.from(token, 'base64');
    let parted = b.toString().split(".");
    const hmac = crypto.createHmac('sha256', cookieSecret);

    let data = parted[0] + parted[1];

    const signature = hmac.update(data).digest('base64');

    if( signature === parted[2]){
        return JSON.parse(parted[1]);
    }else{
        return null;
    }
}

server.listen(54000, ()=>{
    console.log("chat server listening on 54000 port");
});
