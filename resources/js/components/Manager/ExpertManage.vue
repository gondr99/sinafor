<template>
    <div>
        <skill-register v-for="skill in skillList" :skill="skill" :key="skill.id"></skill-register>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import SkillRegister from "./SkillRegister";
    export default {
        name: "ExpertManage",
        components:{
            'skill-register':SkillRegister
        },
        computed: mapState({
            skillList: state => state.skillList,
        })
    }
</script>

<style scoped>

</style>
