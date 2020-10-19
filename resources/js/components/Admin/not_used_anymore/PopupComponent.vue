<template>
    <div id="popupTemplate">
        <div class="inner">
            <div class="row">
                <div class="col-10 offset-1">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>{{item.name}} {{trans(`adminmenu.${modeName[mode][0]}`)}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="user-list p-2">
                                <div class="user" v-for="m in userList">
                                    <span class="name">{{m.name}}</span>
                                    <button @click="dropManager(m.id)" class="btn btn-sm btn-outline-primary">{{trans(`adminmenu.${modeName[mode][1]}`)}}</button>
                                </div>
                            </div>
                            <div class="card-text" v-if="userList.length === 0"> {{trans(`adminmenu.${modeName[mode][2]}`)}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-10 offset-1">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{item.name}} {{ trans(`adminmenu.${modeName[mode][3]}`) }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="card-text" v-if="permissionList.length === 0"> {{trans(`adminmenu.${modeName[mode][4]}`)}}</div>
                            <div class="user-list p-2">
                                <user-info-component v-for="user in permissionList" :user="user" :key="user.id" :skill-id="item.id" @closePopup="close" :mode="mode"></user-info-component>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-outline-dark btn-sm" @click="close">닫기</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-10 offset-1">

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import UserInfoComponent from "../UserInfoComponent";
    export default {
        name: "PopupComponent",
        components:{
            'user-info-component' : UserInfoComponent
        },
        props:['item', 'mode'],
        mounted() {
            if(this.mode === 0){
                axios.get('/admin/skill/manager').then(res => {
                    this.permissionList = res.data.filter( x =>  this.item.managerList.find(manager => manager.id === x.id) === undefined);
                });
            } else if(this.mode === 1) {
                axios.get('/admin/skill/expert').then(res => {
                    this.permissionList = res.data.filter( x =>  this.item.expertList.find(exp => exp.id === x.id) === undefined);
                });
            }
        },
        methods:{
            close(){
                this.$emit("closePopup");
            },
            dropManager(user_id){
                Swal.fire({
                    title: this.trans('messages.sure'),
                    text: this.trans(`adminmenu.${this.modeName[this.mode][1]}`),
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if(this.mode == 0){
                            axios.delete(`/admin/skill/manager/${this.item.id}?uid=${user_id}`).then(res =>{
                                this.$emit("closePopup");
                            });
                        }else if(this.mode == 1){
                            axios.delete(`/admin/skill/expert/${this.item.id}?uid=${user_id}`).then(res =>{
                                this.$emit("closePopup");
                            });
                        }

                    }
                });

            }
        },
        data(){
            return{
                permissionList:[],
                modeName:[
                    ['manager_list', 'drop_manager', 'no_manager', 'add_manager', 'no_manager'],
                    ['expert_list', 'drop_expert', 'no_expert', 'add_expert', 'no_expert'],
                ]
            }
        },
        computed:{
            userList(){
                if(this.mode === 0){
                    return this.item.managerList;
                }else if(this.mode === 1){
                    return this.item.expertList;
                }
            }
        }
    }
</script>

<style>
    #popupTemplate {
        position: fixed;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background-color: rgba(0,0,0, 0.3);
        z-index: 10;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .inner{
        width:80%;
        background-color: #fff;
        border-radius: 10px;
        padding:20px;
    }
    .user-list {
        display: grid;
        grid-template-columns: 1fr;
        gap:20px;
    }
</style>
