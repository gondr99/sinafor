<template>
    <div class="skill-teach">
        <expert-skill v-for="skill in skillList" :key="skill.id" :skill="skill"></expert-skill>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import ExpertSkill from "./ExpertSkill";

    export default {
        name: "SkillTeach",
        components:{
            'expert-skill':ExpertSkill
        },
        computed:mapState({
            skillList: state => state.skillList,
        }),
    }
</script>

<style>
    .skill-teach{
        position: relative;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 0.25rem;
        padding:0.7rem;
        display: grid;
        grid-template-columns: 1fr;
        gap:10px;
    }
</style>
