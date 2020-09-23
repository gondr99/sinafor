import App from './components/ExpertApp';
import Vuex from 'vuex';

Vue.use(Vuex); //using vuex for state management

//adminApp store
const store = new Vuex.Store({
    state:{
        skillList:[],
    },
    mutations:{
        refreshSkillList(state, list){
            state.skillList = list;
        }
    }
});

window.onload = ()=>{
    new Vue({
        el:"#expertApp",
        store,
        render:h=> h(App)
    });
};
