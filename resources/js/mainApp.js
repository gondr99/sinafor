import App from './components/MainApp';
import Vuex from 'vuex';

Vue.use(Vuex); //using vuex for state management

const store = new Vuex.Store({
    state:{
        //skillList:[],
    },
    mutations:{
        // refreshSkillList(state, list){
        //     state.skillList = list;
        // }
    }
});

window.onload = ()=>{
    new Vue({
        el:"#mainApp",
        store,
        render:h=> h(App)
    });
};
