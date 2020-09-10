import App from './components/RegisterApp';

window.onload = ()=>{
    new Vue({
        el:"#registerApp",
        render:h=> h(App)
    });
};
