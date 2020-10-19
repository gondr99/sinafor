const mysql = require('mysql2');

const {credential} = require('./credential');
/*
    credential.js 파일에는 다음과 같은 내용이 들어가야 한다.
    const credential = {
    host:'',
    user:'',
    database:'',
    password:''
};

module.exports.credential = credential;

 */
const pool = mysql.createPool(credential);

const promisePool = pool.promise();

module.exports.pool = promisePool;
