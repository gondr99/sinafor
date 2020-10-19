<template>
    <div class="row">
        <!-- start of skill1 card-->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4>{{trans('title.skill_level_one_list')}}</h4>
                        </div>
                        <div class="col-4 text-right">
                            <button class="btn btn-primary" @click="addLevel1">{{trans('adminmenu.add_level_1')}}</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="skill-list">
                        <div class="card" :class="{active:levelIdx == item.id}" v-for="item in levelList" @click="changeLevel2(item.id)">
                            <img :src="`/images/skills/${item.image}`" alt="skill image">
                            <div class="card-body">
                                <h5 class="card-title">{{item.name}}</h5>
                                <button @click.stop="delLevel1(item.id)" class="btn btn-outline-danger">{{trans('menu.delete')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- end of skill1 card-->

        <!-- start of skill2 card-->
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4>{{trans('title.skill_level_two_list')}} <span v-if="levelIdx != -1"
                                                                              class="badge badge-info badge-pill">{{ selectedLevel.name }}</span></h4>
                        </div>
                        <div class="col-4 text-right">
                            <button class="btn btn-primary" @click="addLevel2" v-if="levelIdx != -1">{{trans('adminmenu.add_level_2')}}</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="skill-list">
                        <div class="card" :class="{active:level2Idx == item.id}" v-for="item in level2" @click="changeSkill(item.id)">
                            <img :src="`/images/skills/${item.image}`" alt="skill image">
                            <div class="card-body">
                                <h5 class="card-title">{{item.name}}</h5>
                                <button @click.stop="delLevel2(item.id)" class="btn btn-outline-danger">{{trans('menu.delete')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of skill2 card-->

        <!-- start of skilllist card-->
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4>{{trans('title.skill_list')}} <span v-if="level2Idx != -1" class="badge badge-info badge-pill">{{ selecteLevel2.name }}</span>
                            </h4>
                        </div>
                        <div class="col-4 text-right">
                            <button class="btn btn-primary" @click="openAddSkill" v-if="level2Idx != -1">{{trans('adminmenu.add_skill')}}</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="skill-list">
                        <div class="card" v-for="item in skillList">
                            <img :src="`/images/icons/${item.filename}`" alt="skill image">
                            <div class="card-body">
                                <h5 class="card-title">{{item.name}}</h5>
                                <div class="title">
                                    {{trans('adminmenu.manager_list')}}
                                </div>
                                <div class="badge-list">
                                    <span class="badge badge-danger" v-if="item.managerList.length === 0">{{trans('adminmenu.no_manager')}}</span>
                                    <span class="badge badge-primary mr-2" v-for="m in item.managerList">{{ m.name }}</span>
                                </div>
                                <div class="tilte">
                                    {{trans('adminmenu.expert_list')}}
                                </div>
                                <div class="badge-list">
                                    <span class="badge badge-danger" v-if="item.expertList.length === 0">{{trans('adminmenu.no_expert')}}</span>
                                    <span class="badge badge-primary mr-2" v-for="m in item.expertList">{{ m.name }}</span>
                                </div>
                                <div class="desc my-2">
                                    {{item.description}}
                                </div>
                                <button @click.stop="delSkill(item.id)" class="btn btn-outline-danger">{{trans('menu.delete')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of skilllist card-->

        <!-- start of popup AddSkill -->
        <transition name="fade">
            <div class="add-skill-popup" v-if="isOpenAddSkill">
                <add-skill-component :level2="level2Idx" @close="closeOpenSkill"></add-skill-component>
            </div>
        </transition>
        <!-- end of popup AddSkill -->
    </div>
    <!--    end of row-->
</template>

<script>

    import {mapState} from "vuex";
    import AddSkillcomponent from "./AddSkillcomponent";

    export default {
        name: "SkillManagementComponent",
        components:{
            'add-skill-component':AddSkillcomponent
        },
        data() {
            return {
                levelIdx: -1,
                level2Idx: -1,
                skillList:[],
                isOpenAddSkill:false,
            }
        },
        mounted() {
            axios.get('/admin/skillLevel').then(res => {
                this.$store.commit('refreshLevels', res.data);
            }).catch(err => {
                console.log(err);
            });
        },
        methods: {
            changeLevel2(idx) {
                this.levelIdx = idx;
                this.level2Idx = -1;
                this.skillList = [];
            },
            changeSkill(idx) {
                this.level2Idx = idx;
                axios.get(`/admin/skillList/${idx}`).then(res => {
                     this.skillList = res.data;
                });
            },
            openAddSkill(){
                this.isOpenAddSkill = true;
            },
            delSkill(skillId){

            },
            closeOpenSkill(data = null){
                if(data !== null){
                    this.skillList.push(data);
                }
                this.isOpenAddSkill = false;
            },
            //add level1 data to server
            async addLevel1() {
                let data = await this.getName();
                if (!data.isDismissed) {
                    const formData = new FormData();
                    formData.append("name", data.value[0]);
                    formData.append("image", data.value[1]);
                    formData.append('desc', data.value[2]);
                    this.uploadData('/admin/levelOne', formData);
                }
            },
            async addLevel2() {
                let data = await this.getName();
                if (!data.isDismissed) {
                    const formData = new FormData();
                    formData.append("name", data.value[0]);
                    formData.append("image", data.value[1]);
                    formData.append('desc', data.value[2]);
                    this.uploadData(`/admin/levelTwo/${this.levelIdx}`, formData);
                }
            },
            uploadData(url, formData) {
                axios.post(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => {
                    console.log(res.data);
                    this.$store.commit('refreshLevels', res.data);
                }).catch(err => {
                    Swal.fire(err.response.data);
                });
            },
            async delLevel1(id) {
                const result = await this.checkSure();
                if (result.isConfirmed) {
                    axios.delete(`/admin/levelOne/${id}`).then(res => {
                        this.$store.commit('refreshLevels', res.data);
                    }).catch(err => {
                        Swal.fire(err.response.data);
                    })
                }
            },
            async delLevel2(id) {
                const result = await this.checkSure();
                if (result.isConfirmed) {
                    axios.delete(`/admin/levelTwo/${this.levelIdx}/${id}`).then(res => {
                        this.$store.commit('refreshLevels', res.data);
                    }).catch(err => {
                        Swal.fire(err.response.data);
                    })
                }
            },

            async getName() {
                const value = await Swal.fire({
                    title: this.trans('messages.enter_category_name'),
                    html: `
<div class="form-row">
    <div class="from-group col-12">
        <label for="categoryName">${this.trans('menu.category_name')}</label>
        <input id="categoryName" type="text" class="form-control">
    </div>
    <div class="from-group col-12">
        <label for="categoryPic">${this.trans('menu.category_image')}</label>
        <input id="categoryPic" class="form-control" type="file" accept="image/*">
    </div>
    <div class="from-group col-12">
        <label for="categoryDesc">${this.trans('menu.category_desc')}</label>
        <textarea id="categoryDesc" class="form-control" placeholder=""></textarea>
    </div>
</div>`,
                    showCancelButton: true,
                    preConfirm: () => {
                        return [
                            document.querySelector("#categoryName").value,
                            document.querySelector("#categoryPic").files[0],
                            document.querySelector("#categoryDesc").value
                        ];
                    }
                });
                return value;
            },
            checkSure() {
                return Swal.fire({
                    title: this.trans('messages.delete'),
                    text: this.trans('messages.sure'),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: this.trans('messages.yes'),
                    cancelButtonText: this.trans('messages.cancel')
                });
            }
        },
        computed: mapState({
            levelList: state => state.levelList,
            level2(state) {
                const item = state.levelList.find(x => x.id == this.levelIdx);
                if (item !== undefined) {
                    return item.two;
                } else {
                    return [];
                }
            },
            selectedLevel(state) {
                return state.levelList.find(x => x.id == this.levelIdx);
            },
            selecteLevel2(state){
                return this.level2.find(x => x.id === this.level2Idx);
            }
        })
    }
</script>

<style scoped>
    .closed {
        position: relative;
        padding-right: 20px;
    }

    .closed > span {
        position: absolute;
        top: 0.375rem;
        right: 5px;
        border: 1px solid #ddd;
        background-color: #fff;
    }

    .closed:hover > span {
        color: #1d643b;
    }

    .skill-list {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-gap: 15px;
    }

    .skill-list .card img {
        height: 150px;
    }

    .card.active {
        border: 1px solid #3276ff;
    }

    .desc {
        border:1px solid #ddd;
        border-radius: 4px;
    }

    .add-skill-popup {
        position: fixed;
        width:100%;
        height:100%;
        top:0;
        left:0;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(0,0,0,0.3);
        z-index: 10;
    }
</style>
