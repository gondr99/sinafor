<template>
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
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" :placeholder="trans('placeholder.add_skill_category')"
                               v-model="skillName">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="uploadSpan">{{ trans('placeholder.upload_image_file') }}</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="iconFile" @change="changeFile" accept="image/*">
                            <label class="custom-file-label" for="iconFile">{{ imgText }}</label>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <textarea class="form-control" v-model="description" rows="3" :placeholder="trans('placeholder.description')"></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-outline-secondary" type="button" @click="addSkill">
                        {{ trans('menu.add') }}
                    </button>
                </div>
            </div>


        </div>
        <div class="col-12 card-box">
            <skill-component v-for="item in skillList" :admin="true" :item="item" :key="item.id"></skill-component>
        </div>

        <transition name="fade">
            <popup-component :item="currentPopupItem" v-if="openPopup" @closePopup="closePopup"></popup-component>
        </transition>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import SkillComponent from "./SkillComponent";
    import PopupComponent from "./PopupComponent";

    export default {
        name: "SkillCategoryComponent",
        components:{
            'skill-component': SkillComponent,
            'popup-component': PopupComponent
        },
        mounted() {
            this.refreshSkillData();
        },
        methods:{
            addSkill(){
                if(this.skillName.trim() === "" || this.file === "" || this.description.trim() === "" || this.level2 === undefined){
                    Swal.fire(this.trans('messages.require_empty'));
                    return;
                }

                let formData = new FormData();
                formData.append('level2', this.level2);
                formData.append('name', this.skillName);
                formData.append('image', this.file);
                formData.append('description', this.description);

                axios.post('/admin/skill', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => {
                    const data = res.data;
                    //console.log(data);
                    this.$store.commit('addSkill', data);
                }).catch( err => {
                    Swal.fire(err.response.data);
                });
            },
            changeFile(e){
                this.file = e.target.files[0];
            },
            openManagerWindow(id){
                this.currentPopupItem = this.skillList.find( x => x.id == id);
                this.openPopup = true;
            },
            closePopup(){
                this.refreshSkillData();
                this.openPopup = false;
            },
            refreshSkillData(){
                axios.get('/admin/skill').then( res=>{
                    this.$store.commit('refreshSkillList', res.data);
                }).catch(err => {
                    console.log(err);
                });
            }
        },
        data(){
            return {
                skillName:'',
                file:'',
                description:'',
                openPopup:false,
                currentPopupItem:{},
                level1:undefined,
                level2:undefined,
            }
        },
        watch:{
              level1(newValue){
                  this.level2 = undefined;
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
            skillList: state => state.skillList,
            imgText (state){
                if(this.file === ''){
                    return this.trans('placeholder.choose_image_file')
                }else {
                    return this.file.name;
                }
            }
        })
    }
</script>

<style>
    .card-box {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap:20px;
        padding:20px;
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }


</style>
