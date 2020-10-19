import App from './components/not_used_anymore/RegisterApp';

window.onload = ()=>{
    new Vue({
        el:"#registerApp",
        render:h=> h(App)
    });
};
