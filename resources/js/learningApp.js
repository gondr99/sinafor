import App from './components/LearningApp';
import Vuex from 'vuex';

Vue.use(Vuex); //using vuex for state management

//adminApp store
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
        components:{
            'learning-app':App
        },
        el:"#learningApp",
        store
    });
};
