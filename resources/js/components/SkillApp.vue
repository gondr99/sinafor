<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <header-component :title="trans('title.skill_register_title')" @toggle="searchToggle = !searchToggle"></header-component>
            </div>
        </div>
        <div class="row">
            <div class="col-12 p-0">
                <div class="card">
                    <div class="card-body">
<!--                        search input form-->
                        <transition name="fade">
                            <div class="input-group mb-3" v-if="searchToggle">
                                <input type="text" class="form-control" :placeholder="trans('title.skill_search')" id="searchInput" v-model="word">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" @click="searchSkill">{{trans('menu.search')}}</button>
                                </div>
                            </div>
                        </transition>
<!--                        search input form end-->

<!--                        search page start-->
                        <div class="search-page" v-if="page === 0">
                            <div class="video-container" v-if="level1 === undefined && !searched">
                                <video loop="loop" controls preload="auto" width="100%">
                                    <source src="/videos/static/certification_video_sample.mp4" type="video/mp4">
                                    <img src="/images/not/404.jpg" alt="404">
                                </video>
                            </div>

                            <div class="skill-select-container">
                                <transition name="fade" mode="out-in">
                                    <div class="skill-list" v-if="level1 === undefined && !searched">
                                        <div class="skill-level-list" v-for="level in levelList" :style="`background-image:url('/images/skills/${level.image}')`" @click.stop="loadLevel2(level.id)">
                                            <div class="title-box">
                                                <h4 class="title">{{level.name}}</h4>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="second-grid" v-else-if="level1 !== undefined && !searched">
                                        <div class="menu-bar d-flex justify-content-end mb-3" >
                                            <button class="btn btn-sm btn-outline-success mr-2" @click="backToList">{{trans('menu.go_first')}}</button>
                                            <button class="btn btn-sm btn-outline-primary" @click="back">{{trans('menu.back')}}</button>
                                        </div>


                                        <div class="skill-list" v-if="level2 === undefined && !searched">
                                            <div class="skill-level-list" v-for="level in level2List" :style="`background-image:url('/images/skills/${level.image}')`" @click.stop="loadSkill(level.id)">
                                                <div class="title-box">
                                                    <h4 class="title">{{level.name}}</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="skill-page" v-if="level2 !== undefined && !searched">
                                            <div class="image-container" :style="`background-image:url(/images/skills/${selectedLevel2.image})`">
                                                <div class="title-box">
                                                    <h4 class="title">{{selectedLevel2.name}}</h4>
                                                </div>
                                            </div>
                                            <div class="desc-container">
                                                <p>
                                                    {{selectedLevel2.desc}}
                                                </p>
                                            </div>
                                            <div class="contained-skill-list mb-4">
                                                <div class="skill-item" :class="{'bg-danger': selectedSkillIdx === skill.id, 'bg-primary':selectedSkillIdx !== skill.id}" v-for="skill in skillList" :key="skill.id" @click="selectedSkillIdx = skill.id">
                                                    {{skill.name}}
                                                </div>
                                            </div>
                                            <div class="certify-btn-container d-flex justify-content-center">
                                                <button class="btn btn-outline-primary" @click="certifySkill">{{trans('menu.certify_this_skill')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </transition>

                                <div class="searched-page" v-if="searched && skillList.length !== 0">
                                    <div class="contained-skill-list mb-4">
                                        <div class="skill-item" :class="{'bg-danger': selectedSkillIdx === skill.id, 'bg-primary':selectedSkillIdx !== skill.id}" v-for="skill in skillList" :key="skill.id" @click="selectedSkillIdx = skill.id">
                                            {{skill.name}}
                                        </div>
                                    </div>
                                    <div class="certify-btn-container d-flex justify-content-center">
                                        <button class="btn btn-outline-primary" @click="certifySkill">{{trans('menu.certify_this_skill')}}</button>
                                    </div>
                                </div>

                                <div class="searched-page" v-else-if="searched">
                                    <p>{{trans('messages.list_is_empty')}}</p>
                                </div>
                            </div>
                        </div>
<!--                        search page end-->

<!--                        certify-page start-->
                        <div class="certify-page" v-else-if="page === 1 && selectedSkillInfo !== undefined">
                            <div class="logo-container d-flex justify-content-center align-items-center">
                                <i class="far fa-id-card"></i>
                            </div>
                            <div class="skill-info-container">
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="category1">{{ trans('title.category_level1')}}</label>
                                        <input type="text" class="form-control" id="category1" readonly :value="selectedSkillInfo.level1.name">
                                    </div>
                                    <div class="form-group col-12">
                                        <label for="category2">{{ trans('title.category_level2')}}</label>
                                        <input type="text" class="form-control" id="category2" readonly :value="selectedSkillInfo.level2.name">
                                    </div>
                                    <div class="form-group col-12">
                                        <label for="skillTitle">{{ trans('title.skill_title')}}</label>
                                        <input type="text" class="form-control" id="skillTitle" readonly :value="selectedSkillInfo.name">
                                    </div>
                                </div>
                            </div>
                            <div class="form-container my-2">
                                <p>{{trans('title.your_position')}}</p>
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label for="latitude">Latitude</label>
                                        <input type="text" class="form-control" id="latitude" readonly :value="coords.latitude">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="longitude">Latitude</label>
                                        <input type="text" class="form-control" id="longitude" readonly :value="coords.longitude">
                                    </div>
                                </div>

                                <p>{{trans('title.another_location_message')}}</p>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <select v-model="anotherLocation" class="form-control">
                                            <option :value="state.id" :key="state.id" v-for="state in stateList">{{state.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <div class="form-group col-12">
                                            <label for="applicationDate">{{ trans('title.application_date')}}</label>
                                            <input type="date" class="form-control" id="applicationDate" readonly :value="today">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-12 d-flex justify-content-center">
                                        <button class="btn btn-outline-primary mr-2" @click="startCertify">{{trans('menu.start')}}</button>
                                        <button class="btn btn-outline-danger" @click="backToList">{{trans('menu.go_first')}}</button>
                                    </div>
                                </div>
                           </div>
                        </div>
<!--                        certify-page end-->
                    </div>
<!--                    end of card body-->
                </div>
<!--                end of card-->
            </div>
        </div>

    </div>

</template>

<script>
    import {mapState} from "vuex";
    import SkillComponent from "./Admin/SkillComponent";
    import HeaderComponent from "./HeaderComponent";

    export default {
        name: "SkillApp",
        components:{
            'skill-component':SkillComponent,
            'header-component':HeaderComponent,
        },
        data(){
            return {
                level1:undefined,
                level2:undefined,
                word:'',
                searched:false,
                skillList:[],
                selectedSkillIdx:-1,  //set to -1
                searchToggle:false,
                page:0, //set to 0,
                coords: {
                    latitude:0,
                    longitude:0
                },
                anotherLocation:1,
            }
        },
        mounted() {
            this.loadData();
            this.getCoords();
        },
        methods:{
            certifySkill(){
                if(this.selectedSkillIdx < 0){
                    Swal.fire(this.trans('messages.must_select_skill'));
                    return;
                }
                this.page = 1;
            },
            loadLevel2(level1Id){
                  this.level1 = level1Id;
            },
            loadSkill(level2Id){
                this.level2 = level2Id;
            },
            async loadData(){
                try {
                    let res = await axios.get('/skill/skillLevel');
                    this.$store.commit('refreshLevels', res.data);
                    res = await axios.get('/skill/skillList');
                    this.$store.commit('refreshSkillList', res.data);
                    res = await axios.get('/state');
                    this.$store.commit('refreshStateList', res.data);
                }catch (err) {
                    console.log(err);
                }
            },
            searchSkill(){
                if(this.word !== ""){
                    this.searched = true;
                    this.skillList = this.$store.state.skillList.filter(x => x.name.includes(this.word));
                }else{
                    this.searched = false;
                    this.skillList = [];
                }

            },
            back(){
                if(this.level2 === undefined){
                    this.level1 = undefined;
                }else {
                    this.selectedSkillIdx = -1;
                    this.level2 = undefined;
                }
            },
            backToList(){
                this.level1 = undefined;
                this.level2 = undefined;
                this.selectedSkillIdx = -1;
                this.page = 0;
            },
            getCoords(){
                if("geolocation" in navigator){
                    navigator.geolocation.getCurrentPosition(p => {
                        this.coords.latitude = p.coords.latitude;
                        this.coords.longitude = p.coords.longitude;
                    })
                }
            },
            async startCertify(){
                const result = await this.checkSure();
                if (result.isConfirmed){
                    axios.put(`/skill/register/${this.selectedSkillIdx}`,{state:this.anotherLocation, date:this.today}).then( async res => {
                        await Swal.fire(res.data.msg);
                        this.backToList();
                    }).catch(err => {
                        console.log(err);
                    })
                }
            },
            checkSure(){
                return Swal.fire({
                    title: this.trans('messages.skill_register'),
                    text: this.trans('messages.sure'),
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: this.trans('messages.yes'),
                    cancelButtonText: this.trans('messages.cancel')
                });
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
                const item = state.levelList.find(x => x.id === this.level1);
                if(item !== undefined){
                    return item.two;
                }else {
                    return [];
                }
            },
            stateList: state => state.stateList,
            selectedLevel2(state){
                return this.level2List.find(x => x.id === this.level2);
            },
            selectedSkillInfo(state){
                let skill = state.skillList.find(x => x.id === this.selectedSkillIdx);
                if(skill !== undefined){
                    skill.level1 = state.levelList.find(lv1 => lv1.two.find(lv2 => lv2.id === skill.belongs) !== undefined);
                    skill.level2 = skill.level1.two.find(lv2 => lv2.id === skill.belongs);
                    return skill;
                }else{
                    return undefined;
                }
            },
            today(state){
                const d = new Date();
                return `${ d.getFullYear() }-${ (d.getMonth() + 1).zeroFormat(2) }-${d.getDate().zeroFormat(2)}`;
            }
        })
    }
</script>

<style scoped>

    .video-container {
        width:100%;
        margin:20px 0;
    }

    .skill-list {
        display: grid;
        grid-template-columns: 1fr;
        grid-auto-rows: 150px;
        gap:20px;
    }

    .skill-level-list {
        border-radius: 0.5rem;
        background-size: cover;
        cursor: pointer;
        position: relative;
        box-shadow: 2px 2px 2px rgba(0,0,0, 0.3);
    }

    .title-box {
        position: absolute;
        bottom:15px;
        right: 15px;
        color:#fff;
        background-color: rgba(0,0,0, 0.5);
    }

    .skill-page {
        display: flex;
        flex-direction: column;
    }

    .skill-page > .image-container {
        width: 100%;
        height:250px;
        position: relative;
        background-size: cover;
    }

    .desc-container {
        padding:0.25rem 0.7rem;
    }

    .contained-skill-list{
        display: grid;
        grid-gap:20px;
        padding:0.25rem 0.7rem;
        grid-template-columns: 1fr 1fr;
        grid-auto-rows: 50px;
    }

    .skill-item {
        padding:8px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 0.5rem;
        color:#fff;
    }

    .certify-page {
        display: grid;
        grid-template-columns: 1fr;
        grid-gap:15px;
    }

    .logo-container > i {
        font-size:30px;
        color: #3352ff;
    }
</style>
