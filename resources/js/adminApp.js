import App from './components/AdminApp';
import Vuex from 'vuex';

Vue.use(Vuex); //using vuex for state management

//adminApp store
const store = new Vuex.Store({
    state:{
        categoryList:[], //user category list
        skillList:[], //supporting job list
    },
    mutations:{
        refreshCategory(state, list){
            state.categoryList = list
        },
        addCategory(state, item){
            state.categoryList = [...state.categoryList, item];
        },
        delCategory(state, id){
            state.categoryList = state.categoryList.filter(x => x.id !== id);
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
