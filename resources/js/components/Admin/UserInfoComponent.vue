<template>
    <div class="user-card">
        <div class="user">
            <span>{{user.name}}</span>
            <span>{{user.email}}</span>
            <span>{{user.phone}}</span>
            <div class="roles">
                <button @click="removeRole(role.id)" class="btn btn-sm btn-outline-primary mr-2" v-for="role in user.roles" :key="role.id">{{role.name}}
                </button>
            </div>
            <div class="menu" v-if="skillId === 0">
                <button class="btn btn-sm btn-outline-primary" v-if="!hasExpert" @click="approveExpert">{{ trans('adminmenu.approve_expert')}}</button>
                <button class="btn btn-sm btn-outline-success" v-if="!hasManager" @click="approveManage">{{ trans('adminmenu.approve_manager')}}</button>
            </div>

            <div class="menu" v-if="skillId !== 0">
                <button class="btn btn-sm btn-outline-primary" @click="setApprove">{{ trans('adminmenu.set_approve')}}</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UserInfoComponent",
        props: {
            'user': Object,
            'skillId':{
                type:Number,
                default:0
            },
            'mode':{
                default:0,
                type:Number
            }
        },
        data(){
            return {
                message:[
                    'set_manager',
                    'set_expert'
                ]
            }
        },
        methods: {
            setApprove(){
                Swal.fire({
                    title: this.trans('messages.sure'),
                    text: this.trans(`adminmenu.${this.message[this.mode]}`),
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if(this.mode === 0){
                            axios.put(`/admin/skill/manager/${this.skillId}`,{ user_id:this.user.id }).then(res => {
                                this.$emit('closePopup');
                            });
                        }else if(this.mode === 1){
                            axios.put(`/admin/skill/expert/${this.skillId}`,{ user_id:this.user.id }).then(res => {
                                this.$emit('closePopup');
                            });
                        }
                    }
                });

            },
            approveExpert() {
                axios.put('/admin/user/role', {user_id: this.user.id, category_name: roleList.expert}).then(res => {
                    const data = res.data;
                    this.$emit('refreshUser', this.user.id);
                });
            },
            approveManage() {
                axios.put('/admin/user/role', {user_id: this.user.id, category_name: roleList.manager}).then(res => {
                    const data = res.data;
                    this.$emit('refreshUser', this.user.id);
                });
            },
            removeRole(id) {
                Swal.fire({
                    title: this.trans('messages.sure'),
                    text: this.trans('messages.delete'),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/admin/user/role?cid=${id}&uid=${this.user.id}`).then(res => {
                            this.$emit('refreshUser', this.user.id);
                        }).catch(err => {
                            Swal.fire(err.response.data);
                        });
                    }
                });
            }
        },
        computed: {
            hasExpert() {
                if (this.user.roles === undefined) return false;
                return this.user.roles.find(x => x.name === roleList.expert) !== undefined;
            },
            hasManager() {
                if (this.user.roles === undefined) return false;
                return this.user.roles.find(x => x.name === roleList.manager) !== undefined;
            }
        }
    }
</script>

<style scoped>
    .user {
        display: grid;
        grid-template-columns: 1fr 1fr 2fr 2fr 2fr;
        width: 100%;
        padding:15px;
    }

    .user-card {
        display: flex;
        align-items: center;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
</style>
