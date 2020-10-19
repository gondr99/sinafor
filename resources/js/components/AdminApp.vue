<template>
    <div class="desktop-box">
        <div class="row">
            <div class="col-2">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:menuIndex == 0}" @click="menuIndex = 0">{{  trans('adminmenu.skill_management') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:menuIndex == 1}" @click="menuIndex = 1">{{  trans('adminmenu.role_management') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:menuIndex == 2}" @click="menuIndex = 2">{{  trans('adminmenu.user_manage') }}</a>
                    </li>
                </ul>
            </div>
            <div class="col-10">
                <div class="box p-2">
                    <skill-management-component v-if="menuIndex === 0"></skill-management-component>
                    <role-management-component v-if="menuIndex === 1"></role-management-component>
                    <user-manage-component v-if="menuIndex === 2"></user-manage-component>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import RoleManagementComponent from "./Admin/RoleManagementComponent";
    import UserManageComponent from "./Admin/UserManageComponent";
    import SkillManagementComponent from "./Admin/SkillManagementComponent";

    export default {
        name: "AdminApp",
        components:{
            'user-manage-component':UserManageComponent,
            'skill-management-component':SkillManagementComponent,
            'role-management-component': RoleManagementComponent
        },
        mounted() {

        },
        data(){
            return {
                menuIndex:0
            }
            //for debug menuIndex value fixed to 1
        }
    }
</script>

<style scoped>

</style>
