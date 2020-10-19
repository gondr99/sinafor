require('./bootstrap');

window.Vue = require('vue');

window.Swal = require('sweetalert2');

window.io = require('socket.io-client');

//this code is support multilanguage
Vue.prototype.trans = string => window._.get(window.i18n, string);

//save user role name from server
//axios.get('/')

Number.prototype.zeroFormat = function(count){
    let dummy = "0".repeat(count) + this;

    return (dummy).substr(dummy.length - count);
};

window.socket = new io.connect('localhost:54000', {transports: ['websocket'], upgrade: false});
