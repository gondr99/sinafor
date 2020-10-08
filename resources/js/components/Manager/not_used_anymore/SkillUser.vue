<template>
    <div class="user">
        <div class="user-name">{{user.name}}( {{user.email }} )</div>
        <select class="form-control" v-model.number="expertId">
            <option value="0">{{trans('placeholder.choose_expert')}}</option>
            <option v-for="expert in expertList" :value="expert.id">{{expert.name}} ( {{expert.email }} )</option>
        </select>
        <div class="button-box">
            <button @click="accept" class="btn btn-outline-primary btn-sm">{{trans('menu.accept')}}</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SkillUser",
        props:['user', 'expertList', 'skillId'],
        data(){
            return {
                expertId:0
            }
        },
        methods:{
            accept(){
                console.log(this.user.id, this.expertId);
                if(this.expertId === 0){
                    Swal.fire(this.trans('messages.choose_expert'));
                    return;
                }

                axios.put('/manager/user', {userId:this.user.id, expertId : this.expertId, skillId:this.skillId }).then( res => {
                    this.$store.commit("refreshSkillList", res.data);
                    Swal.fire(this.trans('messages.expert_connected'));
                });
            }
        }

    }
</script>

<style scoped>
    .user {
        padding:0.25rem;
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 0.25rem;
        background-color: #fff;
        display: grid;
        grid-template-columns: 1fr 2fr 1fr;
        gap:10px;
    }

    .user > div {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
