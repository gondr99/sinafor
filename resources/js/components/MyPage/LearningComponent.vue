<template>
    <div class="skill-container">
        <learn-skill-component v-for="skill in registeredList" :key="skill.id" :skill="skill"></learn-skill-component>
    </div>
</template>

<script>
    import LearnSkillComponent from "./LearnSkillComponent";
    import {mapState} from 'vuex';
    export default {
        name: "LearningComponent",
        components:{
            'learn-skill-component':LearnSkillComponent,
        },
        computed:mapState({
            registeredList: state => state.registeredList,
        })
    }
</script>

<style>
    .skill-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 300px;
        gap:10px;
    }
</style>
