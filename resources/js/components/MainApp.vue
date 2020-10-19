<template>
    <div class="row">
        <div class="col-12">
            <div class="loading" v-if="mode === 0">
                <div class="spin-container"><i class="fas fa-spinner"></i></div>
            </div>

            <div id="userMenu" v-if="mode === 1" class="panel-grid my-2 p-3">
                <div class="menu-panel bg-primary">
                    <a href="/skill/register"><i class="far fa-smile"></i></a>
                    <p>{{trans('menu.certify_my_skill')}}</p>
                </div>
                <div class="menu-panel bg-info">
                    <a href="/user/my_certification"><i class="fas fa-file-invoice"></i></a>
                    <p>{{trans('menu.my_certifications')}}</p>
                </div>
                <div class="menu-panel bg-danger">
                    <a href="/skill/assistance"><i class="fas fa-hands-helping"></i></a>
                    <p>{{trans('menu.get_assistance')}}</p>
                </div>

            </div>

            <div id="expertMenu" v-if="mode === 2" class="panel-grid my-2 p-3">
                <div class="menu-panel bg-primary">
                    <a href="/expert/request"><i class="fas fa-file-signature"></i></a>
                    <p>{{trans('menu.certifications_requests')}}</p>
                </div>
                <div class="menu-panel bg-info">
                    <a href="/expert/answer"><i class="fas fa-user-friends"></i></a>
                    <div class="tags unread" v-if="unread !== 0">
                        <span class="tag bg-dark text-white">{{trans('title.unread_message')}}</span>
                        <span class="tag bg-danger text-white">{{unread}}</span>
                    </div>
                    <p>{{trans('menu.answer_inquiries')}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MainApp",
        mounted() {
            //redirect page by user role
            axios.get('/user').then( res => {
                const user = res.data;
                if( user.roleList.find(x => x.name === roleList.admin) !== undefined) {
                    location.href = '/admin'; //goto admin page
                    return;
                }else if(user.roleList.find(x => x.name === roleList.manager) !== undefined){
                    location.href = '/manager'; //goto manager page
                    return;
                }
                let expert = user.roleList.find(x => x.name === roleList.expert);
                if(expert !== undefined) {
                    this.mode = 2;
                    return;
                }
                this.mode = 1;
            });
            //읽지 안은 채팅 메시지가 있다면 가져온다.
            axios.get('/chat/unread').then(res => {
                this.unread = res.data.reduce( (s, v) => s + v.unread, 0);
            });
        },
        data(){
            return {
                mode:0, // 0 is loading, 1 is user 2 is expert,
                unread:0,
            }
        }
    }
</script>

<style scoped>
    .panel-grid {
        display: grid;
        grid-template-columns: 1fr;
        grid-auto-rows: 200px;
        grid-gap:20px;
    }
    .menu-panel {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        color:#fff;
        position: relative;
    }
    .menu-panel a {
        font-size:60px;
        color:#fff;
        margin-bottom: 10px;
    }
    .menu-panel > p {
        font-size:20px;
    }

    .unread {
        position: absolute;
        top:10px;
        right:10px;
    }
</style>
