<template>
    <div>
        <div class="item">
            <div class="pic">
                <img :src="`/images/profiles/${user.profile}`" alt="">
            </div>
            <div class="info">
                <div class="title-bar bg-primary text-white px-1">
                    <h4>{{user.skill.name}}</h4>
                </div>
                <div class="content p-2">
                    <div class="tag-bar">
                        <div class="tags mb-2">
                            <span class="tag bg-dark text-white">{{trans('title.register_user')}}</span>
                            <span class="tag bg-primary text-white">{{user.name}}</span>
                        </div>

                        <div class="tags">
                            <span class="tag bg-dark text-white">{{trans('title.phase')}}</span>
                            <span class="tag bg-danger text-white">{{user.phase === 5 ? trans('title.complete') : user.phase}}</span>
                        </div>
                    </div>

                    <h4>Detail message</h4>
                    <p>{{user.detail}}</p>
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
        name: "ChatUserComponent",
        props:{
            user:Object,
            unreadList:Array,
            expert:Object
        },
        data(){
            return {

            }
        },
        methods:{
            openChat(){
                this.$emit('openchat', this.user);
            }
        },
        computed:{
            unreadCnt(){
                const data = this.unreadList.find(x => x.expert_id === this.expert.id && x.user_id === this.user.id);
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
        gap:10px;
    }

    .tag-bar {
        display: flex;
        justify-content: space-between;
    }
</style>
