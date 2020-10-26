<template>
    <div class="inner-window">
        <div class="profile-header">
            <div class="profile">
                <img :src="`/images/profiles/${other.profile}`" alt="profile">
            </div>
            <div class="info p-2">
                <div class="tags mb-2">
                    <span class="tag bg-dark text-white">{{trans('title.user_name')}}</span>
                    <span class="tag bg-primary text-white">{{other.name}}</span>
                </div>
            </div>
        </div>
        <div class="chat-list" ref="list">
            <div class="chat mb-2 p-1" :class="{me:c.user_id === user.id}" v-for="c in chatList">
                {{c.message}}
            </div>
        </div>
        <div class="message mb-2">

            <div class="input-group">
                <input type="text" class="form-control" placeholder="chat your message" @keydown.enter="send" v-model="message">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" @click="send">{{trans('menu.send')}}</button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        name: "ChatWindowComponent",
        props:{
            roomData:Object,
            user:Object,
            other:Object,
            socket:Object
        },
        mounted() {
            this.socket.off('message'); //기존에 등록된 메시지 큐 삭제
            this.socket.on('message', data => {
                this.chatList.push(data);
                this.properScroll();
            });
            this.chatList = this.roomData.messages;
            this.properScroll();
            //읽음 처리
            axios.put(`/chat/read/`, {id: this.roomData.id});
        },
        data(){
            return {
                message:'',
                chatList:[],
            }
        },
        methods:{
            send(){
                this.socket.emit('chat', this.message);
                this.message = ''
            },
            properScroll(){
                setTimeout(()=>{
                    this.$refs.list.scrollTop = this.$refs.list.scrollHeight - this.$refs.list.clientHeight;
                }, 50);
            }
        }
    }
</script>

<style scoped>
    .inner-window {
        height: 100%;
        background-color: #fff;
        display: grid;
        grid-template-rows: 60px 1fr 60px;
    }

    .chat {
        border: 1px solid #ddd;
        display: flex;
        width:80%;
        border-radius: 0.25rem;
    }
    .chat.me {
        transform: translateX(20%);
        background-color: #ffe928;
    }

    .chat-list {
        padding:10px;
        overflow-y: auto;
    }

    .profile-header {
        display: grid;
        grid-template-columns: 60px 1fr;
        grid-template-rows: 60px;
        width:100%;
        height:60px;
    }

    .profile {
        width:64px;
        height:64px;
    }

    .profile > img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
