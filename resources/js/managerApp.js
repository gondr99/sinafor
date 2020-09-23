import App from './components/ManagerApp';
import Vuex from 'vuex';

Vue.use(Vuex); //using vuex for state management

//adminApp store
const store = new Vuex.Store({
    state:{
        skillList:[],
        //registeredList:[],
    },
    mutations:{
        refreshSkillList(state, list){
            state.skillList = list;
        }
        // refreshRegisteredList(state, list){
        //     state.registeredList = list;
        // },
    }
});

window.onload = ()=>{
    new Vue({
        el:"#managerApp",
        store,
        render:h=> h(App)
    });
};
