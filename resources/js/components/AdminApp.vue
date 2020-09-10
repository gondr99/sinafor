<template>
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" :class="{active:menuIndex == 0}" @click="menuIndex = 0">{{  trans('adminmenu.user_category') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{active:menuIndex == 1}" @click="menuIndex = 1">{{  trans('adminmenu.skill_category') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{active:menuIndex == 2}" @click="menuIndex = 2">{{ trans('adminmenu.user_manage')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{active:menuIndex == 3}" @click="menuIndex = 3">Not yet..</a>
                </li>
            </ul>
            <div class="box p-4">
                <user-category v-if="menuIndex === 0"></user-category>
                <skill-category v-if="menuIndex === 1"></skill-category>
                <user-manage v-if="menuIndex === 2"></user-manage>
            </div>
        </div>
    </div>
</template>

<script>
    import UserCategoryComponent from "./Admin/UserCategoryComponent";
    import SkillCategoryComponent from "./Admin/SkillCategoryComponent";
    import UserManageComponent from "./Admin/UserManageComponent";

    export default {
        name: "AdminApp",
        components:{
            'user-category':UserCategoryComponent,
            'skill-category':SkillCategoryComponent,
            'user-manage':UserManageComponent,
        },
        mounted() {

        },
        data(){
            return {
                menuIndex:1
            }
            //for debug menuIndex value fixed to 1
        }
    }
</script>

<style scoped>

</style>
