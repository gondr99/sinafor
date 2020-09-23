<template>
    <div class="learn-skill-card" :style="{'background-image':`url(/images/icons/${skill.filename})`}">
        <div class="info-box d-flex justify-content-center align-items-center">
            <h5 class="title">{{skill.name}}</h5>
        </div>
        <div class="button-box">
            <a :href="`/skill/learn/${skill.id}`" class="btn btn-primary">{{ trans('menu.start_learning')}}</a>
        </div>
    </div>
</template>

<script>
    export default {
        name: "LearnSkillComponent",
        props:{
            skill:Object
        }
    }
</script>

<style scoped>
    .learn-skill-card{
        display: flex;
        background-size: cover;
        justify-content: center;
        align-items: center;
        background-position: center center;
        position: relative;
    }
    .info-box {
        background-color: #fff;
        border-radius: 0.25rem;
        padding:8px 12px;
        box-shadow: 2px 2px 2px 2px rgba(0,0,0,0.3);
    }
    .button-box {
        position: absolute;
        width:100%;
        bottom:10px;
        left:0;
        display: flex;
        justify-content: flex-end;
        padding:8px;
        background-color: rgba(255, 255, 255, 0.8);
    }
    .title {
        padding:0;
        margin: 0;
    }
</style>
