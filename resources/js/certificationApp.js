import App from './components/CertificationApp';
// import Vuex from 'vuex';
//
// Vue.use(Vuex); //using vuex for state management

// //adminApp store
// const store = new Vuex.Store({
//     state:{
//         levelList:[],
//     },
//     mutations:{
//         refreshLevels(state, list){
//             state.levelList = list;
//         },
//     }
// });

window.onload = ()=>{
    new Vue({
        el:"#certificationApp",
        render:h=> h(App)
    });
};
