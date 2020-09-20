<template>
    <div class="row">
        <div class="col-2">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link" :class="{active:menuIndex == 0}" @click="menuIndex = 0">{{  trans('adminmenu.skill_level') }}</a>
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
        </div>
        <div class="col-10">
            <div class="box p-2">
                <skill-level v-if="menuIndex === 0"></skill-level>
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
    import SkillLevelComponent from "./Admin/SkillLevelComponent";

    export default {
        name: "AdminApp",
        components:{
            'user-category':UserCategoryComponent,
            'skill-category':SkillCategoryComponent,
            'user-manage':UserManageComponent,
            'skill-level':SkillLevelComponent,
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
