<template>

    <div class="row">
        <div class="col-2">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link" :class="{active:menuIndex == 0}" @click="menuIndex = 0">{{  trans('menu.expert_manage') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{active:menuIndex == 1}" @click="menuIndex = 1">{{  trans('menu.curriculum_manage')}}</a>
                </li>
            </ul>
        </div>
        <div class="col-10">
            <div class="box p-2">
                <expert-manage v-if="menuIndex === 0"></expert-manage>
                <curriculum-manage v-if="menuIndex === 1"></curriculum-manage>
            </div>
        </div>
    </div>

</template>

<script>
    import ExpertManage from "./Manager/ExpertManage";
    import CurriculumManage from "./Manager/CurriculumManage";
    export default {
        name: "ManagerApp",
        mounted() {
            axios.get('/manager/skill').then(res => {
               this.$store.commit('refreshSkillList', res.data);
            });
        },
        components:{
            'expert-manage':ExpertManage,
            'curriculum-manage':CurriculumManage,
        },
        data(){
            return {
                menuIndex:1
            }
        }
    }
</script>

<style scoped>

</style>
