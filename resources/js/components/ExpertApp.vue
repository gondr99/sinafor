<template>

    <div class="row">
        <div class="col-2">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link" :class="{active:menuIndex == 0}" @click="menuIndex = 0">{{  trans('menu.skill_manage') }}</a>
                </li>
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link" :class="{active:menuIndex == 1}" @click="menuIndex = 1">{{  trans('menu.curriculum_manage')}}</a>-->
<!--                </li>-->
            </ul>
        </div>
        <div class="col-10">
            <div class="box p-2">
                <skill-teach v-if="menuIndex == 0"></skill-teach>
            </div>
        </div>
    </div>

</template>

<script>
    import SkillTeach from "./Expert/SkillTeach";
    export default {
        name: "ExpertApp",
        components:{
            'skill-teach':SkillTeach
        },
        mounted() {
            axios.get('/expert/skill').then(res => {
                this.$store.commit('refreshSkillList', res.data);
            });
        },

        data(){
            return {
                menuIndex:0
            }
        }
    }
</script>

<style scoped>

</style>
