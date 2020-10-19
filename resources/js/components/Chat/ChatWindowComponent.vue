<template>
    <div class="inner-window">
        <div class="chat-list">
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
            user:Object
        },
        mounted() {
            socket.off('message'); //기존에 등록된 메시지 큐 삭제
            socket.on('message', data => {
                this.chatList.push(data);
            });
            this.chatList = this.roomData.messages;

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
                socket.emit('chat', this.message);
                this.message = ''
            }
        }
    }
</script>

<style scoped>
    .inner-window {
        height: 100%;
        background-color: #fff;
        display: grid;
        grid-template-rows: 1fr 60px;
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
    }
</style>
