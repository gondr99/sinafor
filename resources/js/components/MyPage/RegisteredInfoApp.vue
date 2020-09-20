<template>
    <div>
        <h4>{{trans('title.registered_skill_list')}}</h4>
        <div class="row">
            <div class="col-12 grid-list">
                <skill-item v-for="skill in registerList" :item="skill" :key="skill.id"></skill-item>
            </div>
        </div>

    </div>
</template>

<script>
    import SkillItem from "./SkillItem";
    import {mapState} from 'vuex';

    export default {
        name: "RegisteredInfoApp",
        components:{
            'skill-item':SkillItem
        },
        computed:{

        },
        computed: mapState({
            registerList: state => state.registeredList,
        })
    }
</script>

<style scoped>
    .grid-list {
        display: grid;
        grid-template-columns: 1fr;
        gap:10px;
    }
</style>
