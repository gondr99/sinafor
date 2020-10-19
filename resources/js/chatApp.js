import App from './components/ChatApp';
import Vuex from 'vuex';

Vue.use(Vuex); //using vuex for state management

//adminApp store
const store = new Vuex.Store({
    state:{
        skillList:[],
        userList:[],
    },
    mutations:{
        refreshSkillList(state, list){
            state.skillList = list;
        },
        refreshUserList(state, list){
            state.userList = list;
        }
    }
});

window.onload = ()=>{
    new Vue({
        el:"#chatApp",
        store,
        render:h=> h(App)
    });
};
