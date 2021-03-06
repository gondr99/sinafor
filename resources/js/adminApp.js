import App from './components/AdminApp';
import Vuex from 'vuex';

Vue.use(Vuex); //using vuex for state management

//adminApp store
const store = new Vuex.Store({
    state:{
        levelList:[],

        //categoryList:[], //not used....sad...T.T
        skillList:[], //supporting job list
    },
    mutations:{
        refreshLevels(state, list){
            state.levelList = list;
        },

        refreshSkillList(state, list){
            state.skillList = list;
        },
        addSkill(state, item){
            state.skillList = [...state.skillList, item];
        },
        removeSkill(state, id){
            state.skillList = state.skillList.filter(x => x.id !== id);
        }

    }
});

window.onload = ()=>{
    new Vue({
        el:"#adminApp",
        store,
        render:h=> h(App)
    });
};
