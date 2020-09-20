<template>
    <div>
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:page == 0}" @click="page = 0">{{trans('menu.registered_info')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:page == 1}" @click="page = 1">{{trans('menu.learning')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:page == 2}" @click="page = 2">{{trans('menu.not_yet')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:page == 3}" @click="page = 3">{{trans('menu.not_yet')}}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <register-info v-if="page == 0"></register-info>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import RegisteredInfoApp from "./MyPage/RegisteredInfoApp";

    export default {
        name: "MyPageApp",
        mounted() {
            axios.get('/skill/register/list').then( res => {
                this.$store.commit('refreshRegisteredList', res.data);
            });
        },
        components:{
            'register-info':RegisteredInfoApp
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
