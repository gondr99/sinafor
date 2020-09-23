<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputLevel1">{{trans('title.skill_level_one_list')}}</label>
                                <select id="inputLevel1" class="form-control" v-model="level1">
                                    <option v-for="level in levelList" :value="level.id" >{{level.name}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputLevel2">{{trans('title.skill_level_two_list')}}</label>
                                <select id="inputLevel2" class="form-control" v-model="level2">
                                    <option v-for="level in level2List" :value="level.id" >{{level.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="searchInput">{{trans('title.skill_search')}}</label>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" :placeholder="trans('placeholder.skill_name')" id="searchInput" v-model="word">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" @click="searchSkill">{{trans('menu.search')}}</button>
                            </div>
                        </div>
                    </div>
<!--                    end of card body-->
                </div>
<!--                end of card-->
            </div>
        </div>
<!--        row of search-->
        <div class="row">
            <div class="col-12" id="skillList">
                <skill-component v-for="skill in skillList" :admin="false" :item="skill" :key="skill.id"></skill-component>
            </div>
        </div>
<!--        row of list-->
    </div>

</template>

<script>
    import {mapState} from "vuex";
    import SkillComponent from "./Admin/SkillComponent";

    export default {
        name: "SkillApp",
        components:{
            'skill-component':SkillComponent
        },
        data(){
            return {
                level1:undefined,
                level2:undefined,
                word:'',
                skillList:[]
            }
        },
        mounted() {
            this.loadData();
        },
        methods:{
            //dev comment : 이미 수료를 완료한 과정에 대해서는 나타나지 않도록 정정해야 한다. -> 차후 개발(2020-09-20)

            async loadData(){
                try {
                    let res = await axios.get('/skill/skillLevel');
                    this.$store.commit('refreshLevels', res.data);
                    res = await axios.get('/skill/skillList');
                    this.$store.commit('refreshSkillList', res.data);
                }catch (err) {
                    console.log(err);
                }
                //this.skillList = this.$store.state.skillList;
            },
            searchSkill(){
                this.skillList = this.$store.state.skillList.filter(x => x.name.includes(this.word));
            }
        },
        watch:{
            level2(){
                this.skillList = this.$store.state.skillList.filter(x => x.belongs == this.level2);
            }
        },
        computed: mapState({
            levelList: state => state.levelList,
            level2List (state){
                const item = state.levelList.find(x => x.id == this.level1);
                if(item !== undefined){
                    return item.two;
                }else {
                    return [];
                }
            },
            selectedLevel(state){
                return state.levelList.find(x => x.id == this.level1);
            }
        })
    }
</script>

<style scoped>
    #skillList {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap:10px;
    }
</style>
