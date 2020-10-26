<template>
    <div class="container-fluid">
        <div class="row" ref="header">
            <div class="col-12 p-0">
                <header-component :title="trans('title.get_assitance')" @toggle="searchToggle = !searchToggle" @back="back"></header-component>
            </div>
        </div>

        <div class="row">
            <div class="col-12 p-0" ref="search">
                <transition name="fade">
                    <div class="input-group mb-3" v-if="searchToggle">
                        <input type="text" class="form-control" :placeholder="trans('placeholder.find_certification')" id="searchInput" v-model="word">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" @click="searchCertification">{{trans('menu.search')}}</button>
                        </div>
                    </div>
                </transition>
            </div>
            <div class="col-12 p-0" ref="chat">
                <div class="skill-list p-4" v-if="mode === 0">
                    <chat-skill-component v-if="!isExpert" v-for="skill in skillList" :user="me" :skill="skill" :key="skill.id" :unread-list="unreadList" @openchat="openChat"></chat-skill-component>
                    <chat-user-component v-if="isExpert" :expert="me" v-for="user in userList" :user="user" :key="user.id" :unread-list="unreadList" @openchat="openChat"></chat-user-component>
                </div>
                <div class="loading" v-else-if="mode === 1">
                    <div class="spin-container"><i class="fas fa-spinner"></i></div>
                </div>
                <div class="chat-window" v-else-if="mode === 2">
                    <chat-window-component :room-data="roomData" :user="me" :other="other" :socket="socket"></chat-window-component>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import HeaderComponent from "./HeaderComponent";
    import ChatSkillComponent from "./Chat/ChatSkillComponent";
    import ChatUserComponent from "./Chat/ChatUserComponent";
    import ChatWindowComponent from "./Chat/ChatWindowComponent";

    export default {
        name: "ChatApp",
        components: {
            'header-component': HeaderComponent,
            'chat-skill-component': ChatSkillComponent,
            'chat-window-component': ChatWindowComponent,
            'chat-user-component':ChatUserComponent
        },
        mounted() {
            //소켓 연결
            this.socket = new io.connect('localhost:54000', {transports: ['websocket'], upgrade: false});
            this.socket.on('error-msg', data => {
                Swal.fire(data);
                this.mode = 0;
            });
            //수강중인 유저리스트 가져오기
            axios.get('/user').then(res => {
                this.me = res.data;
                this.isExpert = this.me.roleList.find(x => x.name === roleList.expert) !== undefined;
                if(this.isExpert){
                    axios.get('/expert/certificate').then(res => {
                        this.$store.commit('refreshUserList', res.data);
                    });
                }else{
                    axios.get('/skill/register/list').then(res => {
                        this.$store.commit('refreshSkillList', res.data);
                    });
                }
            });

            axios.get('/token').then(res => {
                this.token = res.data;
            });

            this.updateUnread();
        },
        data() {
            return {
                socket:null,
                searchToggle: false,
                word: '',
                mode: 0,  //0은 리스트 모드 1은 로딩 2는 채팅모드,
                roomData: null,
                me:null,
                isExpert:false,
                unreadList:[],
                token:'',
                other:null,
            }
        },
        methods: {
            updateUnread(){
                //읽지 안은 채팅 메시지가 있다면 가져온다.
                axios.get('/chat/unread').then(res => {
                    this.unreadList = res.data;
                });
            },
            back() {
                if(this.mode > 0) {
                    this.mode = 0;
                    this.updateUnread();
                }
                else location.href = "/main";
            },
            searchCertification() {
                //not yet....
            },
            openChat(other) {
                this.mode = 1; //로딩
                this.other = other;
                axios.get(`/chat/room/${other.id}`).then(res => {
                    this.socket.emit('login', {user:this.me, token:this.token});
                    this.roomData = res.data;
                    this.mode = 2;
                    this.socket.emit('join', this.roomData.id);
                    this.properHeight();
                });
            },
            properHeight() {
                //채팅 윈도우의 높이를 적절하게 조절
                let height = window.innerHeight - this.$refs.header.clientHeight + this.$refs.search.clientHeight
                this.$refs.chat.style.height = `${height}px`;
            }
        },
        computed: mapState({
            skillList: state => state.skillList.filter(x => x.expert_id !== null),
            userList: state => state.userList
        })
    }
</script>

<style scoped>
    .skill-list {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .chat-window {
        height: 100%;
    }
</style>
