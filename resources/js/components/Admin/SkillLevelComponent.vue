<template>
    <div class="row">
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
                    <div class="btn btn-outline-success closed" :class="{active:levelIdx == item.id}" v-for="item in levelList" @click="changeLevel2(item.id)">{{item.name}}<span @click.stop="delLevel1(item.id)" class="dismiss">X</span></div>
                </div>
            </div>
<!--            end of card-->
        </div>

        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4>{{trans('title.skill_level_two_list')}} <span v-if="levelIdx != -1" class="badge badge-info badge-pill">{{ selectedLevel.name }}</span></h4>
                        </div>
                        <div class="col-4 text-right">
                            <button class="btn btn-primary" @click="addLevel2" v-if="levelIdx != -1">{{trans('adminmenu.add_level_2')}}</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="btn btn-outline-success closed" v-for="item in level2">{{item.name}}<span class="dismiss" @click.stop="delLevel2(item.id)">X</span></div>
                </div>
            </div>
            <!--            end of card-->
        </div>
    </div>
<!--    end of row-->
</template>

<script>

    import {mapState} from "vuex";

    export default {
        name: "SkillLevelComponent",
        data(){
            return {
                levelIdx:-1,
            }
        },
        mounted() {
            axios.get('/admin/skillLevel').then( res=>{
                this.$store.commit('refreshLevels', res.data);
            }).catch(err => {
                console.log(err);
            });
        },
        methods:{
            changeLevel2(idx){
                this.levelIdx = idx;
            },
            async addLevel1(){
                let data = await this.getName();
                if(!data.isDismissed){
                    const name = data.value;
                    axios.post('/admin/levelOne', {name}).then(res => {
                        this.$store.commit('refreshLevels', res.data);
                    }).catch(err => {
                        console.log(err);
                    });
                }
            },
            async addLevel2(){
                let data = await this.getName();
                if(!data.isDismissed){
                    const name = data.value;
                    axios.post(`/admin/levelTwo/${this.levelIdx}`, {name}).then(res => {
                        this.$store.commit('refreshLevels', res.data);
                    }).catch(err => {
                        console.log(err);
                    });
                }
            },
            async delLevel1(id){
                const result = await this.checkSure();
                if (result.isConfirmed){
                    axios.delete(`/admin/levelOne/${id}`).then(res => {
                        this.$store.commit('refreshLevels', res.data);
                    }).catch(err => {
                        console.log(err);
                    })
                }
            },
            async delLevel2(id){
                const result = await this.checkSure();
                if (result.isConfirmed){
                    axios.delete(`/admin/levelTwo/${this.levelIdx}/${id}`).then(res => {
                        this.$store.commit('refreshLevels', res.data);
                    }).catch(err => {
                        console.log(err);
                    })
                }
            },
            async getName(){
                const value = await Swal.fire({
                    title: this.trans('messages.enter_name'),
                    input: 'text',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return this.trans('messages.empty');
                        }
                    }
                });
                return value;
            },
            checkSure(){
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
            level2 (state){
                const item = state.levelList.find(x => x.id == this.levelIdx);
                if(item !== undefined){
                    return item.two;
                }else {
                    return [];
                }
            },
            selectedLevel(state){
                return state.levelList.find(x => x.id == this.levelIdx);
            }
        })
    }
</script>

<style scoped>
    .closed {
        position: relative;
        padding-right:20px;
    }

    .closed > span {
        position: absolute;
        top:0.375rem;
        right:5px;
        border:1px solid #ddd;
        background-color: #fff;
    }

    .closed:hover > span {
        color: #1d643b;
    }
</style>
