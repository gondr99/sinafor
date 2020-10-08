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
                    <div class="skill-list">
                        <div class="card" v-for="item in level2">
                            <img :src="`/images/skills/${item.image}`" alt="skill image">
                            <div class="card-body">
                                <h5 class="card-title">{{item.name}}</h5>
                                <button @click.stop="delLevel2(item.id)" class="btn btn-outline-danger">{{trans('menu.delete')}}</button>
                            </div>
                        </div>
                    </div>
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
            //add level1 data to server
            async addLevel1(){
                let data = await this.getName();
                if(!data.isDismissed){
                    const formData = new FormData();
                    formData.append("name", data.value[0]);
                    formData.append("image", data.value[1]);
                    formData.append('desc', data.value[2]);
                    this.uploadData('/admin/levelOne', formData);
                }
            },
            async addLevel2(){
                let data = await this.getName();
                if(!data.isDismissed){
                    const formData = new FormData();
                    formData.append("name", data.value[0]);
                    formData.append("image", data.value[1]);
                    formData.append('desc', data.value[2]);
                    this.uploadData(`/admin/levelTwo/${this.levelIdx}`, formData);
                }
            },
            uploadData(url, formData){
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
            async delLevel1(id){
                const result = await this.checkSure();
                if (result.isConfirmed){
                    axios.delete(`/admin/levelOne/${id}`).then(res => {
                        this.$store.commit('refreshLevels', res.data);
                    }).catch(err => {
                        Swal.fire(err.response.data);
                    })
                }
            },
            async delLevel2(id){
                const result = await this.checkSure();
                if (result.isConfirmed){
                    axios.delete(`/admin/levelTwo/${this.levelIdx}/${id}`).then(res => {
                        this.$store.commit('refreshLevels', res.data);
                    }).catch(err => {
                        Swal.fire(err.response.data);
                    })
                }
            },
            async getName(){
                const value = await Swal.fire({
                    title: this.trans('messages.enter_category_name'),
                    html:`
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
                    preConfirm: () =>{
                        return [
                            document.querySelector("#categoryName").value,
                            document.querySelector("#categoryPic").files[0],
                            document.querySelector("#categoryDesc").value
                        ];
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

    .skill-list{
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-gap:15px;
    }

    .skill-list .card img {
        height:200px;
    }

    .card.active {
        border:1px solid #3276ff;
    }
</style>
