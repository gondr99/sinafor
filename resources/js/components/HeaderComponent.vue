<template>
    <div class="bg-info header px-2">
        <div class="logo-title">
            <i class="far fa-lightbulb"></i>
            <span>{{title}}</span>
        </div>

        <div class="dot-menu">
            <i class="fas fa-search mr-4" @click="toggle"></i>
            <i class="fas fa-ellipsis-v" @click="toggleNav"></i>
        </div>
        <transition name="fade">
            <div class="dot-nav-menu" v-if="nav">
                <nav class="nav flex-column">
                    <a class="nav-link" href="/main">{{trans('menu.goto_main')}}</a>
                    <a class="nav-link" href="/user/mypage">{{trans('menu.my_page')}}</a>
                </nav>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        name: "HeaderComponent",
        props:{
            'title':String
        },
        data(){
            return {
                nav:false
            }
        },
        methods:{
            toggle(){
                this.$emit("toggle");
            },
            toggleNav(){
                this.nav = !this.nav;
            }
        }
    }
</script>

<style scoped>
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 50px;
        color:#fff;
    }
    .logo-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height:100%;
    }

    .logo-title > i {
        font-size:20px;
        margin-right: 5px;
    }

    .logo-title > span {
        font-size:25px;
    }
    .dot-menu {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height:100%;
        font-size:20px;
    }

    .dot-menu > i {
        cursor: pointer;
    }
    .dot-nav-menu {
        position: absolute;
        top:50px;
        right:0;
        background-color: #fff;
        border-radius: 0.5rem;
        z-index: 10;
    }
</style>
