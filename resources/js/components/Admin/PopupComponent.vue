<template>
    <div id="popupTemplate">
        <div class="inner">
            <div class="row">
                <div class="col-10 offset-1">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>{{item.name}} {{trans('adminmenu.manager_list')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="user-list p-2">
                                <div class="user" v-for="m in managerList">
                                    <span class="name">{{m.name}}</span>
                                    <button @click="dropManager(m.id)" class="btn btn-sm btn-outline-primary">{{trans('adminmenu.drop_manager')}}</button>
                                </div>
                            </div>
                            <div class="card-text" v-if="managerList.length === 0"> {{ trans('messages.no_manager') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-10 offset-1">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{item.name}} {{ trans('adminmenu.add_manager') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="card-text" v-if="managerPermissionList.length === 0"> {{ trans('messages.no_manager') }}</div>
                            <div class="user-list p-2">
                                <user-info-component v-for="user in managerPermissionList" :user="user" :key="user.id" :skill-id="item.id" @closePopup="close"></user-info-component>
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
    import UserInfoComponent from "./UserInfoComponent";
    export default {
        name: "PopupComponent",
        components:{
            'user-info-component' : UserInfoComponent
        },
        props:['item'],
        mounted() {
            axios.get('/admin/skill/manager').then(res => {
                this.managerPermissionList = res.data.filter( x =>  this.item.managerList.find(manager => manager.id === x.id) === undefined);
            });
        },
        methods:{
            close(){
                this.$emit("closePopup");
            },
            dropManager(user_id){
                Swal.fire({
                    title: this.trans('messages.sure'),
                    text: this.trans('adminmenu.drop_manager'),
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/admin/skill/manager/${this.item.id}?uid=${user_id}`).then(res =>{
                            this.$emit("closePopup");
                        });
                    }
                });

            }
        },
        data(){
            return{
                managerPermissionList:[]
            }
        },
        computed:{
            managerList(){
                return this.item.managerList;
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
