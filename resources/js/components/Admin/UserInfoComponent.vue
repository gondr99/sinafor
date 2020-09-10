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
            <div class="menu" v-if="skillId == 0">
                <button class="btn btn-sm btn-outline-primary" v-if="!hasExpert" @click="approveExpert">{{ trans('adminmenu.approve_expert')}}</button>
                <button class="btn btn-sm btn-outline-success" v-if="!hasManager" @click="approveManage">{{ trans('adminmenu.approve_manager')}}</button>
            </div>

            <div class="menu" v-if="skillId != 0">
                <button class="btn btn-sm btn-outline-primary" @click="setManager">{{ trans('adminmenu.set_manager')}}</button>
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
            }
        },
        methods: {
            setManager(){
                Swal.fire({
                    title: this.trans('messages.sure'),
                    text: this.trans('adminmenu.set_manager'),
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.put(`/admin/skill/manager/${this.skillId}`,{ user_id:this.user.id }).then(res => {
                            this.$emit('closePopup');
                        });
                    }
                });

            },
            approveExpert() {
                axios.put('/admin/user/role', {user_id: this.user.id, category_name: 'Expert'}).then(res => {
                    const data = res.data;
                    this.$emit('refreshUser', this.user.id);
                });
            },
            approveManage() {
                axios.put('/admin/user/role', {user_id: this.user.id, category_name: 'Skill Manager'}).then(res => {
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
                return this.user.roles.find(x => x.name === 'Expert') !== undefined;
            },
            hasManager() {
                if (this.user.roles === undefined) return false;
                return this.user.roles.find(x => x.name === 'Skill Manager') !== undefined;
            }
        }
    }
</script>

<style scoped>
    .user {
        display: grid;
        grid-template-columns: 1fr 1fr 2fr 2fr 2fr;
        width: 100%;
    }

    .user-card {
        display: flex;
        align-items: center;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
</style>
