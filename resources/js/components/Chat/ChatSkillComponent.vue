<template>
    <div>
        <div class="loading" v-if="expert === undefined">
            <div class="spin-container"><i class="fas fa-spinner"></i></div>
        </div>
        <div class="item" v-if="expert !== undefined">
            <div class="pic">
                <img :src="`/images/profiles/${expert.profile}`" alt="">
            </div>
            <div class="info">
                <div class="title-bar bg-primary text-white px-1">
                    <h4>{{skill.name}}</h4>
                </div>
                <div class="content p-2">
                    <div class="tags">
                        <span class="tag bg-dark text-white">{{trans('title.expert')}}</span>
                        <span class="tag bg-primary text-white">{{expert.name}}</span>
                    </div>
                    <h4>Detail message</h4>
                    <p>{{skill.detail}}</p>
                    <div class="button-row text-right">
                        <button class="btn btn-primary" @click="openChat">{{trans('menu.start_chat')}} <span class="badge badge-light" v-if="unreadCnt !== 0">{{unreadCnt}}</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ChatSkillComponent",
        props:{
            skill:Object,
            unreadList:Array,
            user:Object
        },
        data(){
            return {
                expert:undefined
            }
        },
        created() {
            axios.get(`/user/${this.skill.expert_id}`).then(res => {
                this.expert = res.data;
            })
        },
        methods:{
            openChat(){
                this.$emit('openchat', this.expert);
            }
        },
        computed:{
            unreadCnt(){
                const data = this.unreadList.find(x => x.expert_id === this.skill.expert_id && x.user_id === this.user.id);
                return data !== undefined ? data.unread : 0;
            }
        }
    }
</script>

<style scoped>
    .item {
        display: grid;
        grid-template-columns: 150px 1fr;
    }

    .pic {
        width:100%;
        height:100%;
    }

    .pic > img {
        width:100%;
        height:100%;
        object-fit: cover;
    }

    .info {
        box-shadow: 0 0 5px 5px rgba(0,0,0, 0.1);
        display: grid;
        grid-template-rows: 28px 1fr;
    }
    .content {
        display: grid;
        grid-template-columns: 1fr;
        grid-template-rows: 25px 25px 1fr 30px;
        height: 100%;
    }
</style>
