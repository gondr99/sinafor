<template>
    <div class="item">
        <span class="name">{{item.name}}</span>
        <span class="day">{{item.created_at}}</span>
        <span class="badge badge-pill badge-primary">{{status}}</span>
    </div>
</template>

<script>
    export default {
        name: "SkillItem",
        props:["item"],
        data(){
            return {
                statusLabel:[
                    this.trans('menu.waiting_for_accept'),
                    this.trans('menu.progress'),
                    this.trans('menu.complete')
                ]
            }
        },
        computed:{
            status(){
                return this.statusLabel[this.item.status];
            }
        }
    }
</script>

<style scoped>
    .item {
        border:1px solid #ddd;
        border-radius: 5px;
        padding:8px 12px;
        display: flex;
        justify-content: space-between;
    }
</style>
