import App from './components/SkillApp';
import Vuex from 'vuex';

Vue.use(Vuex); //using vuex for state management

//adminApp store
const store = new Vuex.Store({
    state:{
        levelList:[],
        skillList:[],
        stateList:[],
    },
    mutations:{
        refreshLevels(state, list){
            state.levelList = list;
        },

        refreshSkillList(state, list){
            state.skillList = list;
        },

        refreshStateList(state, list){
            state.stateList = list;
        }
        // addSkill(state, item){
        //     state.skillList = [...state.skillList, item];
        // },
        // removeSkill(state, id){
        //     state.skillList = state.skillList.filter(x => x.id !== id);
        // }

    }
});

window.onload = ()=>{
    new Vue({
        el:"#skillApp",
        store,
        render:h=> h(App)
    });
};
