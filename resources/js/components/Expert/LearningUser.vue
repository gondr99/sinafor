<template>
    <div class="user">
        <div class="name">{{user.name}}</div>
        <div class="email">{{user.email}}</div>
        <div class="button-col">
            <button v-if="user.status === 1" @click="expertConfirm" class="btn btn-outline-primary btn-sm">{{trans('menu.wait_for_expert_confirm')}}</button>
            <button v-if="user.status === 2" class="btn btn-outline-primary btn-sm">{{trans('menu.progress')}}</button>
            <button v-if="user.status === 2" @click="expertCertificate" class="btn btn-outline-danger btn-sm">{{trans('menu.expert_certificate')}}</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "LearningUser",
        props:{
            'user':Object,
            'skillId':Number,
        },
        data(){
            return {
                complete:0,
            }
        },
        mounted() {

        },
        methods:{
            expertConfirm(){
                this.showSwal(this.trans('messages.sure'),this.trans(`messages.expert_confirm_text`)).then( result => {
                    if (result.isConfirmed) {
                        axios.put(`/expert/confirm`, {userId:this.user.id, skillId:this.skillId}).then(res =>{
                            this.$store.commit('refreshSkillList', res.data);
                        });
                    }
                });
            },
            expertCertificate(){
                this.showSwal(this.trans('messages.sure'),this.trans(`messages.expert_certificate_text`)).then( result => {
                    if (result.isConfirmed) {
                        axios.put(`/expert/certificate`, {userId:this.user.id, skillId:this.skillId}).then(res =>{
                            this.$store.commit('refreshSkillList', res.data);
                        });
                    }
                });
            },
            showSwal(title, text){
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                });
            }
        },
        computed:{

        }
    }
</script>

<style scoped>
    .user {
        padding:0.75rem;
        border-bottom:1px solid #ddd;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
    }
    .button-col {
        display: flex;
        justify-content: space-between;
    }
</style>
