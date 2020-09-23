import App from './components/MyPageApp';
import Vuex from 'vuex';

Vue.use(Vuex); //using vuex for state management

//adminApp store
const store = new Vuex.Store({
    state:{
        registeredList:[],
        user:{},
    },
    mutations:{
        refreshRegisteredList(state, list){
            state.registeredList = list;
        },
        setUser(state, user){
            state.user = user;
        }
    }
});

window.onload = ()=>{
    new Vue({
        el:"#myPageApp",
        store,
        render:h=> h(App)
    });
};
