<template>
    <div>
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:page === 0}" @click="page = 0">{{trans('menu.profile_page')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:page === 1}" @click="page = 1">{{trans('menu.registered_info')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:page === 2}" @click="page = 2">{{trans('menu.not_yet')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:page === 3}" @click="page = 3">{{trans('menu.not_yet')}}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <profile-component v-if="page === 0"></profile-component>
                    <register-info v-if="page === 1"></register-info>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import RegisteredInfoApp from "../MyPage/RegisteredInfoApp";
    import ProfileComponent from "../MyPage/ProfileComponent";
    //import LearningComponent from "./MyPage/LearningComponent";  //not used....T.T
    export default {
        name: "MyPageApp",
        mounted() {
            axios.get('/skill/register/list').then( res => {
                this.$store.commit('refreshRegisteredList', res.data);
            });
            axios.get('/user').then(res => this.$store.commit('setUser', res.data));
        },
        components:{
            'register-info':RegisteredInfoApp,
            'profile-component':ProfileComponent
            //'learning-component':LearningComponent
        },
        data(){
            return {
                page:0
            }
        }
    }
</script>

<style scoped>
    .box {
        border:1px solid #ddd;
        border-top:none;
        padding:5px;
    }
</style>
