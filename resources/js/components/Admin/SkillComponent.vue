<template>
    <div class="card">
        <div class="img-box">
            <img class="card-inner-image" :src="`/images/icons/${item.filename}`" alt="skill-image">
            <div class="card-manager-list">
                <span class="badge badge-danger" v-if="item.managerList.length == 0">{{trans('adminmenu.no_manager')}}</span>
                <span class="badge badge-primary p-1 mr-1" v-for="manager in item.managerList" :key="manager.id">{{manager.name}}</span>
            </div>
        </div>
        <div class="card-body">
            <h5 class="card-title">{{item.name}}</h5>
            <p class="card-text">{{item.description}}</p>

            <div class="item-menu">
                <button class="btn btn-sm btn-outline-success" @click="openManagerWindow">{{ trans('menu.manager') }}
                </button>
                <button class="btn btn-sm btn-outline-danger" @click="delSkill">{{ trans('menu.delete') }}(미구현)</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SkillComponent",
        props: ['item'],
        methods: {
            delSkill() {

            },
            openManagerWindow() {
                this.$parent.openManagerWindow(this.item.id);
            }
        }
    }
</script>

<style>
    .card > .img-box {
        position: relative;
    }
    .card-inner-image {
        width: 100%;
        height: 100%;
        max-height: 200px;
        object-fit: cover;
        object-position: center center;
    }

    .card-manager-list {
        position: absolute;
        width:60%;
        right:10px;
        bottom:10px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-gap:10px;
    }

</style>
