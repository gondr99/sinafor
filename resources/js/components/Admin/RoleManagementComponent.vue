<template>
    <div>
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:mode === 0}" @click="mode = 0">{{trans('adminmenu.expert_list')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:mode === 1}" @click="mode = 1">{{trans('adminmenu.manager_list')}}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <transition name="fade">
                    <div class="content-box p-2" v-if="mode === 0">
                        <expert-role-component :skill-list="skillList" @refresh="refreshSkill"></expert-role-component>
                    </div>
                    <div class="content-box p-2" v-else-if="mode === 1">
                        <manager-role-component :skill-list="skillList" @refresh="refreshSkill"></manager-role-component>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
    import ExpertRoleComponent from "./ExpertRoleComponent";
    import ManagerRoleComponent from "./ManagerRoleComponent";

    export default {
        name: "RoleManagementComponent",
        components:{
            'expert-role-component':ExpertRoleComponent,
            'manager-role-component':ManagerRoleComponent
        },
        mounted() {
            axios.get('/admin/expertList').then(res => this.expertList = res.data);
            axios.get('/admin/managerList').then(res => this.managerList = res.data);
            axios.get('/admin/skillList').then(res => this.skillList = res.data);
        },
        data(){
            return {
                mode:0,
                expertList:[],
                managerList:[],
                skillList:[],
            }
        },
        methods:{
            refreshSkill(){
                axios.get('/admin/skillList').then(res => this.skillList = res.data);
            }
        }
    }
</script>

<style scoped>
    .content-box {
        border: 1px solid #ddd;
        border-top:none;
    }
</style>

